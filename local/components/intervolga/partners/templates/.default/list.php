<?php
$APPLICATION->IncludeComponent(
	"intervolga:partners.store.list",
	".default",
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"COORDINATES_DELIMITER" => "_",
		"USE_FILTER" => "Y",
		"FILTER_VALUES" => array(
			0 => "UF_GOROD",
		),
		"USE_MAP" => "Y",
		"SHOW_MAP_ZOOM" => "Y",
		"SHOW_MAP_RULER" => "Y",
		"SHOW_MAP_FULLSCREEN" => "Y",
		"USE_PAGINATION" => "Y",
		"PAGINATION_AJAX" => "Y",
		"PAGINATION_TEMPLATE" => "main",
		"PAGINATION_COUNT_ELEMENTS" => "2",
		"DISPLAY_FIELDS" => array(
			0 => "UF_METRO",
			1 => "UF_VREMYARABOTY",
			2 => "UF_GOROD",
		)
	),
	$component
);