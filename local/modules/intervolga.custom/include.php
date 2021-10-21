<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
require_once __DIR__."/functions.php";
require_once __DIR__."/constants.php";

$dir = new \Bitrix\Main\IO\Directory(__DIR__ . "/lib/eventhandlers/");
if(CModule::IncludeModule("intervolga.common"))
{
	\Intervolga\Common\Tools\EventAutoLoader::addDirectoryEventHandlers("\\Intervolga\\Custom\\EventHandlers\\", $dir);

	/**
	 * Add Monolog loggers to Registry here
	 */
}
