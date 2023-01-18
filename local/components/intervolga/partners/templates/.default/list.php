<?php
$APPLICATION->IncludeComponent(
	"intervolga:partners.store.list",
	".default",
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"COORDINATES_DELIMITER" => isset($arParams["COORDINATES_DELIMITER"]) ? $arParams["COORDINATES_DELIMITER"] : "_",
		"USE_FILTER" => $arParams["USE_FILTER"],
		"FILTER_VALUES" => $arParams["FILTER_VALUES"],
		"USE_MAP" => $arParams["USE_MAP"],
		"SHOW_MAP_ZOOM" => isset($arParams["SHOW_MAP_ZOOM"]) ? $arParams["SHOW_MAP_ZOOM"] : "Y",
		"SHOW_MAP_RULER" => isset($arParams["SHOW_MAP_RULER"]) ? $arParams["SHOW_MAP_RULER"] : "Y",
		"SHOW_MAP_FULLSCREEN" => isset($arParams["SHOW_MAP_FULLSCREEN"]) ? $arParams["SHOW_MAP_FULLSCREEN"] : "Y",
		"USE_PAGINATION" => $arParams["USE_PAGINATION"],
		"PAGINATION_AJAX" => isset($arParams["PAGINATION_AJAX"]) ? $arParams["PAGINATION_AJAX"] : "Y",
		"PAGINATION_TEMPLATE" => isset($arParams["PAGINATION_TEMPLATE"]) ? $arParams["PAGINATION_TEMPLATE"] : "main",
		"PAGINATION_COUNT_ELEMENTS" => isset($arParams["PAGINATION_COUNT_ELEMENTS"]) ? $arParams["PAGINATION_COUNT_ELEMENTS"] : "6",
		"DISPLAY_FIELDS" => $arParams["DISPLAY_FIELDS"]
	),
	$component
);