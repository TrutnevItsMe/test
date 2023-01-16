<?php

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arComponentParameters = [
	"PARAMETERS" => [
		"SEF_MODE" => [
			"list" => [
				"NAME" => Loc::getMessage("SEF_LIST"),
			],
			"detail" => [
				"NAME" => Loc::getMessage("SEF_DETAIL"),
				"DEFAULT" => "#ELEMENT_ID#/"
			]
		]
	]
];