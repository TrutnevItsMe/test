<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");?><?$APPLICATION->IncludeComponent(
	"intervolga:partners", 
	".default", 
	array(
		"SEF_FOLDER" => "/contacts/",
		"SEF_MODE" => "Y",
		"COMPONENT_TEMPLATE" => ".default",
		"SEF_URL_TEMPLATES" => array(
			"list" => "/contacts/",
			"detail" => "#ELEMENT_ID#/",
		)
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>