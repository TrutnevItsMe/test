<?namespace Intervolga\Custom\EventHandlers;

use Intervolga\Common\Tools\EventHandler;
use Intervolga\Custom\Import\Sets;


/**
 * Class Iblock
 *
 * Event handlers for module "catalog".
 *
 * @package Intervolga\Custom\EventHandlers
 */
class Catalog extends EventHandler
{
	/**
	 * Event handler for "OnSuccessCatalogImport1C" event.
	 *
	 * @param array $arParams Параметры подключения компонента обмена
	 * @param string $xmlFileName абсолютный путь к xml файлу обмена
	 */
	public static function onSuccessCatalogImport1C($arParams, $xmlFileName)
	{
		// Intervolga Akentyev Logs
		file_put_contents(
			$_SERVER['DOCUMENT_ROOT'] . '/upload/logs/1c_catalog' . DATE('_Y_m_d') . '.log',
			var_export($xmlFileName, true),
			FILE_APPEND
		);
		//Sets::import($xmlFileName);
	}
}