<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
require_once(__DIR__.'/functions.php');

\Bitrix\Main\EventManager::getInstance()->addEventHandler(
    "iblock",
    "OnIBlockPropertyBuildList",
    array('\Intervolga\Common\Iblock\LocationProperty', 'GetUserTypeDescription')
);

\Bitrix\Main\EventManager::getInstance()->addEventHandler(
    "main",
    "OnUserTypeBuildList",
    array('\Intervolga\Common\Main\LocationProperty', 'GetUserTypeDescription')
);

\Bitrix\Main\EventManager::getInstance()->addEventHandler(
	"iblock",
	"OnIBlockPropertyBuildList",
	array('\Intervolga\Common\Iblock\LocationGroupProperty', 'GetUserTypeDescription')
);
