<? namespace Intervolga\Common\Iblock;

use Bitrix\Main\Localization\Loc;
use Intervolga\Common\Location;

Loc::loadMessages(__FILE__);

/**
 * Class LocationProperty свойство инфоблока для хранения местоположения
 */
class LocationProperty
{
    protected static $siteLocations = array();

    /**
     * Возвращает массив, описывающий поведение пользовательского свойства.
     * Вызывается по событию OnIBlockPropertyBuildList.
     *
     * @return array массив, описывающий поведение пользовательского свойства
     */
    public static function GetUserTypeDescription()
    {
        return array(
            "PROPERTY_TYPE" => "S",
            "USER_TYPE" => "IntervolgaLocation",
            "DESCRIPTION" => Loc::getMessage("INTERVOLGA_COMMON.LOCATION_PROPERTY.DESCRIPTION"),
            "GetPropertyFieldHtml" => Array(__CLASS__, "getPropertyFieldHtml"),
            "ConvertToDB" => Array(__CLASS__, "convertToDB"),
            "ConvertFromDB" => Array(__CLASS__, "convertFromDB"),
            "GetAdminListViewHTML" => array(__CLASS__, "getAdminListViewHTML"),
            "GetPublicViewHTML" => Array(__CLASS__, "getPublicViewHTML"),
            "GetLength" => Array(__CLASS__, "getLength"),
            "CheckFields" => Array(__CLASS__, "checkFields"),
            "GetSettingsHTML" => Array(__CLASS__, "getSettingsHTML"),
            "PrepareSettings" => Array(__CLASS__, "prepareSettings"),
            "GetPropertyFieldHtmlMulty" => Array(__CLASS__, "getPropertyFieldHtmlMulty"),
        );
    }

    /**
     * Вернуть HTML отображения элемента управления для редактирования значений свойства в административной части.
     *
     * @param array $arProperty Метаданные свойства
     * @param array $value Значение свойства
     * @param array $strHTMLControlName Имена элементов управления для заполнения значения свойства и его описания
     * @return string Строка
     */
    public static function getPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
    {
        static::getSiteLocations($arProperty);

        // "Пустое значение" для списка
        $siteLocations = static::$siteLocations;
        $defaultValue = array(
            'ID' => 0,
            'NAME' => Loc::getMessage('INTERVOLGA_COMMON.LOCATION_PROPERTY.DEFAULT_LIST_VALUE')
        );
        array_unshift($siteLocations, $defaultValue);

        // Вывод поля
        if($arProperty["MULTIPLE"] == "Y")
        {
            $html = '<div class="intervolga-location">';
            $html .= '<select multiple name="' . $strHTMLControlName['VALUE'] . '" size="'.$arProperty['ROW_COUNT'].'">';
            foreach ($siteLocations as $siteLocation) {
                $selectedProp = $siteLocation['ID'] == $value['VALUE'] ? 'selected' : '';
                $html .= '<option value="'
                    . $siteLocation['ID']
                    . '" '
                    . $selectedProp
                    . '>'
                    . $siteLocation['NAME']
                    . '</option>';
            }
            $html .= '</select>';
            $html .= '</div>';
        }
        else
        {
            $html = '<div class="intervolga-location">';
            $html .= '<select name="' . $strHTMLControlName['VALUE'] . '" size="'.$arProperty['ROW_COUNT'].'">';
            foreach ($siteLocations as $siteLocation) {
                $selectedProp = $siteLocation['ID'] == $value['VALUE'] ? 'selected' : '';
                $html .= '<option value="'
                    . $siteLocation['ID']
                    . '" '
                    . $selectedProp
                    . '>'
                    . $siteLocation['NAME']
                    . '</option>';
            }
            $html .= '</select>';
            $html .= '</div>';
        }

        return $html;
    }


