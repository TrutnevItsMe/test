<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");?><?$APPLICATION->IncludeComponent(
	"intervolga:partners", 
	".default", 
	array(
		"SEF_FOLDER" => "/contacts/",
		"SEF_MODE" => "Y",
		"COMPONENT_TEMPLATE" => ".default",
		"DISPLAY_FIELDS" => array(
			0 => "UF_METRO",
			1 => "UF_VREMYARABOTY",
			2 => "UF_GOROD",
			3 => "UF_TELEFON",
			4 => "UF_ADRES",
		),
		"COORDINATES_DELIMITER" => ",",
		"USE_FILTER" => "Y",
		"FILTER_VALUES" => array(
			0 => "UF_GOROD",
			1 => "UF_TIPMAGAZINA",
		),
		"USE_MAP" => "Y",
		"SHOW_MAP_ZOOM" => "Y",
		"SHOW_MAP_RULER" => "Y",
		"SHOW_MAP_FULLSCREEN" => "Y",
		"USE_PAGINATION" => "Y",
		"PAGINATION_AJAX" => "Y",
		"PAGINATION_TEMPLATE" => "main",
		"PAGINATION_COUNT_ELEMENTS" => "200",
		"AUTO_SCROLL" => "Y",
		"DURATION" => "1000",
		"INTERVAL_DURATION" => "2000",
		"CAROUSEL_COUNT_ITEMS" => "3",
		"USE_DRAG" => "Y",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"SEF_URL_TEMPLATES" => array(
			"list" => "/contacts/",
			"detail" => "#ELEMENT_ID#/",
		)
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>