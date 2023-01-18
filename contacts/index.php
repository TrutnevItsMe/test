<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");?><?$APPLICATION->IncludeComponent(
	"intervolga:partners", 
	"", 
	array(
		"SEF_FOLDER" => "/contacts/",
		"SEF_MODE" => "Y",
		"SEF_URL_TEMPLATES" => array(
			"list" => "/contacts/",
			"detail" => "#ELEMENT_ID#/",
		)
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>