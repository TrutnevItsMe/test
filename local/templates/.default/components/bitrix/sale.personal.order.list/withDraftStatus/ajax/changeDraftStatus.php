<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
if (!Bitrix\Main\Loader::includeModule('sale'))
	return;

use Intervolga\Custom\Tools\SaleUtil;

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$ID = $request->get("ID");

$needPaymentStatus = SaleUtil::getStatusIdByXml(SALE_NEED_PAYMENT_STATUS_XML_ID);
$isUpdate = \CSaleOrder::Update($ID, ["STATUS_ID" => $needPaymentStatus]);

$result = [
	'isSuccess' => $isUpdate
];

header("Content-type: application/json; charset=utf-8");
echo json_encode($result);