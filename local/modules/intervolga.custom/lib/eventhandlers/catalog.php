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
		if (strpos($xmlFileName, 'import') !== false) {
			Sets::import($xmlFileName);
		}
	}
}