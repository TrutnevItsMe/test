<? $APPLICATION->SetPageProperty("HIDE_LEFT_BLOCK", "Y") ?><? $APPLICATION->SetPageProperty("HIDE_LEFT_BLOCK", "Y") ?>

<?
global $arTheme, $arRegion;
?>

<? $isAjax = "N"; ?>
<? if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest" && isset($_GET["ajax_get"]) && $_GET["ajax_get"] == "Y" || (isset($_GET["ajax_basket"]) && $_GET["ajax_basket"] == "Y"))
{
	$isAjax = "Y";
}
if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest" && isset($_GET["ajax_get_filter"]) && $_GET["ajax_get_filter"] == "Y")
{
	$isAjaxFilter = "Y";
}

$sectionIDRequest = (isset($_GET["section_id"]) && $_GET["section_id"] ? $_GET["section_id"] : 0);

if ($arParams["FILTER_NAME"] == '' || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"]))
{
	$arParams["FILTER_NAME"] = "arrFilter";
}

if (!in_array($arParams["LIST_OFFERS_FIELD_CODE"], "DETAIL_PAGE_URL"))
{
	$arParams["LIST_OFFERS_FIELD_CODE"][] = "DETAIL_PAGE_URL";
}

$catalogIBlockID = ($arParams["IBLOCK_CATALOG_ID"] ? $arParams["IBLOCK_CATALOG_ID"] : $arTheme["CATALOG_IBLOCK_ID"]["VALUE"]);

$filter = &$GLOBALS[$arParams["FILTER_NAME"]];
$filter["PROPERTY_" . $arParams["LINKED_PRODUCTS_PROPERTY"]] = $arElement["ID"];

$arItemsFilter = [
	"IBLOCK_ID" => $catalogIBlockID,
	"ACTIVE" => "Y",
	"PROPERTY_" . $arParams["LINKED_PRODUCTS_PROPERTY"] => $arElement["ID"], // выбираем элементы текущего бренда
	'SECTION_GLOBAL_ACTIVE' => 'Y',
];


CNext::makeElementFilterInRegion($arItemsFilter);
$arItems = CNextCache::CIBLockElement_GetList(
	[
		'CACHE' => [
			"MULTI" => "Y",
			"TAG" => CNextCache::GetIBlockCacheTag($catalogIBlockID),
		]
	],
	$arItemsFilter,
	false,
	false,
	["ID", "IBLOCK_ID", "IBLOCK_SECTION_ID"]
);

$arAllSections = $arSectionsID = $arItemsID = [];
$arParams["AJAX_FILTER_CATALOG"] = "N";


?>

<div class="right_block wide_N">
	<div class="middle">

		<?
		if ($arItems)
		{
			foreach ($arItems as $arItem)
			{
				$arItemsID[$arItem["ID"]] = $arItem["ID"];
				if ($arItem["IBLOCK_SECTION_ID"])
				{
					if (is_array($arItem["IBLOCK_SECTION_ID"]))
					{
						foreach ($arItem["IBLOCK_SECTION_ID"] as $id)
						{
							$arAllSections[$id]["COUNT"]++;
							$arAllSections[$id]["ITEMS"][$arItem["ID"]] = $arItem["ID"];
						}
					}
					else
					{
						$arAllSections[$arItem["IBLOCK_SECTION_ID"]]["COUNT"]++;
						$arAllSections[$arItem["IBLOCK_SECTION_ID"]]["ITEMS"][$arItem["ID"]] = $arItem["ID"];
					}
				}
			}

			$arSectionsID = array_keys($arAllSections);

			ob_start() ?>
			<? if (count($arAllSections) > 1): ?>
			<?
###########################bart#######################################
			$res = CIBlockSection::GetList(
				["NAME" => "ASC"],
				[
					"IBLOCK_ID" => $catalogIBlockID,
				],
				false,
				["ID", "UF_*"]
			);
			$arNotShowSections = [];

			while ($ar_result = $res->GetNext())
			{
				if ($ar_result["UF_DISABLE_GLOBAL"])
				{
					$arNotShowSections[] = $ar_result['ID'];
				}
			}

			unset($res);
###########################bart#######################################
			$ar_SELECT = [
				"ID" => $arSectionsID,
				"IBLOCK_ID" => $catalogIBlockID,
				"!ID" => $arNotShowSections
			];

			// Получаем все разделы / подразделы текущего бренда
			$arBrandSections = CNextCache::CIBlockSection_GetList(
				[
					'NAME' => 'ASC',
					'CACHE' => [
						"MULTI" => "N",
						"GROUP" => ["ID"],
						"TAG" => CNextCache::GetIBlockCacheTag($catalogIBlockID)
					]
				],
				$ar_SELECT,
				false,
				[
					"ID",
					"IBLOCK_SECTION_ID",
					"IBLOCK_ID",
					"NAME",
					'SECTION_PAGE_URL',
					"UF_CATALOG_ICON",
					"PICTURE",
				]
			);

			$arDeleteParams = ['section_id'];
			if (preg_match_all('/PAGEN_\d+/i' . BX_UTF_PCRE_MODIFIER, $_SERVER['QUERY_STRING'], $arMatches))
			{
				$arPagenParams = $arMatches[0];
				$arDeleteParams = array_merge($arDeleteParams, $arPagenParams);
			}
			?>

			<div class="top_block_filter_section toggle_menu">
				<div class="title"><a class="dark_link" title="<?= GetMessage("FILTER_ALL_SECTON"); ?>"
									  href="<?= $APPLICATION->GetCurPageParam('', $arDeleteParams) ?>"><?= GetMessage("FILTER_SECTON"); ?></a>
				</div>
				<?php $APPLICATION->IncludeComponent("bitrix:menu",
					"left_brand_catalog",
					[
						"ROOT_MENU_TYPE" => "left",
						"MENU_CACHE_TYPE" => "N",
						"MENU_CACHE_TIME" => "3600000",
						"MENU_CACHE_USE_GROUPS" => "N",
						"CACHE_SELECTED_ITEMS" => "N",
						"MENU_CACHE_GET_VARS" => "",
						"MAX_LEVEL" => $arTheme["MAX_DEPTH_MENU"]["VALUE"],
						"CHILD_MENU_TYPE" => "left",
						"USE_EXT" => "Y",
						"DELAY" => "N",
						"ALLOW_MULTI_SELECT" => "N",
						"BRAND_IBLOCK_SECTIONS" => $arBrandSections
					],
					false,
					["ACTIVE_COMPONENT" => "Y"]
				); ?>
			</div>
		<? endif; ?>
			<? $htmlSections = ob_get_clean(); ?>
			<? $APPLICATION->AddViewContent('filter_section', $htmlSections); ?>

			<?
			// sort
			ob_start();
			include_once(__DIR__ . "/../sort.php");
			$htmlSort = ob_get_clean();
			$listElementsTemplate = $template;
			?>

			<? ob_start() ?>
			<div class="visible_mobile_filter">

				<?php
				#######################bart			#######################bart
				global $arrFilter;
				$arrFilter[0] = ['!SECTION_ID' => $arNotShowSections];
				#######################bart			#######################bart

				$PREFILTER = [
					"PROPERTY_BRAND" => $arElement["ID"]
				];

				$GLOBALS["PREFILTER"] = $PREFILTER;

				$APPLICATION->IncludeComponent(
					"bitrix:catalog.smart.filter",
					($arParams["AJAX_FILTER_CATALOG"] == "Y" ? "main_ajax" : "main"),
					[
						"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
						"IBLOCK_ID" => $catalogIBlockID,
						"AJAX_FILTER_FLAG" => $isAjaxFilter,
						"SECTION_ID" => '',
						"FILTER_NAME" => $arParams["FILTER_NAME"],
						"PRICE_CODE" => ($arParams["USE_FILTER_PRICE"] == 'Y' ? $arParams["FILTER_PRICE_CODE"] : $arParams["PRICE_CODE"]),
						"CACHE_TYPE" => $arParams["CACHE_TYPE"],
						"CACHE_TIME" => $arParams["CACHE_TIME"],
						"CACHE_NOTES" => "",
						"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
						"SECTION_IDS" => ($sectionIDRequest ? [$sectionIDRequest] : $arSectionsID),
						"ELEMENT_IDS" => ($sectionIDRequest ? $arAllSections[$sectionIDRequest]["ITEMS"] : $arItemsID),
						"SAVE_IN_SESSION" => "N",
						"XML_EXPORT" => "Y",
						"SECTION_TITLE" => "NAME",
						"HIDDEN_PROP" => ["BRAND"],
						"SECTION_DESCRIPTION" => "DESCRIPTION",
						"SHOW_HINTS" => $arParams["SHOW_HINTS"],
						'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
						'CURRENCY_ID' => $arParams['CURRENCY_ID'],
						'DISPLAY_ELEMENT_COUNT' => $arParams['DISPLAY_ELEMENT_COUNT'],
						"INSTANT_RELOAD" => "Y",
						"VIEW_MODE" => strtolower($arTheme["FILTER_VIEW"]["VALUE"]),
						"SEF_MODE" => (strlen($arResult["URL_TEMPLATES"]["smart_filter"]) ? "Y" : "N"),
						"SEF_RULE" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["smart_filter"],
						"SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
						"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
						"SORT_BUTTONS" => $arParams["SORT_BUTTONS"],
						"SORT_PRICES" => $arParams["SORT_PRICES"],
						"AVAILABLE_SORT" => $arAvailableSort,
						"SORT" => $sort,
						"SORT_ORDER" => $sort_order,
						"PREFILTER_NAME" => "PREFILTER"
					],
					$component);
				?>
			</div>
			<? $html = ob_get_clean(); ?>
			<? $APPLICATION->AddViewContent('filter_content', $html); ?>
		<? } ?>
		<? if ($isAjax == "Y"): ?>
			<? $APPLICATION->RestartBuffer(); ?>
		<? endif; ?>

		<? $APPLICATION->IncludeComponent(
			"bitrix:news.detail",
			"partners",
			[
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
				"DETAIL_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["detail"],
				"SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
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
				"IBLOCK_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["news"],
				"USE_SHARE" => $arParams["USE_SHARE"],
				"SHARE_HIDE" => $arParams["SHARE_HIDE"],
				"SHARE_TEMPLATE" => $arParams["SHARE_TEMPLATE"],
				"SHARE_HANDLERS" => $arParams["SHARE_HANDLERS"],
				"SHARE_SHORTEN_URL_LOGIN" => $arParams["SHARE_SHORTEN_URL_LOGIN"],
				"SHARE_SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
				"SHOW_TOP_BANNER" => "Y",
			],
			$component
		); ?>
		<?

		if (!$sectionIDRequest and (!$_GET['set_filter']))
		{
			?>
			<? if (count($arAllSections) > 1)
		{
			?>

			<?
			###########################bart#######################################
			$res = CIBlockSection::GetList(
				["NAME" => "ASC"],
				[
					"IBLOCK_ID" => $catalogIBlockID,
				],
				false,
				["ID", "UF_*"]
			);
			$arNotShowSections = [];

			while ($ar_result = $res->GetNext())
			{
				if ($ar_result["UF_DISABLE_GLOBAL"])
				{
					$arNotShowSections[] = $ar_result['ID'];
				}
			}
			unset($res);
			###########################bart#######################################
			$ar_SELECT_1 = [
				"ID" => $arSectionsID,
				"IBLOCK_ID" => $catalogIBlockID,
				"!ID" => $arNotShowSections
			];

			$arSections_1 = CNextCache::CIBlockSection_GetList(
				[
					'NAME' => 'ASC',
					'CACHE' => [
						"MULTI" => "N",
						"GROUP" => ["ID"],
						"TAG" => CNextCache::GetIBlockCacheTag($catalogIBlockID)
					]
				],
				$ar_SELECT_1,
				false,
				[
					"ID",
					"IBLOCK_ID",
					"NAME",
					"PICTURE"
				]
			);

			$arDeleteParams = ['section_id'];
			if (preg_match_all('/PAGEN_\d+/i' . BX_UTF_PCRE_MODIFIER, $_SERVER['QUERY_STRING'], $arMatches))
			{
				$arPagenParams = $arMatches[0];
				$arDeleteParams = array_merge($arDeleteParams, $arPagenParams);
			}
			?>
			<div class="">
				<div class="title"><a class="dark_link" title="<?= GetMessage("FILTER_ALL_SECTON"); ?>"
									  href="<?= $APPLICATION->GetCurPageParam('', $arDeleteParams) ?>"><?= GetMessage("FILTER_SECTON"); ?></a>
				</div>
				<div class="">
					<?
					$cntToShow = 300;
					$cntShow = 0;
					$bCurrentShowed = false;
					$bNeedShowCurrent = in_array($sectionIDRequest, $arSectionsID);
					$arTopSections = [];

					// Получаем верхний уровень раздела для бренда
					foreach ($arBrandSections as $id => $arSection)
					{
						$list = CIBlockSection::GetNavChain($catalogIBlockID, $arSection["ID"], [], true);

						if (!$arTopSections[$list[0]["ID"]] && $list[0]["ID"])
						{
							$arTopSections[$list[0]["ID"]] = $list[0];
						}
					}

					// Выводим плитку разделов
					foreach ($arTopSections as $sId => $arSection): ?>
						<?

						// Снято с производства | комплекктующие
						if (strstr($arSection["CODE"], OUT_OF_PRODUCTION_IBLOCK_SECTION_CODE) !== false ||
							strstr($arSection["CODE"], "komplektuyushchie_dlya") !== false)
						{
							continue;
						}

						$bCurrent = $sectionIDRequest && $arSection["ID"] == $sectionIDRequest;
						$bCurrentShowed |= $bCurrent;
						$bLastToShow = $cntShow == ($cntToShow - 1);
						$bCollapsed = ($bLastToShow && $bNeedShowCurrent && !$bCurrentShowed) ? true : !$bCurrent && $cntShow >= $cntToShow;

						if (!$bCollapsed)
						{
							++$cntShow;
						}

						if (!$arSection['PICTURE'])
						{
							$arSection['PICTURE'] = SITE_TEMPLATE_PATH . "/images/no_photo_medium.png";
						}
						else
						{
							$arSection['PICTURE'] = CFile::GetPath($arSection['PICTURE']);
						}
						?>

						<div class="cat-item <?= ($bCurrent ? ' current' : '') ?><?= ($bCollapsed ? ' collapsed' : '') ?>">
							<!--noindex-->
							<a href="<?= $APPLICATION->GetCurPageParam('section_id=' . $arSection["ID"], $arDeleteParams) ?>"
							   class="dark_link">
								<span class="cat-img"><img src="<?= $arSection['PICTURE']; ?>"/></span>
								<span class="item_title"><?= $arSection['NAME'] ?></span>
							</a><!-- /noindex -->
						</div>
					<? endforeach; ?>
					<? $cntMore = count($arSections_1) - $cntShow; ?>

				</div>
			</div>
			<?
		}
		}
		else
		{
			$arFilterSection = [];

			// Выбрали фильтр на главной странице бренда
			if (!$sectionIDRequest || $sectionIDRequest == 0)
			{
				$sectionID = [];

				foreach ($arBrandSections as $brandSection)
				{
					$res = CIBlockSection::GetNavChain(
						$catalogIBlockID,
						$brandSection["ID"],
						[],
						true
					);

					$sectionID[$res[0]["ID"]] = $res[0];
				}


				$sectionID = array_column($sectionID, "ID");
				$arFilterSection = array_merge($arFilterSection, ["ID" => $sectionID]);
			}
			else
			{
				$arFilterSection = array_merge($arFilterSection, ["SECTION_ID" => $sectionIDRequest]);
			}

			// Выводим вложенные разделы
			$res = CIBlockSection::GetList(
				[],
				array_merge(
					$arFilterSection,
					[
						"IBLOCK_ID" => $catalogIBlockID,
						"ACTIVE" => "Y"
					]
				)
			);
			$subsections = [];

			while ($sec = $res->GetNext())
			{
				$subsections[] = $sec;
			}
			?>

			<div class="">
				<div class="title"><a class="dark_link" title="<?= GetMessage("FILTER_ALL_SECTON"); ?>"
									  href="<?= $APPLICATION->GetCurPageParam('', $arDeleteParams) ?>"><?= GetMessage("FILTER_SECTON"); ?></a>
				</div>
				<div class="">
					<?
					$cntToShow = 300;
					$cntShow = 0;
					?>
					<? foreach ($subsections as $sId => $arSection): ?>
						<?

						// Снято с производства | комплектующие
						if (strstr($arSection["CODE"], OUT_OF_PRODUCTION_IBLOCK_SECTION_CODE) !== false ||
							strstr($arSection["CODE"], "komplektuyushchie_dlya") !== false)
						{
							continue;
						}

						if (!$arSection['PICTURE'])
						{
							$arSection['PICTURE'] = SITE_TEMPLATE_PATH . "/images/no_photo_medium.png";
						}
						else
						{
							$arSection['PICTURE'] = CFile::GetPath($arSection['PICTURE']);
						}
						?>
						<div class="cat-item">
							<a href="<?= $APPLICATION->GetCurPageParam('section_id=' . $arSection["ID"], $arDeleteParams) ?>"
							   class="dark_link">
								<span class="cat-img"><img src="<?= $arSection['PICTURE']; ?>"/></span>
								<span class="item_title"><?= $arSection['NAME'] ?>&#160;
										<? if ($arAllSections[$arSection["ID"]]['COUNT']): ?>
											(<?= $arAllSections[$arSection["ID"]]['COUNT'] ?>)
										<? endif; ?>
									</span>
							</a>
						</div>
					<? endforeach; ?>
				</div>
			</div>
		<? } ?>
		<? if ($isAjax == "Y"): ?>
			<? die(); ?>
		<? endif; ?>
	</div>

	<div class="clearfix"></div>
	<? // Костыль, без него или съезжает левое меню, или едет верстка (почему не знаю, не разобрался)?>
	<div style="display: none;"><?= $htmlSections; ?></div>

	<?
	// Выводим список элементов, если не перешли на страницу какого-либо раздела
	if (!$sectionIDRequest)
	{
		$arBrandItemsId = [];

		foreach ($arSections_1 as $sectionId => $arSection)
		{
			if ($sectionId == OUT_OF_PRODUCTION_IBLOCK_ID)
			{
				continue;
			}

			$arBrandItemsId = array_merge($arBrandItemsId, $arAllSections[$sectionId]["ITEMS"]);
		}

		// Настраиваем фильтр
		$GLOBALS[$arParams["FILTER_NAME"]]['ID'] = $arBrandItemsId;
		$GLOBALS[$arParams["FILTER_NAME"]]['SECTION_GLOBAL_ACTIVE'] = 'Y';
	}
	else
	{
		$GLOBALS[$arParams["FILTER_NAME"]]['ID'] = [];
		$arBrandItemsId = [];
		$subsectionIds = [];

		$rsParentSection = CIBlockSection::GetByID($sectionIDRequest);
		$arParentSection = $rsParentSection->GetNext();

		$arFilter = [
			'IBLOCK_ID' => $catalogIBlockID,
			'>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'],
			'<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'],
			'>DEPTH_LEVEL' => $arParentSection['DEPTH_LEVEL']
		]; // выберет потомков без учета активности
		;
		$rsSect = CIBlockSection::GetList(
			['left_margin' => 'asc'],
			$arFilter
		);

		while ($arSect = $rsSect->GetNext())
		{
			if ($arAllSections[$arSect["ID"]]["ITEMS"])
			{
				$subsectionIds[] = $arSect["ID"];
				$arBrandItemsId = array_merge($arBrandItemsId, $arAllSections[$arSect["ID"]]["ITEMS"]);
			}
		}

		$GLOBALS[$arParams["FILTER_NAME"]]['ID'] = $arBrandItemsId;
		$GLOBALS[$arParams["FILTER_NAME"]]['SECTION_GLOBAL_ACTIVE'] = 'Y';
		$GLOBALS[$arParams["FILTER_NAME"]][1]["SECTION_ID"] = count($subsectionIds) ? $subsectionIds : [$sectionIDRequest];
	}
	?>

	<?
	if ($isAjax == "N")
	{
		$frame = new \Bitrix\Main\Page\FrameHelper("viewtype-brand-block");
		$frame->begin();
	}
	?>

	<div class="inner_wrapper">
		<? if ($arBrandItemsId): ?>
			<hr/>
			<h5><?= GetMessage("T_GOODS", ["#BRAND_NAME#" => $arElement["NAME"]]) ?></h5>
		<? endif; ?>

		<? $arTransferParams = [
			"SHOW_ABSENT" => $arParams["SHOW_ABSENT"],
			"HIDE_NOT_AVAILABLE_OFFERS" => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],
			"PRICE_CODE" => $arParams["PRICE_CODE"],
			"OFFER_TREE_PROPS" => $arParams["OFFER_TREE_PROPS"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
			"CURRENCY_ID" => $arParams["CURRENCY_ID"],
			"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
			"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
			"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
			"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
			"LIST_OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],
			"LIST_OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
			"SHOW_DISCOUNT_TIME" => $arParams["SHOW_DISCOUNT_TIME"],
			"SHOW_COUNTER_LIST" => $arParams["SHOW_COUNTER_LIST"],
			"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
			"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
			"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
			"SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],
			"SHOW_DISCOUNT_PERCENT_NUMBER" => $arParams["SHOW_DISCOUNT_PERCENT_NUMBER"],
			"USE_REGION" => ($arRegion ? "Y" : "N"),
			"STORES" => $arParams["STORES"],
			"DEFAULT_COUNT" => $arParams["DEFAULT_COUNT"],
			"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
			"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
			"PARTIAL_PRODUCT_PROPERTIES" => $arParams["PARTIAL_PRODUCT_PROPERTIES"],
			"ADD_PROPERTIES_TO_BASKET" => $arParams["ADD_PROPERTIES_TO_BASKET"],
			"SHOW_DISCOUNT_TIME_EACH_SKU" => $arParams["SHOW_DISCOUNT_TIME_EACH_SKU"],
			"SHOW_ARTICLE_SKU" => $arParams["SHOW_ARTICLE_SKU"],
			"OFFER_ADD_PICT_PROP" => $arParams["OFFER_ADD_PICT_PROP"],
			"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
			"OFFER_SHOW_PREVIEW_PICTURE_PROPS" => $arParams["OFFER_SHOW_PREVIEW_PICTURE_PROPS"],
			"MAIN_IBLOCK_ID" => $catalogIBlockID,
		]; ?>

		<div class="ajax_load <?= $display; ?> js_wrapper_items"
			 data-params='<?= str_replace('\'', '"', CUtil::PhpToJSObject($arTransferParams, false)) ?>'>
			<? if ($isAjax == "Y" && $isAjaxFilter != "Y"): ?>
				<? $APPLICATION->RestartBuffer(); ?>
			<? endif; ?>
			<? $APPLICATION->IncludeComponent(
				"bitrix:catalog.section",
				"catalog_block",
				[
					"USE_REGION" => ($arRegion ? "Y" : "N"),
					"STORES" => $arParams['STORES'],
					"SHOW_UNABLE_SKU_PROPS" => $arParams["SHOW_UNABLE_SKU_PROPS"],
					"ALT_TITLE_GET" => $arParams["ALT_TITLE_GET"],
					"SEF_URL_TEMPLATES" => $arParams["SEF_URL_TEMPLATES"],
					"IBLOCK_TYPE" => $arParams["IBLOCK_CATALOG_TYPE"],
					"IBLOCK_ID" => $catalogIBlockID,
					"SHOW_COUNTER_LIST" => "Y",
					"SECTION_ID" => '',
					"SECTION_CODE" => '',
					"AJAX_REQUEST" => (($isAjax == "Y" && $isAjaxFilter != "Y") ? "Y" : "N"),
					"ELEMENT_SORT_FIELD" => $sort,
					"ELEMENT_SORT_ORDER" => $sort_order,
					"SHOW_DISCOUNT_TIME_EACH_SKU" => $arParams["SHOW_DISCOUNT_TIME_EACH_SKU"],
					"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
					"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
					"FILTER_NAME" => $arParams["FILTER_NAME"],
					"INCLUDE_SUBSECTIONS" => "Y",
					"SHOW_ALL_WO_SECTION" => "Y",
					"PAGE_ELEMENT_COUNT" => $show,
					"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
					"DISPLAY_TYPE" => $display,
					"TYPE_SKU" => $arTheme["TYPE_SKU"]["VALUE"],
					"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CATALOG_CODE"],
					"SHOW_ARTICLE_SKU" => $arParams["SHOW_ARTICLE_SKU"],
					"SHOW_MEASURE_WITH_RATIO" => $arParams["SHOW_MEASURE_WITH_RATIO"],

					"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
					"OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
					"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
					"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
					"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
					"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
					'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],

					"OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],

					"SECTION_URL" => "",
					"DETAIL_URL" => "",
					"BASKET_URL" => $arTheme["BASKET_PAGE_URL"]["VALUE"],
					"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
					"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
					"PRODUCT_QUANTITY_VARIABLE" => "quantity",
					"PRODUCT_PROPS_VARIABLE" => "prop",
					"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
					"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
					"AJAX_MODE" => $arParams["AJAX_MODE"],
					"AJAX_OPTION_JUMP" => $arParams["AJAX_OPTION_JUMP"],
					"AJAX_OPTION_STYLE" => $arParams["AJAX_OPTION_STYLE"],
					"AJAX_OPTION_HISTORY" => $arParams["AJAX_OPTION_HISTORY"],
					"CACHE_TYPE" => $arParams["CACHE_TYPE"],
					"CACHE_TIME" => $arParams["CACHE_TIME"],
					"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
					"CACHE_FILTER" => "Y",
					"META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
					"META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
					"BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
					"ADD_SECTIONS_CHAIN" => "N",
					"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
					'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],
					"SET_TITLE" => "N",
					"SET_STATUS_404" => "N",
					"SHOW_404" => "N",
					"MESSAGE_404" => "",
					"FILE_404" => "",
					"PRICE_CODE" => [10, 11],
					"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
					"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
					"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
					"USE_PRODUCT_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"],
					"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
					"DISPLAY_TOP_PAGER" => "N",
					"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],

					"PAGER_TITLE" => $arParams["PAGER_TITLE"],
					"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
					"PAGER_TEMPLATE" => "",
					"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
					"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
					"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],

					"AJAX_OPTION_ADDITIONAL" => "",
					"ADD_CHAIN_ITEM" => "N",
					"SHOW_QUANTITY" => $arParams["SHOW_QUANTITY"],
					"SHOW_QUANTITY_COUNT" => $arParams["SHOW_QUANTITY_COUNT"],
					"SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],
					"SHOW_DISCOUNT_PERCENT_NUMBER" => $arParams["SHOW_DISCOUNT_PERCENT_NUMBER"],
					"SHOW_DISCOUNT_TIME" => $arParams["SHOW_DISCOUNT_TIME"],
					"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
					"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
					"CURRENCY_ID" => $arParams["CURRENCY_ID"],
					"USE_STORE" => $arParams["USE_STORE"],
					"MAX_AMOUNT" => $arParams["MAX_AMOUNT"],
					"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
					"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
					"USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
					"DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
					"LIST_DISPLAY_POPUP_IMAGE" => $arParams["LIST_DISPLAY_POPUP_IMAGE"],
					"DEFAULT_COUNT" => 1,
					"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
					"SHOW_HINTS" => $arParams["SHOW_HINTS"],
					"OFFER_HIDE_NAME_PROPS" => $arParams["OFFER_HIDE_NAME_PROPS"],
					"SHOW_SECTIONS_LIST_PREVIEW" => $arParams["SHOW_SECTIONS_LIST_PREVIEW"],
					"SECTIONS_LIST_PREVIEW_PROPERTY" => $arParams["SECTIONS_LIST_PREVIEW_PROPERTY"],
					"SHOW_SECTION_LIST_PICTURES" => $arParams["SHOW_SECTION_LIST_PICTURES"],
					"USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
					"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
					"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
					"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
					"SALE_STIKER" => $arParams["SALE_STIKER"],
					"STIKERS_PROP" => $arParams["STIKERS_PROP"],
					"SHOW_RATING" => ($arParams["SHOW_RATING"] ? $arParams["SHOW_RATING"] : "Y"),
					"DISPLAY_COMPARE" => ($arParams["DISPLAY_COMPARE"] ? $arParams["DISPLAY_COMPARE"] : "Y"),
					"ADD_PICT_PROP" => $arParams["ADD_PICT_PROP"],
					"OFFER_SHOW_PREVIEW_PICTURE_PROPS" => $arParams["OFFER_SHOW_PREVIEW_PICTURE_PROPS"],
				],
				$component,
				["HIDE_ICONS" => $isAjax]
			); ?>
			<? if ($isAjax == "Y" && $isAjaxFilter != "Y"): ?>
				<? die(); ?>
			<? endif; ?>
		</div>
		<? if ($isAjax != "Y"): ?>
			<? $frame->end(); ?>
		<? endif; ?>
	</div>

	<hr class="bottoms"/>
	<div class="row">
		<div class="col-md-6 share">
			<? if ($arParams["USE_SHARE"] == "Y" && $arElement): ?>
				<div class="line_block">
					<? $APPLICATION->IncludeFile(SITE_DIR . "include/share_buttons.php",
						[],
						[
							"MODE" => "html",
							"NAME" => GetMessage('CT_BCE_CATALOG_SOC_BUTTON')
						]); ?>
				</div>
			<? endif; ?>
		</div>
		<div class="col-md-6">
			<a class="back-url url-block" href="<?= $arResult['FOLDER'] . $arResult['URL_TEMPLATES']['news'] ?>"><i
						class="fa fa-angle-left"></i><span><?= GetMessage('BACK_LINK') ?></span></a>
		</div>
	</div>
	<? $bHideBackUrl = true; ?>
</div>
<div class="left_block filter_visible">
	<? $APPLICATION->ShowViewContent('filter_section'); ?>

	<? $APPLICATION->ShowViewContent('filter_content'); ?>

	<? $APPLICATION->ShowViewContent('under_sidebar_content'); ?>

	<? CNext::get_banners_position('SIDE', 'Y'); ?>
	<? $APPLICATION->IncludeComponent("bitrix:main.include", ".default",
		[
			"COMPONENT_TEMPLATE" => ".default",
			"PATH" => SITE_DIR . "include/left_block/comp_subscribe.php",
			"AREA_FILE_SHOW" => "file",
			"AREA_FILE_SUFFIX" => "",
			"AREA_FILE_RECURSIVE" => "Y",
			"EDIT_TEMPLATE" => "include_area.php"
		],
		false
	); ?>
</div>