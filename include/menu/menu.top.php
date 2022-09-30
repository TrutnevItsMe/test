<?global $arTheme;?>
<?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"top1", 
	array(
		"ALLOW_MULTI_SELECT" => "Y",
		"CHILD_MENU_TYPE" => "left",
		"COMPONENT_TEMPLATE" => "top1",
		"COUNT_ITEM" => "6",
		"DELAY" => "N",
		"MAX_LEVEL" => "1",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MENU_CACHE_TIME" => "3600000",
		"MENU_CACHE_TYPE" => "A",
		"MENU_CACHE_USE_GROUPS" => "N",
		"CACHE_SELECTED_ITEMS" => "N",
		"ROOT_MENU_TYPE" => "top_content_multilevel",
		"USE_EXT" => "Y"
	),
	false
);?>