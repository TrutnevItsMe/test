<? namespace Intervolga\Common\Main;

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
     * Инициализация пользовательского свойства для главного модуля
     *
     * @return array
     */
    public function GetUserTypeDescription()
    {
        return array(
            "USER_TYPE_ID" => "IntervolgaLocation",
            "CLASS_NAME" => __CLASS__,
            "DESCRIPTION" => Loc::getMessage("INTERVOLGA_COMMON.LOCATION_PROPERTY.DESCRIPTION"),
            "BASE_TYPE" => "int",
            "VIEW_CALLBACK" => array(__CLASS__, 'getPublicView'),
        );
    }

    public function GetDBColumnType($arUserField)
    {
        global $DB;
        switch(strtolower($DB->type))
        {
            case "mysql":
                return "int(18)";
            case "oracle":
                return "number(18)";
            case "mssql":
                return "int";
        }
    }

	/**
	 * Вернуть HTML отображения элемента управления для редактирования значений свойства в административной части.
	 *
	 * @param string $name
	 * @param $value
	 * @param bool $is_ajax
	 * @return string
	 */
    public static function getEditHTML($name, $value, $is_ajax = false)
    {

        static::getSiteLocations($value);

        // "Пустое значение" для списка
        $siteLocations = static::$siteLocations;
        $defaultValue = array(
            'ID' => 0,
            'NAME' => Loc::getMessage('INTERVOLGA_COMMON.LOCATION_PROPERTY.DEFAULT_LIST_VALUE')
        );
        array_unshift($siteLocations, $defaultValue);

        // Вывод поля
        $html = '<div class="intervolga-location">';
        $html .= '<select name="' . $name . '">';
        foreach ($siteLocations as $siteLocation) {
            $selectedProp = $siteLocation['ID'] == $value ? 'selected' : '';
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
     * Представление свойства
     *
     * @param $name
     * @param $value
     * @return string
     */
    function getViewHTML($name, $value)
    {
        return '<div>' . $value . '</div>';
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
     * Метод возвращает отображение значения свойства
     *
     * @param $arProperty
     * @return mixed
     */
    public static function getPublicView($arProperty)
    {
        $locationInfo = static::getLocationInfo($arProperty, $arProperty['VALUE']);
        return $locationInfo['NAME'];
    }

    /**
     * Рредактирование свойства в форме (главный модуль)
     *
     * @param $arUserField
     * @param $arHtmlControl
     * @return string
     */
    function GetEditFormHTML($arUserField, $arHtmlControl)
    {
        return self::getEditHTML($arHtmlControl['NAME'], $arHtmlControl['VALUE'], false);
    }

    /**
     * Редактирование свойства в списке (главный модуль)
     *
     * @param $arUserField
     * @param $arHtmlControl
     * @return mixed
     */
    function GetAdminListEditHTML($arUserField, $arHtmlControl)
    {
        return self::getEditHTML($arHtmlControl['NAME'], $arHtmlControl['VALUE'], true);
    }

    /**
     * Представление свойства в списке (главный модуль)
     *
     * @param $value
     * @param $strHTMLControlName
     * @return mixed
     */
    function GetAdminListViewHTML($value, $strHTMLControlName)
    {
        return self::getViewHTML($strHTMLControlName['VALUE'], $value['VALUE']);
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
    public function getSettingsHTML($arProperty, $strHTMLControlName, &$arPropertyFields)
    {
        $arPropertyFields = array(
            "HIDE" => array(
                "MULTIPLE",
                "COL_COUNT",
                "ROW_COUNT",
                "DEFAULT_VALUE",
                "WITH_DESCRIPTION",
                "MULTIPLE_CNT"
            )
        );

        return '';
    }

    /**
     * Возвращает список местоположений для текущего сайта.
     *
     * @param array $arProperty Метаданные свойства
     * @return array список местоположений для текущего сайта
     */
    protected static function getSiteLocations($arProperty)
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