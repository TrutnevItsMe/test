<?namespace Intervolga\Custom\EventHandlers;

use Intervolga\Common\Tools\EventHandler;

class Main extends EventHandler
{
	static function onBeforeUserAdd(&$fields) {
		global $APPLICATION;
		if (!isset($GLOBALS['HL_CAN_CREATE_USERS']) || !$GLOBALS['HL_CAN_CREATE_USERS']) {
			$APPLICATION->throwException("Запрещено создавать новых пользователей!");
			return false;
		}
	}
}