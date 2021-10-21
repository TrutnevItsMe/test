<? namespace Intervolga\Common\Iblock;

use Bitrix\Main\Localization\Loc;
use \Bitrix\Sale\Location\GroupTable;
use Bitrix\Main\Loader;


Loc::loadMessages(__FILE__);

/**
 * Class LocationGroupProperty свойство инфоблока для хранения привязки к группе местоположений
 *
 * @package Intervolga\Common\Iblock
 * @author Дмитрий Матюшечкин
 */
class LocationGroupProperty
{
    public static function GetUserTypeDescription() {
        return Array(
            'PROPERTY_TYPE'        => 'S',
            'USER_TYPE'            => 'IntervolgaLocationGroup',
            'DESCRIPTION'          => Loc::GetMessage('INTERVOLGA_COMMON.IBLOCK_PROP_LOCATION_GROUP_DESC'),
            'GetAdminListViewHTML' => array(__CLASS__, 'GetPublicViewHTML'),
            'GetPublicViewHTML'    => array(__CLASS__, 'GetPublicViewHTML'),
            'GetPublicEditHTML'    => array(__CLASS__, 'GetPublicEditHTML'),
            'GetPropertyFieldHtml' => array(__CLASS__, 'GetPropertyFieldHtml'),
            'AddFilterFields'      => array(__CLASS__, 'AddFilterFields'),
            'GetPublicFilterHTML'  => array(__CLASS__, 'GetPublicFilterHTML'),
            'GetAdminFilterHTML'   => array(__CLASS__, 'GetPublicFilterHTML'),
            'ConvertToDB'          => array(__CLASS__, 'ConvertToDB'),
            'ConvertFromDB'        => array(__CLASS__, 'ConvertFromDB'),
            'GetSearchContent'     => array(__CLASS__, 'GetSearchContent'),
            'PrepareSettings'      => array(__CLASS__, 'PrepareSettings'),
        );
    }

    function GetLocationGroups($exFilter = false) {
        $result = array();
        if (Loader::includeModule('sale')) {
            $filter = array("SALE_LOCATION_GROUP_NAME_LID" => LANGUAGE_ID);
            if ($exFilter && is_array($exFilter)) {
                $filter = array_merge($filter, $exFilter);
            }
            $dbLocations = GroupTable::getList(array(
                'filter' => $filter,
                'select' => array("*", "NAME")
            ));
            while ($location = $dbLocations->Fetch()) {
                $location['NAME'] = $location['SALE_LOCATION_GROUP_NAME_NAME'];
                $result[] = $location;
            }
        }
        return $result;
    }

    function GetPublicViewHTML($arProperty, $value, $strHTMLControlName)
    {
        $HTMLResult = "Не задано";
        if (intval($value['VALUE']) > 0) {

            $location = self::GetLocationGroups(array('ID' => $value['VALUE']));
            $location = $location[0];
            $groupEditUrl = Loc::GetMessage('INTERVOLGA_COMMON.IBLOCK_PROP_LOCATION_GROUP_EDIT_URL',
                array("#ID#" => $value['VALUE'], "#LANGUAGE_ID#" => LANGUAGE_ID));

            $HTMLResult = '<a href="' . $groupEditUrl . '">' . $location['NAME'] . '</a>';

        }
        return $HTMLResult;
    }

    function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
    {
        $HTMLResult = '<select name="' . $strHTMLControlName['VALUE'] . '">';
        $HTMLResult .= '<option ' . ($value['VALUE'] == '' ? 'selected' : '') . ' value="">Не задано</option>';

        $locations = self::GetLocationGroups();

        foreach($locations as $location) {
            $HTMLResult .= '<option ' . ($location['ID'] == $value['VALUE'] ? 'selected' : '') . ' value="' . $location['ID'] . '">' . $location['NAME'] . '</option>';
        }
        $HTMLResult .= '</select>';
        return $HTMLResult;
    }


    function GetPublicEditHTML($arUserField)
    {
        $HTMLResult = '<select name="' . $arUserField['NAMES'][0] . '">';
        $HTMLResult .= '<option ' . ($arUserField['VALUES'][0] == '' ? 'selected' : '') . ' value="">Не задано</option>';

        $locations = self::GetLocationGroups();

        foreach($locations as $location) {
            $HTMLResult .= '<option ' . ($location['ID'] == $arUserField['VALUES'][0] ? 'selected' : '') . ' value="' . $location['ID'] . '">' . $location['NAME'] . '</option>';
        }
        $HTMLResult .= '</select>';
        return $HTMLResult;
    }

    function GetSearchContent($arProperty, $value, $strHTMLControlName)
    {
        $propId = $arProperty;
        $propParams = \CIBlockProperty::GetByID($propId)->Fetch();
        return $value['VALUE'] ? $propParams['NAME'] : '';
    }

    function ConvertToDB($arProperty, $value)
    {
        return $value;
    }

    function ConvertFromDB($arProperty, $value)
    {
        return $value;
    }
}