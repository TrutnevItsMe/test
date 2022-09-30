<?global $arTheme;?>
<?$bHideCatalogMenu = (isset($arParams["HIDE_CATALOG"]) && $arParams["HIDE_CATALOG"] == "Y");?>
<?if(!CNext::IsMainPage()):?>
	<?if(CNext::IsCatalogPage()):?>
		<?if(!$bHideCatalogMenu):?>
			<?$APPLICATION->IncludeComponent("bitrix:menu", "left_front_catalog1", array(
				"ROOT_MENU_TYPE" => "left",
				"MENU_CACHE_TYPE" => "A",
				"MENU_CACHE_TIME" => "3600000",
				"MENU_CACHE_USE_GROUPS" => "N",
				"CACHE_SELECTED_ITEMS" => "N",
				"MENU_CACHE_GET_VARS" => "",
				"MAX_LEVEL" => $arTheme["MAX_DEPTH_MENU"]["VALUE"],
				"CHILD_MENU_TYPE" => "left",
				"USE_EXT" => "Y",
				"DELAY" => "N",
				"ALLOW_MULTI_SELECT" => "N" ),
				false, array( "ACTIVE_COMPONENT" => "Y" )
			);?>
		<?endif;?>
	<?elseif(CNext::IsPersonalPage()):?>
		<?$APPLICATION->IncludeComponent("bitrix:menu", "left_menu", array(
			"ROOT_MENU_TYPE" => "cabinet",
			"MENU_CACHE_TYPE" => "A",
			"MENU_CACHE_TIME" => "3600000",
			"MENU_CACHE_USE_GROUPS" => "Y",
			"MENU_CACHE_GET_VARS" => "",
			"MAX_LEVEL" => "2",
			"CHILD_MENU_TYPE" => "left",
			"USE_EXT" => "Y",
			"DELAY" => "N",
			"CACHE_SELECTED_ITEMS" => "N",
			"ALLOW_MULTI_SELECT" => "N" ),
			false, array( "ACTIVE_COMPONENT" => "Y" )
		);?>
	<?else:?>
		<?$APPLICATION->IncludeComponent("bitrix:menu", "left_menu", array(
			"ROOT_MENU_TYPE" => "left",
			"MENU_CACHE_TYPE" => "A",
			"MENU_CACHE_TIME" => "3600000",
			"MENU_CACHE_USE_GROUPS" => "N",
			"MENU_CACHE_GET_VARS" => "",
			"MAX_LEVEL" => "2",
			"CHILD_MENU_TYPE" => "left",
			"USE_EXT" => "Y",
			"DELAY" => "N",
			"ALLOW_MULTI_SELECT" => "N" ),
			false, array( "ACTIVE_COMPONENT" => "Y" )
		);?>
	<?endif;?>
<?elseif(!$bHideCatalogMenu):?>
	<?$APPLICATION->IncludeComponent("bitrix:menu", "left_front_catalog1", Array(
	"ROOT_MENU_TYPE" => "left",	// Тип меню для первого уровня
		"MENU_CACHE_TYPE" => "A",	// Тип кеширования
		"MENU_CACHE_TIME" => "3600000",	// Время кеширования (сек.)
		"MENU_CACHE_USE_GROUPS" => "N",	// Учитывать права доступа
		"CACHE_SELECTED_ITEMS" => "N",
		"MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
		"MAX_LEVEL" => $arTheme["MAX_DEPTH_MENU"]["VALUE"],	// Уровень вложенности меню
		"CHILD_MENU_TYPE" => "left",	// Тип меню для остальных уровней
		"USE_EXT" => "Y",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
		"DELAY" => "N",	// Откладывать выполнение шаблона меню
		"ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
	),
	false,
	array(
	"ACTIVE_COMPONENT" => "Y"
	)
);?>
<?endif;?>