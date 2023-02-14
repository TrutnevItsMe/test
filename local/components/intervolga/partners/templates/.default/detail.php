<?php

$APPLICATION->IncludeComponent("intervolga:partners.detail",
	"",
	[
		"ID" => $arResult['VARIABLES']['ELEMENT_ID'],
		"DURATION" => $arParams["DURATION"],
		"INTERVAL_DURATION" => $arParams["INTERVAL_DURATION"],
		"AUTO_SCROLL" => $arParams["AUTO_SCROLL"]
	],
	$component);