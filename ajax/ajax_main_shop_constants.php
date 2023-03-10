<?php

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
const MAIN_STORE_IDS = [376];

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
if ($request->isPost()) {
	echo json_encode(MAIN_STORE_IDS);
}
