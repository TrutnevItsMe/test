<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

define('ADMIN_MODULE_NAME', 'intervolga.common');
if (Loader::includeModule('intervolga.common') )
{
	$table = new \Intervolga\Common\Admin\SentMessages();
}
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');
if ($table && $USER->isAdmin())
{
	$APPLICATION->SetTitle('Отправленные сообщения');
	$table->initializeFilter();
	$table->show();
}
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');