<?global $arTheme;?>
<?$bHideCatalogMenu = (isset($arParams["HIDE_CATALOG"]) && $arParams["HIDE_CATALOG"] == "Y");?>
<?if(!CNext::IsMainPage()):?>
	<?if(CNext::IsCatalogPage()):?>
		<?if(!$bHideCatalogMenu):?>
			<?$APPLICATION->IncludeComponent(
	"bitrix:menu",
	"left_front_catalog",
	Array(
		"ALLOW_MULTI_SELECT" => "N",
		"CACHE_SELECTED_ITEMS" => "N",
		"CHILD_MENU_TYPE" => "left",
		"DELAY" => "N",
		"MAX_LEVEL" => $arTheme["MAX_DEPTH_MENU"]["VALUE"],
		"MENU_CACHE_GET_VARS" => "",
		"MENU_CACHE_TIME" => "3600000",
		"MENU_CACHE_TYPE" => "A",
		"MENU_CACHE_USE_GROUPS" => "N",
		"ROOT_MENU_TYPE" => "left",
		"USE_EXT" => "Y"
	),
false,
Array(
	'ACTIVE_COMPONENT' => 'Y'
)
);?>
		<?endif;?>
	<?elseif(CNext::IsPersonalPage()):?>
		<?$APPLICATION->IncludeComponent(
	"bitrix:menu",
	"left_menu",
	Array(
		"ALLOW_MULTI_SELECT" => "N",
		"CACHE_SELECTED_ITEMS" => "N",
		"CHILD_MENU_TYPE" => "left",
		"COMPONENT_TEMPLATE" => "left_menu",
		"DELAY" => "N",
		"MAX_LEVEL" => "2",
		"MENU_CACHE_GET_VARS" => array(),
		"MENU_CACHE_TIME" => "3600000",
		"MENU_CACHE_TYPE" => "A",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"ROOT_MENU_TYPE" => "cabinet",
		"USE_EXT" => "Y"
	),
false,
Array(
	'ACTIVE_COMPONENT' => 'Y'
)
);?>
	<?else:?>
		<?$APPLICATION->IncludeComponent(
	"bitrix:menu",
	"left_menu",
	Array(
		"ALLOW_MULTI_SELECT" => "N",
		"CHILD_MENU_TYPE" => "left",
		"DELAY" => "N",
		"MAX_LEVEL" => "2",
		"MENU_CACHE_GET_VARS" => "",
		"MENU_CACHE_TIME" => "3600000",
		"MENU_CACHE_TYPE" => "A",
		"MENU_CACHE_USE_GROUPS" => "N",
		"ROOT_MENU_TYPE" => "left",
		"USE_EXT" => "Y"
	),
false,
Array(
	'ACTIVE_COMPONENT' => 'Y'
)
);?>
	<?endif;?>
<?elseif(!$bHideCatalogMenu):?>
	<?$APPLICATION->IncludeComponent(
	"bitrix:menu",
	"left_front_catalog1",
	Array(
		"ALLOW_MULTI_SELECT" => "N",
		"CACHE_SELECTED_ITEMS" => "N",
		"CHILD_MENU_TYPE" => "left",
		"DELAY" => "N",
		"MAX_LEVEL" => $arTheme["MAX_DEPTH_MENU"]["VALUE"],
		"MENU_CACHE_GET_VARS" => "",
		"MENU_CACHE_TIME" => "3600000",
		"MENU_CACHE_TYPE" => "A",
		"MENU_CACHE_USE_GROUPS" => "N",
		"ROOT_MENU_TYPE" => "left",
		"USE_EXT" => "Y"
	),
false,
Array(
	'ACTIVE_COMPONENT' => 'Y'
)
);?>
<?endif;?>