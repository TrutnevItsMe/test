<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("HIDETITLE", "Y");
$APPLICATION->IncludeComponent(
	"bitrix:sale.order.ajax", 
	"compact", 
	array(
		"PAY_FROM_ACCOUNT" => "N",
		"ONLY_FULL_PAY_FROM_ACCOUNT" => "N",
		"COUNT_DELIVERY_TAX" => "Y",
		"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
		"ALLOW_AUTO_REGISTER" => "N",
		"SEND_NEW_USER_NOTIFY" => "Y",
		"DELIVERY_NO_AJAX" => "Y",
		"DELIVERY_NO_SESSION" => "N",
		"TEMPLATE_LOCATION" => "popup",
		"DELIVERY_TO_PAYSYSTEM" => "d2p",
		"USE_PREPAYMENT" => "N",
		"PROP_1" => "",
		"PROP_3" => "",
		"PROP_2" => "",
		"PROP_4" => "",
		"SHOW_STORES_IMAGES" => "Y",
		"PATH_TO_BASKET" => SITE_DIR."basket/",
		"PATH_TO_PERSONAL" => SITE_DIR."personal/",
		"PATH_TO_PAYMENT" => SITE_DIR."order/payment/",
		"PATH_TO_AUTH" => SITE_DIR."auth/",
		"SET_TITLE" => "Y",
		"PRODUCT_COLUMNS" => "",
		"DISABLE_BASKET_REDIRECT" => "N",
		"DISPLAY_IMG_WIDTH" => "90",
		"DISPLAY_IMG_HEIGHT" => "90",
		"COMPONENT_TEMPLATE" => "compact",
		"ALLOW_NEW_PROFILE" => "N",
		"SHOW_PAYMENT_SERVICES_NAMES" => "Y",
		"COMPATIBLE_MODE" => "Y",
		"BASKET_IMAGES_SCALING" => "adaptive",
		"ALLOW_USER_PROFILES" => "Y",
		"TEMPLATE_THEME" => "blue",
		"SHOW_TOTAL_ORDER_BUTTON" => "Y",
		"SHOW_PAY_SYSTEM_LIST_NAMES" => "Y",
		"SHOW_PAY_SYSTEM_INFO_NAME" => "Y",
		"SHOW_DELIVERY_LIST_NAMES" => "Y",
		"SHOW_DELIVERY_INFO_NAME" => "Y",
		"SHOW_DELIVERY_PARENT_NAMES" => "Y",
		"BASKET_POSITION" => "after",
		"SHOW_BASKET_HEADERS" => "Y",
		"DELIVERY_FADE_EXTRA_SERVICES" => "Y",
		"SHOW_COUPONS_BASKET" => "Y",
		"SHOW_COUPONS_DELIVERY" => "Y",
		"SHOW_COUPONS_PAY_SYSTEM" => "Y",
		"SHOW_NEAREST_PICKUP" => "Y",
		"DELIVERIES_PER_PAGE" => "8",
		"PAY_SYSTEMS_PER_PAGE" => "8",
		"PICKUPS_PER_PAGE" => "5",
		"SHOW_MAP_IN_PROPS" => "Y",
		"SHOW_MAP_FOR_DELIVERIES" => array(
		),
		"PROPS_FADE_LIST_1" => array(
			0 => "1",
			1 => "2",
			2 => "3",
			3 => "4",
			4 => "7",
		),
		"PROPS_FADE_LIST_2" => "",
		"PRODUCT_COLUMNS_VISIBLE" => array(
			0 => "PROPS",
			1 => "NOTES",
			2 => "DISCOUNT_PRICE_PERCENT_FORMATED",
			3 => "PRICE_FORMATED",
			4 => "PROPERTY_CML2_ARTICLE",
		),
		"ADDITIONAL_PICT_PROP_13" => "-",
		"ADDITIONAL_PICT_PROP_14" => "-",
		"PRODUCT_COLUMNS_HIDDEN" => array(
		),
		"USE_YM_GOALS" => "N",
		"USE_CUSTOM_MAIN_MESSAGES" => "N",
		"USE_CUSTOM_ADDITIONAL_MESSAGES" => "N",
		"USE_CUSTOM_ERROR_MESSAGES" => "N",
		"SHOW_ORDER_BUTTON" => "final_step",
		"SKIP_USELESS_BLOCK" => "Y",
		"SERVICES_IMAGES_SCALING" => "standard",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"ALLOW_APPEND_ORDER" => "Y",
		"SHOW_NOT_CALCULATED_DELIVERIES" => "L",
		"SPOT_LOCATION_BY_GEOIP" => "Y",
		"SHOW_VAT_PRICE" => "Y",
		"USE_PRELOAD" => "Y",
		"SHOW_PICKUP_MAP" => "Y",
		"PICKUP_MAP_TYPE" => "yandex",
		"SHOW_COUPONS" => "N",
		"USER_CONSENT" => "Y",
		"USER_CONSENT_ID" => "0",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "N",
		"ACTION_VARIABLE" => "soa-action",
		"EMPTY_BASKET_HINT_PATH" => "/",
		"USE_PHONE_NORMALIZATION" => "Y",
		"ADDITIONAL_PICT_PROP_17" => "-",
		"ADDITIONAL_PICT_PROP_20" => "-",
		"HIDE_ORDER_DESCRIPTION" => "Y",
		"USE_ENHANCED_ECOMMERCE" => "N",
		"ADDITIONAL_PICT_PROP_26" => "-",
		"ACTIVE_SAVE_DRAFT_ORDER" => "N",
		"INDEX_PICTURE" => "10",
		"SHOW_STORE" => "Y",
		"SHOW_RESTS" => "Y",
		"ADDITIONAL_PICT_PROP_27" => "-"
	),
	false
);
require_once ($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");