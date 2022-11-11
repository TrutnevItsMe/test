<?
//$GLOBALS["page_css"] = 'Catalog';
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Акции");
?>

<div class="left_block filter_ajax filter_visible">

<?$arParams['USE_FILTER']="Y";?>


<?if('Y' == $arParams['USE_FILTER']):?>
	<?
	if($arTheme["FILTER_VIEW"]["VALUE"] == 'COMPACT'){
		if($arParams["AJAX_FILTER_CATALOG"]=="Y"){
			$template = 'main_compact_ajax';
		}
		else{
			$template = 'main_compact';
		}
	}
	elseif($arParams["AJAX_FILTER_CATALOG"]=="Y"){
		$template = 'main_ajax';
	}
	else{
		$template = 'main';
	}

	global $arrFilter;
	$arrFilter["PROPERTY"] = ["HIT" => PROP_HIT_SALE_VALUE_ID];

	?>
	<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.smart.filter", 
	"main", 
	array(
		"IBLOCK_TYPE" => "aspro_next_catalog",
		"IBLOCK_ID" => "17",
		"AJAX_FILTER_FLAG" => $isAjaxFilter,
		"SECTION_ID" => "",
		"FILTER_NAME" => "arrFilter",
		"PRICE_CODE" => array(
		),
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"FILL_COMPACT_FILTER" => $arParams["FILL_COMPACT_FILTER"],
		"CACHE_NOTES" => "",
		"CACHE_GROUPS" => "N",
		"SAVE_IN_SESSION" => "N",
		"XML_EXPORT" => "Y",
		"SECTION_TITLE" => "NAME",
		"SECTION_DESCRIPTION" => "DESCRIPTION",
		"SHOW_HINTS" => $arParams["SHOW_HINTS"],
		"CONVERT_CURRENCY" => "N",
		"CURRENCY_ID" => $arParams["CURRENCY_ID"],
		"DISPLAY_ELEMENT_COUNT" => $arParams["DISPLAY_ELEMENT_COUNT"],
		"INSTANT_RELOAD" => "Y",
		"VIEW_MODE" => strtolower($arTheme["FILTER_VIEW"]["VALUE"]),
		"SEF_MODE" => "N",
		"SEF_RULE" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["smart_filter"],
		"SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
		"HIDE_NOT_AVAILABLE" => "N",
		"SORT_BUTTONS" => $arParams["SORT_BUTTONS"],
		"SORT_PRICES" => $arParams["SORT_PRICES"],
		"AVAILABLE_SORT" => $arAvailableSort,
		"SORT" => $sort,
		"SORT_ORDER" => $sort_order,
		"COMPONENT_TEMPLATE" => "main",
		"SECTION_CODE" => "",
		"PREFILTER_NAME" => "smartPreFilter",
		"PAGER_PARAMS_NAME" => "arrPager"
	),
	$component
);
	?>
<?endif;?>


</div>

<div class="right_block  wide_N">

<?
$isAjax="N";
if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest"  && isset($_GET["ajax_get"]) && $_GET["ajax_get"] == "Y" || (isset($_GET["ajax_basket"]) && $_GET["ajax_basket"]=="Y"))
{
    $isAjax="Y";
    $APPLICATION->RestartBuffer();
}
?>
<?if($isAjax === 'N'):?>
	<div class="ajax_load block">
<?endif;?>

<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section.filtred", 
	"catalog_block", 
	array(
		"IBLOCK_TYPE" => "aspro_next_catalog",
		"IBLOCK_ID" => "17",
		"SECTION_ID" => $_REQUEST["SECTION_ID"],
		"SECTION_CODE" => "",
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"AJAX_REQUEST" => $isAjax,
		"ELEMENT_SORT_FIELD" => "name",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_FIELD2" => "sort",
		"ELEMENT_SORT_ORDER2" => "desc",
		"FILTER_NAME" => "arrFilter",
		"INCLUDE_SUBSECTIONS" => "Y",
		"SHOW_ALL_WO_SECTION" => "Y",
		"HIDE_NOT_AVAILABLE" => "N",
		"PAGE_ELEMENT_COUNT" => "20",
		"LINE_ELEMENT_COUNT" => "4",
		"PROPERTY_CODE" => array(
			0 => "CML2_ARTICLE",
			1 => "",
		),
		"OFFERS_LIMIT" => "5",
		"TEMPLATE_THEME" => "red",
		"ADD_PICT_PROP" => "-",
		"LABEL_PROP" => "-",
		"PRODUCT_SUBSCRIPTION" => "N",
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"SHOW_OLD_PRICE" => "Y",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Цена скрыта",
		"SECTION_URL" => "",
		"DETAIL_URL" => "",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y",
		"SET_META_KEYWORDS" => "Y",
		"META_KEYWORDS" => "-",
		"SET_META_DESCRIPTION" => "Y",
		"META_DESCRIPTION" => "-",
		"BROWSER_TITLE" => "-",
		"ADD_SECTIONS_CHAIN" => "N",
		"DISPLAY_COMPARE" => "N",
		"SET_TITLE" => "Y",
		"SET_STATUS_404" => "N",
		"CACHE_FILTER" => "N",
		"PRICE_CODE" => array(
			0 => "РРЦ Константа",
			1 => "РРЦ 2022",
		),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"CONVERT_CURRENCY" => "N",
		"BASKET_URL" => "/personal/cart/",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"USE_PRODUCT_QUANTITY" => "N",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRODUCT_PROPERTIES" => array(
		),
		"PAGER_TEMPLATE" => "main",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Товары",
		"PAGER_SHOW_ALWAYS" => "Y",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"COMPONENT_TEMPLATE" => "catalog_block",
		"OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_ORDER" => "asc",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER2" => "desc",
		"PRODUCT_DISPLAY_MODE" => "N",
		"OFFERS_CART_PROPERTIES" => array(
		)
	),
	false
);?>
<?if($isAjax === 'N'):?>
	</div>
<?endif;?>
</div>

 <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>