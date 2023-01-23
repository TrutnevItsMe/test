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

$arDisplayFields = array_diff_key(
	$arFields,
	[
		"ID" => "EXCLUDED_KEY",
		"UF_NAME" => "EXCLUDED_KEY"
	]
);

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
		],
		"DETAIL" => [
			"NAME" => Loc::getMessage("DETAIL_GROUP_NAME")
		]
	],
	"PARAMETERS" => [
		"DISPLAY_FIELDS" => [
			"PARENT" => "BASE",
			"NAME" => Loc::getMessage("DISPLAY_FIELDS"),
			"TYPE" => "LIST",
			"VALUES" => $arDisplayFields,
			"MULTIPLE" => "Y"
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
		],
		"SEF_MODE" => [
			"list" => [
				"NAME" => Loc::getMessage("SEF_LIST"),
			],
			"detail" => [
				"NAME" => Loc::getMessage("SEF_DETAIL"),
				"DEFAULT" => "#ELEMENT_ID#/"
			]
		],
		"AUTO_SCROLL" => [
			"NAME" => Loc::getMessage("AUTO_SCROLL"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			"PARENT" => "DETAIL"
		],
		"DURATION" => [
			"NAME" => Loc::getMessage("DURATION"),
			"TYPE" => "STRING",
			"DEFAULT" => 1000,
			"PARENT" => "DETAIL"
		],
		"INTERVAL_DURATION" => [
			"NAME" => Loc::getMessage("INTERVAL_DURATION"),
			"TYPE" => "STRING",
			"DEFAULT" => 2000,
			"PARENT" => "DETAIL"
		]
	]
];


if ($arCurrentValues["USE_FILTER"] == "Y")
{
	$arFilterFields = array_diff_key(
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
	$arComponentParameters["PARAMETERS"]["COORDINATES_DELIMITER"] = [
		"PARENT" => "BASE",
		"NAME" => Loc::getMessage("COORDINATES_DELIMITER"),
		"TYPE" => "STRING",
		"DEFAULT" => "_"
	];
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