<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$ID = "ID: " . $request->get("ID");
\Bitrix\Main\Diag\Debug::dumpToFile($ID, $varName = '', $fileName = 'log.txt');