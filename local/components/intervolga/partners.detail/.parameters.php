<?php

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$arComponentParameters = [
	"GROUPS" => [
		"CAROUSEL" => [
			"NAME" => Loc::getMessage("CAROUSEL_GROUP_NAME")
		]
	],
	"PARAMETERS" => [
		"ID" => [
			"NAME" => "ID",
			"TYPE" => "STRING",
			"PARENT" => "BASE"
		],
		"AUTO_SCROLL" => [
			"NAME" => Loc::getMessage("AUTO_SCROLL"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			"PARENT" => "CAROUSEL"
		],
		"DURATION" => [
			"NAME" => Loc::getMessage("DURATION"),
			"TYPE" => "STRING",
			"DEFAULT" => 1000,
			"PARENT" => "CAROUSEL"
		],
		"INTERVAL_DURATION" => [
			"NAME" => Loc::getMessage("INTERVAL_DURATION"),
			"TYPE" => "STRING",
			"DEFAULT" => 2000,
			"PARENT" => "CAROUSEL"
		]
	]
];