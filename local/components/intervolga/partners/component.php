<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

$arDefaultUrlTemplates404 = [
	'list'    => 'index.php',
	'detail' => '#ELEMENT_ID#/',
];

$arDefaultVariableAliases404 = [];
$arDefaultVariableAliases    = [];
$arComponentVariables        = ['ELEMENT_ID'];
$SEF_FOLDER                  = '';
$arUrlTemplates              = [];


if ($arParams['SEF_MODE'] == 'Y') {

	$arVariables = [];

	$arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates(
		$arDefaultUrlTemplates404,
		$arParams['SEF_URL_TEMPLATES']
	);

	$arVariableAliases = CComponentEngine::MakeComponentVariableAliases(
		$arDefaultVariableAliases404,
		$arParams['VARIABLE_ALIASES']
	);

	$componentPage = CComponentEngine::ParseComponentPath(
		$arParams['SEF_FOLDER'],
		$arUrlTemplates,
		$arVariables
	);

	if (strlen($componentPage) <= 0) {
		$componentPage = 'list';
	}

	CComponentEngine::InitComponentVariables(
		$componentPage,
		$arComponentVariables,
		$arVariableAliases,
		$arVariables);

	$SEF_FOLDER = $arParams['SEF_FOLDER'];
} else {
	$arVariables = [];

	$arVariableAliases = CComponentEngine::MakeComponentVariableAliases(
		$arDefaultVariableAliases,
		$arParams['VARIABLE_ALIASES']
	);

	CComponentEngine::InitComponentVariables(
		false,
		$arComponentVariables,
		$arVariableAliases,
		$arVariables
	);

	$componentPage = '';

	if (intval($arVariables['ELEMENT_ID']) > 0) {
		$componentPage = 'detail';
	} else {
		$componentPage = 'list';
	}
}

$arResult = [
	'FOLDER'        => $SEF_FOLDER,
	'URL_TEMPLATES' => $arUrlTemplates,
	'VARIABLES'     => $arVariables,
	'ALIASES'       => $arVariableAliases,
];

$this->IncludeComponentTemplate($componentPage);