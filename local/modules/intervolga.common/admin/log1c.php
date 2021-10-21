<?
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

define('ADMIN_MODULE_NAME', 'intervolga.common');
if (Loader::includeModule('intervolga.common'))
{
	$table = new \Intervolga\Common\Admin\Log1CPage();
}
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');
if ($table && Loader::includeModule('intervolga.common') && $USER->isAdmin())
{
	$APPLICATION->setTitle(Loc::getMessage('INTERVOLGA_COMMON.LOG_1C_PAGE'));
	$table->initializeFilter();
	$table->show();
}
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');