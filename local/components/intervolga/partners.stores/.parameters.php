<?php

use Intervolga\Custom\ORM\MagazinyPartnerovTable;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arComponentParameters = [
	"GROUPS" => [
		"FILTER" => [
			"NAME" => Loc::getMessage("FILTER_GROUP_NAME")
		],
	],
	"PARAMETERS" => [
		"USE_FILTER" => [
			"PARENT" => "FILTER",
			"NAME" => Loc::getMessage("USE_FILTER_NAME"),
			"TYPE" => "CHECKBOX",
			"REFRESH" => "Y",
			"DEFAULT" => "Y"
		],
		"COORDINATES_DELIMITER" => [
			"PARENT" => "BASE",
			"NAME" => Loc::getMessage("COORDINATES_DELIMITER"),
			"TYPE" => "STRING",
			"DEFAULT" => "_"
		]
	]
];

if ($arCurrentValues["USE_FILTER"] == "Y")
{
	$fields = MagazinyPartnerovTable::getMap();
	$arFilterFields = [];

	foreach ($fields as $CODE => $arField)
	{
		if ($CODE == "ID")
		{
			continue;
		}

		$arFilterFields[$CODE] = $arField["title"] . " [" . $CODE . "]";
	}

	$arComponentParameters["PARAMETERS"]["FILTER_VALUES"] = [
		"PARENT" => "FILTER",
		"NAME" => GetMessage("FILTER_VALUES_NAME"),
		"TYPE" => "LIST",
		"MULTIPLE" => "Y",
		"VALUES" => $arFilterFields
	];
}