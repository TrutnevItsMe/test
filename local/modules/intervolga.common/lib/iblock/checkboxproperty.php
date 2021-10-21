<? namespace Intervolga\Common\Iblock;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

/**
 * Class CheckboxProperty свойство инфоблока для хранения булевого значения
 *
 * @package Intervolga\Common\Iblock
 * @author Иван Ходненко
 */
class CheckboxProperty
{
	public static function OnIBlockPropertyBuildList() {
		return Array(
			'PROPERTY_TYPE'        => 'N',
			'USER_TYPE'            => 'IntervolgaCheckbox',
			'DESCRIPTION'          => Loc::GetMessage('INTERVOLGA_COMMON.IBLOCK_PROP_CHECKBOX_DESC'),
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

	function GetPublicViewHTML($arProperty, $value, $strHTMLControlName)
	{
		return Loc::GetMessage($value['VALUE'] ? 'INTERVOLGA_COMMON.IBLOCK_PROP_CHECKBOX_YES' : 'INTERVOLGA_COMMON.IBLOCK_PROP_CHECKBOX_NO');
	}

	function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
	{
		return '<input type="hidden" name="' . $strHTMLControlName['VALUE'] . '" value="" /><input type="checkbox" name="'
			. $strHTMLControlName['VALUE'] . '" value="1" ' . (intval($value['VALUE']) ? 'checked="checked"' : '').'/>';
	}

	function AddFilterFields($arProperty, $strHTMLControlName, &$arFilter, &$filtered)
	{
		if(isset($_REQUEST[$strHTMLControlName['VALUE']]))
		{
			$prefix = $_REQUEST[$strHTMLControlName['VALUE']] ? '=' : '!=';
			$arFilter[$prefix.'PROPERTY_'.$arProperty['ID']] = 1;
			$filtered = TRUE;
		}
	}

	function GetPublicFilterHTML($arProperty, $strHTMLControlName)
	{
		$selectValue = $_REQUEST[$strHTMLControlName['VALUE']];
		$select = '<select name="'.$strHTMLControlName['VALUE'].'">
			<option value="" >' . Loc::GetMessage('INTERVOLGA_COMMON.IBLOCK_PROP_CHECKBOX_NA').'</option>
			<option value="1" ' . ($selectValue == 1 ? 'selected="selected"' : '' ).'>' . Loc::GetMessage('INTERVOLGA_COMMON.IBLOCK_PROP_CHECKBOX_YES') . '</option>
			<option value="0" '.($selectValue == 0 ? 'selected="selected"' : '' ).'>' . Loc::GetMessage('INTERVOLGA_COMMON.IBLOCK_PROP_CHECKBOX_NO') . '</option>
		</select>';
		return $select;
	}

	function GetPublicEditHTML($arUserField)
	{
		$checked = (intval($arUserField['VALUES'][0]) == 1) ? 'checked="checked"' : '';
		$html = '<input type="hidden" name="' . $arUserField['NAMES'][0] . '" value="0"/>';
		$html .= '<div class="checkbox"><label><input ' . $checked . ' type="checkbox" name="' . $arUserField['NAMES'][0] . '" value="1"></label></div>';

		return $html;
	}

	function GetSearchContent($arProperty, $value, $strHTMLControlName)
	{
		$propId = $arProperty;
		$propParams = \CIBlockProperty::GetByID($propId)->Fetch();
		return $value['VALUE'] ? $propParams['NAME'] : '';
	}

	function ConvertToDB($arProperty, $value)
	{
		$value['VALUE'] = $value['VALUE'] ? 1 : 0;
		return $value;
	}

	function ConvertFromDB($arProperty, $value)
	{
		$value['VALUE'] = $value['VALUE'] ? 1 : 0;
		return $value;
	}

	function GetLength($arProperty, $value)
	{
		return 1;
	}

	function PrepareSettings($arFields)
	{
		$arFields["MULTIPLE"] = 'N';
		return $arFields;
	}
}