    public static function getPropertyFieldHtmlMulty($arProperty, $value, $strHTMLControlName)
    {
        static::getSiteLocations($arProperty);

        // "Пустое значение" для списка
        $siteLocations = static::$siteLocations;
        $defaultValue = array(
            'ID' => 0,
            'NAME' => Loc::getMessage('INTERVOLGA_COMMON.LOCATION_PROPERTY.DEFAULT_LIST_VALUE')
        );
        array_unshift($siteLocations, $defaultValue);

        $arValues = array();
        foreach($value as $valueItem)
        {
            $arValues[] = $valueItem["VALUE"];
        }

        // Вывод поля
        $html = '<div class="intervolga-location">';
        $html .= '<select multiple name="' . $strHTMLControlName['VALUE'] . '[]" size="'.$arProperty['MULTIPLE_CNT'].'">';
        foreach ($siteLocations as $siteLocation) {
            if (in_array($siteLocation['ID'], $arValues)) {
                $selectedProp = 'selected';
            }
            else
            {
                $selectedProp = '';
            }
            $html .= '<option value="'
                . $siteLocation['ID']
                . '" '
                . $selectedProp
                . '>'
                . $siteLocation['NAME']
                . '</option>';
        }
        $html .= '</select>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Функция должна вернуть безопасный HTML отображения значения свойства в публичной части сайта.
     * Если она вернет пустое значение, то значение отображаться не будет.
     *
     * @param array $arProperty
     * @param string $value
     * @param array $strHTMLControlName
     *
     * @return string
     */
    public static function getPublicViewHTML($arProperty, $value, $strHTMLControlName)
    {
        $locationInfo = static::getLocationInfo($arProperty, $value['VALUE']);
        return $locationInfo['NAME'];
    }

    /**
     * Преобразование значения в пригодный для БД формат.
     *
     * @param array $arProperty Метаданные свойства
     * @param array $value Значение свойства
     * @return string Строка представление для БД
     */
    public static function convertToDB($arProperty, $value)
    {
        return $value;
    }

    /**
     * Преобразование значения из пригодного для БД формата.
     *
     * @param array $arProperty Метаданные свойства
     * @param string $value Значение свойства прочитанное из базы данных
     * @return array Внешнее представление значения свойства
     */
    public static function convertFromDB($arProperty, $value)
    {
        return $value;
    }

    /**
     * Вернуть безопасный HTML отображения значения свойства в списке элементов административной части.
     *
     * @param array $arProperty Метаданные свойства
     * @param array $value Значение свойства
     * @param array $strHTMLControlName Имена элементов управления для заполнения значения свойства и его описания
     * @return string Строка
     */
    public static function getAdminListViewHTML($arProperty, $value, $strHTMLControlName)
    {
        $html = '';

        $locationInfo = static::getLocationInfo($arProperty, $value['VALUE']);
        if(!empty($locationInfo)) {
            $html .= $locationInfo['NAME'] . ' [' . $locationInfo['ID'] . ']';
        }

        return $html;
    }

    /**
     * Возвращает длину свойства.
     *
     * @param array $property
     * @param array $value
     *
     * @return int
     */
    public static function getLength($property, $value)
    {
        return empty($value['VALUE'])  ? 0 : strlen($value['VALUE']);
    }

    /**
     * Функция должна проверить корректность значения свойства и вернуть массив.
     * Пустой если ошибок нет и с сообщениями об ошибках если есть.
     *
     * Вызывается перед добавлением или изменением элемента.
     *
     * @param array $arProperty
     * @param array $value
     *
     * @return array
     */
    public static function checkFields($arProperty, $value)
    {
        $errors = array();

        if(!empty($value['VALUE'])) {
            static::getSiteLocations($arProperty);
            $siteLocationIds = array_map(function ($location) {
                return $location['ID'];
            }, static::$siteLocations);

            $searchResult = array_search($value['VALUE'], $siteLocationIds, true);
            if($searchResult === false) {
                $errors[] = Loc::getMessage('INTERVOLGA_COMMON.LOCATION_PROPERTY.WRONG_LOCATION_ID');
            }
        }

        return $errors;
    }

    /**
     * Получить дополнительные настройки свойства (при редактировании самого свойства, а не значений).
     *
     * @param array $arProperty Метаданные свойства
     * @param array $strHTMLControlName Имя элемента управления для заполнения настроек свойства.
     * @param array $arPropertyFields
     * @return string
     */
    public static function getSettingsHTML($arProperty, $strHTMLControlName, &$arPropertyFields)
    {
        $arPropertyFields = array(
            "HIDE" => array(
                "MULTIPLE",
                "COL_COUNT",
                "ROW_COUNT",
                "DEFAULT_VALUE",
                "WITH_DESCRIPTION",
                "MULTIPLE_CNT"
            ),
        );

        return '';
    }

    /**
     * Получение id сайта для текущего инфоблока.
     *
     * @param array $arProperty Метаданные свойства
     * @return string id сайта текущего инфоблока.
     */
    protected static function getSiteId($arProperty)
    {
        $siteId = '';

        $dbRes = \CIBlock::GetList(
            array('SORT' => 'ASC'),
            array(
                'ID' => $arProperty['IBLOCK_ID']
            )
        );

        if ($iBLock = $dbRes->Fetch()) {
            $siteId = $iBLock['LID'];
        }

        return $siteId;
    }

    /**
     * Возвращает список местоположений для текущего сайта.
     *
     * @param array $arProperty Метаданные свойства
     * @return array список местоположений для текущего сайта
     */
    protected static function getSiteLocations(array $arProperty)
    {
        if(empty(static::$siteLocations)) {
            static::$siteLocations = Location::getLocationsForSite(SITE_ID_IN__ADMIN, LANGUAGE_ID);
        }

        return static::$siteLocations;
    }

    /**
     * Возвращает информацию об указанном местоположении ($siteId).
     *
     * @param array $arProperty Метаданные свойства
     * @param $locationId идентификатор местоположения
     * @return array информация о местоположении
     */
    protected static function getLocationInfo(array $arProperty, $locationId)
    {
        static::getSiteLocations($arProperty);

        $locationInfo = array();
        foreach (static::$siteLocations as $siteLocation) {
            if($siteLocation['ID'] == $locationId) {
                return $siteLocation;
            }
        }

        return $locationInfo;
    }
}