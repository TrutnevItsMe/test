<?namespace Intervolga\Common\Main;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class SendPass
{
	private static $newUserLogin = false;
	private static $newUserPass = false;

	public static function OnBeforeUserAdd(&$arFields)
	{
		//$arFields['LOGIN'] = $arFields['EMAIL'];
		self::$newUserLogin = $arFields['LOGIN'];
		self::$newUserPass = $arFields['PASSWORD'];
	}

	public static function OnOrderNewSendEmail($ID, &$eventName, &$arFields)
	{
		self::PrepareFields($arFields);
	}

	private static function PrepareFields(&$arFields)
	{
		if (self::$newUserPass === false)
		{
			$arFields['AUTO_LOGIN'] = '';
			$arFields['AUTO_PASSWORD'] = '';
			$arFields['AUTO_LOGIN_PASSWORD'] = '';
			$arFields['AUTO_LOGIN_PASSWORD_HTML'] = '';
		}
		else
		{
			$arFields['AUTO_LOGIN'] = self::$newUserLogin;
			$arFields['AUTO_PASSWORD'] = self::$newUserPass;

			$arFields['AUTO_LOGIN_PASSWORD'] = "\n" . Loc::getMessage('INTERVOLGA_COMMON_ALOG') . self::$newUserLogin;
			$arFields['AUTO_LOGIN_PASSWORD'] .= "\n" . Loc::getMessage('INTERVOLGA_COMMON_APAS') . self::$newUserPass;

			$arFields['AUTO_LOGIN_PASSWORD_HTML'] = "<br>" . Loc::getMessage('INTERVOLGA_COMMON_ALOG') . self::$newUserLogin;
			$arFields['AUTO_LOGIN_PASSWORD_HTML'] .= "<br>" . Loc::getMessage('INTERVOLGA_COMMON_APAS') . self::$newUserPass;
		}
	}

	public static function OnSendUserInfo(&$arParams)
	{
		self::PrepareFields($arParams['FIELDS']);
	}
}


