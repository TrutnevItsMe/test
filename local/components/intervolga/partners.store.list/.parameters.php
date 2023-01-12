<?php

use Intervolga\Custom\ORM\MagazinyPartnerovTable;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$fields = MagazinyPartnerovTable::getMap();
$arFields = [];

foreach ($fields as $CODE => $arField)
{
	$arFields[$CODE] = $arField["title"] . " [" . $CODE . "]";
}

$arComponentParameters = [
	"GROUPS" => [
		"FILTER" => [
			"NAME" => Loc::getMessage("FILTER_GROUP_NAME")
		],
		"MAP" => [
			"NAME" => Loc::getMessage("MAP_GROUP_NAME")
		],
		"PAGINATION" => [
			"NAME" => Loc::getMessage("PAGINATION_GROUP_NAME")
		]
	],
	"PARAMETERS" => [
		"COORDINATES_DELIMITER" => [
			"PARENT" => "BASE",
			"NAME" => Loc::getMessage("COORDINATES_DELIMITER"),
			"TYPE" => "STRING",
			"DEFAULT" => "_"
		],
		"USE_FILTER" => [
			"PARENT" => "FILTER",
			"NAME" => Loc::getMessage("USE_FILTER_NAME"),
			"TYPE" => "CHECKBOX",
			"REFRESH" => "Y",
			"DEFAULT" => "N"
		],
		"USE_MAP" => [
			"PARENT" => "MAP",
			"NAME" => Loc::getMessage("USE_MAP"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			"REFRESH" => "Y"
		],
		"USE_PAGINATION" => [
			"PARENT" => "PAGINATION",
			"NAME" => Loc::getMessage("USE_PAGINATION"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			"REFRESH" => "Y"
		]
	]
];

if ($arCurrentValues["USE_FILTER"] == "Y")
{
	$arFilterFields = $arDisplayFields = array_diff_key(
		$arFields,
		[
			"ID" => "EXCLUDED_KEY",
		]
	);

	$arComponentParameters["PARAMETERS"]["FILTER_VALUES"] = [
		"PARENT" => "FILTER",
		"NAME" => Loc::getMessage("FILTER_VALUES_NAME"),
		"TYPE" => "LIST",
		"MULTIPLE" => "Y",
		"VALUES" => $arFilterFields
	];
}

if ($arCurrentValues["USE_MAP"] == "Y")
{
	$arComponentParameters["PARAMETERS"]["SHOW_MAP_ZOOM"] = [
		"PARENT" => "MAP",
		"NAME" => Loc::getMessage("SHOW_MAP_ZOOM"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y"
	];

	$arComponentParameters["PARAMETERS"]["SHOW_MAP_RULER"] = [
		"PARENT" => "MAP",
		"NAME" => Loc::getMessage("SHOW_MAP_RULER"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y"
	];

	$arComponentParameters["PARAMETERS"]["SHOW_MAP_FULLSCREEN"] = [
		"PARENT" => "MAP",
		"NAME" => Loc::getMessage("SHOW_MAP_FULLSCREEN"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y"
	];
}

if ($arCurrentValues["USE_PAGINATION"] == "Y")
{
	$arComponentParameters["PARAMETERS"]["PAGINATION_AJAX"] = [
		"PARENT" => "PAGINATION",
		"NAME" => Loc::getMessage("PAGINATION_AJAX"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y"
	];

	$arComponentParameters["PARAMETERS"]["PAGINATION_TEMPLATE"] = [
		"PARENT" => "PAGINATION",
		"NAME" => Loc::getMessage("PAGINATION_TEMPLATE"),
		"TYPE" => "STRING",
	];

	$arComponentParameters["PARAMETERS"]["PAGINATION_COUNT_ELEMENTS"] = [
		"PARENT" => "PAGINATION",
		"NAME" => Loc::getMessage("PAGINATION_COUNT_ELEMENTS"),
		"TYPE" => "STRING",
		"DEFAULT" => 6
	];
}