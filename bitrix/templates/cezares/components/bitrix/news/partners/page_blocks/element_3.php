<?global $arTheme, $arRegion;?>
<?$APPLICATION->IncludeComponent(
	"bitrix:news.detail",
	"partners",
	Array(
		"DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
		"DISPLAY_NAME" => $arParams["DISPLAY_NAME"],
		"SHOW_GALLERY" => $arParams["SHOW_GALLERY"],
		"T_GALLERY" => $arParams["T_GALLERY"],
		"DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
		"DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"FIELD_CODE" => $arParams["DETAIL_FIELD_CODE"],
		"PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
		"DETAIL_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
		"SECTION_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"META_KEYWORDS" => $arParams["META_KEYWORDS"],
		"META_DESCRIPTION" => $arParams["META_DESCRIPTION"],
		"BROWSER_TITLE" => $arParams["BROWSER_TITLE"],
		"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
		"SET_CANONICAL_URL" => $arParams["DETAIL_SET_CANONICAL_URL"],
		"SET_TITLE" => $arParams["SET_TITLE"],
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
		"ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
		"ADD_ELEMENT_CHAIN" => $arParams["ADD_ELEMENT_CHAIN"],
		"ACTIVE_DATE_FORMAT" => $arParams["DETAIL_ACTIVE_DATE_FORMAT"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
		"GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
		"DISPLAY_TOP_PAGER" => $arParams["DETAIL_DISPLAY_TOP_PAGER"],
		"DISPLAY_BOTTOM_PAGER" => $arParams["DETAIL_DISPLAY_BOTTOM_PAGER"],
		"PAGER_TITLE" => $arParams["DETAIL_PAGER_TITLE"],
		"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => $arParams["DETAIL_PAGER_TEMPLATE"],
		"PAGER_SHOW_ALL" => $arParams["DETAIL_PAGER_SHOW_ALL"],
		"CHECK_DATES" => $arParams["CHECK_DATES"],
		"ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
		"ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
		"IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
		"USE_SHARE" 			=> $arParams["USE_SHARE"],
		"SHARE_HIDE" 			=> $arParams["SHARE_HIDE"],
		"SHARE_TEMPLATE" 		=> $arParams["SHARE_TEMPLATE"],
		"SHARE_HANDLERS" 		=> $arParams["SHARE_HANDLERS"],
		"SHARE_SHORTEN_URL_LOGIN"	=> $arParams["SHARE_SHORTEN_URL_LOGIN"],
		"SHARE_SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
	),
	$component
);?>

<? // link goods?>
<?if($arParams["SHOW_LINKED_PRODUCTS"] == "Y" && strlen($arParams["LINKED_PRODUCTS_PROPERTY"])):?>
	<?
	$list_view = ($arParams['LIST_VIEW'] ? $arParams['LIST_VIEW'] : 'slider');

	$catalogIBlockID = ($arParams["IBLOCK_CATALOG_ID"] ? $arParams["IBLOCK_CATALOG_ID"] : $arTheme["CATALOG_IBLOCK_ID"]["VALUE"]);

	$arItemsFilter = array("IBLOCK_ID" => $catalogIBlockID, "ACTIVE"=>"Y", "PROPERTY_".$arParams["LINKED_PRODUCTS_PROPERTY"] => $arElement["ID"], 'SECTION_GLOBAL_ACTIVE' => 'Y');
	CNext::makeElementFilterInRegion($arItemsFilter);
	$arItems = CNextCache::CIBLockElement_GetList(array('CACHE' => array("MULTI" =>"Y", "TAG" => CNextCache::GetIBlockCacheTag($arTheme["CATALOG_IBLOCK_ID"]["VALUE"]))), $arItemsFilter, false, false, array("ID", "IBLOCK_ID", "IBLOCK_SECTION_ID"));

	if($arItems)
	{
		$arSectionsID = array();
		foreach($arItems as $arItem)
		{
			if($arItem["IBLOCK_SECTION_ID"])
			{
				if(is_array($arItem["IBLOCK_SECTION_ID"]))
					$arSectionsID = array_merge($arSectionsID, $arItem["IBLOCK_SECTION_ID"]);
				else
					$arSectionsID[] = $arItem["IBLOCK_SECTION_ID"];
			}
		}

		if($arSectionsID){
			$arSectionsID = array_unique($arSectionsID);
		}

		if($arSectionsID):?>
			<div class="wraps goods-block with-padding">
				<h5><?=str_replace("#BRAND_NAME#", $arElement["NAME"], (strlen($arParams['T_GOODS_SECTION']) ? $arParams['T_GOODS_SECTION'] : GetMessage('T_GOODS_SECTION')))?></h5>
				<?$GLOBALS["arBrandSections"] = array("ID" => $arSectionsID);?>
				<?$APPLICATION->IncludeComponent(
					"aspro:catalog.section.list.next",
					"front_sections_only",
					array(
						"IBLOCK_TYPE" => "aspro_next_catalog",
						"IBLOCK_ID" => $arTheme["CATALOG_IBLOCK_ID"]["VALUE"],
						"CACHE_TYPE" => "A",
						"CACHE_TIME" => "36000000",
						"CACHE_FILTER" => "Y",
						"CACHE_GROUPS" => "N",
						"COUNT_ELEMENTS" => "N",
						"FILTER_NAME" => "arBrandSections",
						"TOP_DEPTH" => $arParams["DEPTH_LEVEL_BRAND"],
						"SECTION_URL" => "",
						"VIEW_MODE" => "",
						"SHOW_PARENT_NAME" => "N",
						"HIDE_SECTION_NAME" => "N",
						"ADD_SECTIONS_CHAIN" => "N",
						"USE_FILTER_SECTION" => "Y",
						"BRAND_NAME" => $arElement["NAME"],
						"BRAND_CODE" => $arElement["CODE"],
						"COMPACT_VIEW_MOBILE" => $arTheme["MOBILE_CATALOG_LIST_SECTIONS_COMPACT"]["VALUE"],
						"SHOW_SECTIONS_LIST_PREVIEW" => "N",
						"SECTIONS_LIST_PREVIEW_PROPERTY" => "N",
						"SECTIONS_LIST_PREVIEW_DESCRIPTION" => "N",
						"SHOW_SECTION_LIST_PICTURES" => "N",
						"DISPLAY_PANEL" => "N",
						"COMPONENT_TEMPLATE" => "front_sections_theme",
						"SECTION_ID" => "",
						"SECTION_CODE" => "",
						"SECTION_FIELDS" => array(
							0 => "",
							1 => "",
						),
						"SECTION_USER_FIELDS" => array(
							0 => "",
							1 => "",
						),
					),
					false
				);?>
			</div>
		<?endif;?>
		<div class="wraps goods-block with-padding block ajax_load catalog">
			<?$bAjax = ((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest")  && (isset($_GET["ajax_get"]) && $_GET["ajax_get"] == "Y"));?>
			<?if($bAjax):?>
				<?$APPLICATION->RestartBuffer();?>
			<?endif;?>
			<?
			$GLOBALS['arrProductsFilter'] = array(
				"PROPERTY_".$arParams["LINKED_PRODUCTS_PROPERTY"] => $arElement["ID"],
				'SECTION_GLOBAL_ACTIVE' => 'Y',
			);

			if($arRegion)
			{
				if($arRegion['LIST_PRICES'])
				{
					if(reset($arRegion['LIST_PRICES']) != 'component')
						$arParams['PRICE_CODE'] = array_keys($arRegion['LIST_PRICES']);
				}
				if($arRegion['LIST_STORES'])
				{
					if(reset($arRegion['LIST_STORES']) != 'component')
						$arParams['STORES'] = $arRegion['LIST_STORES'];
				}
			}

			if($arParams['LIST_PRICES'])
			{
				foreach($arParams['LIST_PRICES'] as $key => $price)
				{
					if(!$price)
						unset($arParams['LIST_PRICES'][$key]);
				}
			}

			if($arParams['STORES'])
			{
				foreach($arParams['STORES'] as $key => $store)
				{
					if(!$store)
						unset($arParams['STORES'][$key]);
				}
			}

			if($arRegion)
			{
				if($arRegion["LIST_STORES"] && $arParams["HIDE_NOT_AVAILABLE"] == "Y")
				{
					if($arParams['STORES']){
						if(CNext::checkVersionModule('18.6.200', 'iblock')){
							$arStoresFilter = array(
								'STORE_NUMBER' => $arParams['STORES'],
								'>STORE_AMOUNT' => 0,
							);
						}
						else{
							if(count($arParams['STORES']) > 1){
								$arStoresFilter = array('LOGIC' => 'OR');
								foreach($arParams['STORES'] as $storeID)
								{
									$arStoresFilter[] = array(">CATALOG_STORE_AMOUNT_".$storeID => 0);
								}
							}
							else{
								foreach($arParams['STORES'] as $storeID)
								{
									$arStoresFilter = array(">CATALOG_STORE_AMOUNT_".$storeID => 0);
								}
							}
						}

						$arTmpFilter = array('!TYPE' => '2');
						if($arStoresFilter){
							if(!CNext::checkVersionModule('18.6.200', 'iblock') && count($arStoresFilter) > 1){
								$arTmpFilter[] = $arStoresFilter;
							}
							else{
								$arTmpFilter = array_merge($arTmpFilter, $arStoresFilter);
							}

							$GLOBALS['arrProductsFilter'][] = array(
								'LOGIC' => 'OR',
								array('TYPE' => '2'),
								$arTmpFilter,
							);
						}
					}
				}
			}
			?>
			<?$APPLICATION->IncludeComponent(
				"bitrix:main.include",
				"main",
				array(
					"COMPONENT_TEMPLATE" => "main",
					"PATH" => SITE_DIR."include/news.detail.products_".$list_view.".php",
					"AREA_FILE_SHOW" => "file",
					"AREA_FILE_SUFFIX" => "",
					"AREA_FILE_RECURSIVE" => "Y",
					"EDIT_TEMPLATE" => "standard.php",
					"PRICE_CODE" => $arParams["PRICE_CODE"],
					"STORES" => $arParams["STORES"],
					"BIG_DATA_RCM_TYPE" => "bestsell",
					"STIKERS_PROP" => "HIT",
					"SALE_STIKER" => "SALE_TEXT",
					"FROM_AJAX" => ($bAjax ? "Y" : "N"),
					"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
					"LINKED_ELEMENST_PAGE_COUNT" => $arParams["LINKED_ELEMENST_PAGE_COUNT"],
					"LINKED_ELEMENST_PAGINATION" => $arParams["LINKED_ELEMENST_PAGINATION"],
					"SHOW_DISCOUNT_PERCENT_NUMBER" => $arParams["SHOW_DISCOUNT_PERCENT_NUMBER"],
					"TITLE" => str_replace("#BRAND_NAME#", $arElement["NAME"], (strlen($arParams['T_GOODS']) ? $arParams['T_GOODS'] : GetMessage('T_GOODS')))
				),
				false
			);?>
			<?if($bAjax):?>
				<?die();?>
			<?endif;?>
		</div>
	<?}?>
<?endif;?>