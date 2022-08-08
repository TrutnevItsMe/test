<?namespace Intervolga\Custom\EventHandlers;

use Intervolga\Common\Tools\EventHandler;

class Iblock extends EventHandler{

	public static function OnBeforeIBlockSectionUpdate(&$arParams){

		// Отменить изменение названий разделов, если они переданы из 1С
		if ($_REQUEST["mode"] == "import"){
			unset($arParams["NAME"]);
		}
	}
}