<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Каталог");
$APPLICATION->IncludeComponent("bart:stopsovetnik", "", array(), false);

$APPLICATION->IncludeComponent(
	"bitrix:catalog", 
	"main", 
	array(
		"IBLOCK_TYPE" => "aspro_next_catalog",
		"IBLOCK_ID" => "17",
		"HIDE_NOT_AVAILABLE" => "N",
		"BASKET_URL" => "/basket/",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => "/catalog/",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600000",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "Y",
		"SET_TITLE" => "Y",
		"SET_STATUS_404" => "Y",
		"USE_ELEMENT_COUNTER" => "Y",
		"USE_FILTER" => "Y",
		"FILTER_NAME" => "NEXT_SMART_FILTER",
		"FILTER_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_PROPERTY_CODE" => array(
			0 => "TSVET_PROFILYA",
			1 => "CML2_ARTICLE",
			2 => "IN_STOCK",
			3 => "TSVET_KORPUSA",
			4 => "",
		),
		"FILTER_PRICE_CODE" => array(
		),
		"FILTER_OFFERS_FIELD_CODE" => array(
			0 => "NAME",
			1 => "",
		),
		"FILTER_OFFERS_PROPERTY_CODE" => array(
			0 => "TSVET",
			1 => "SHIRINA_IZDELIYA_SM",
			2 => "MONTAZH",
			3 => "GLUBINA_IZDELIYA_SM",
			4 => "",
		),
		"USE_REVIEW" => "N",
		"MESSAGES_PER_PAGE" => "10",
		"USE_CAPTCHA" => "Y",
		"REVIEW_AJAX_POST" => "Y",
		"PATH_TO_SMILE" => "/bitrix/images/forum/smile/",
		"FORUM_ID" => "1",
		"URL_TEMPLATES_READ" => "",
		"SHOW_LINK_TO_FORUM" => "Y",
		"POST_FIRST_MESSAGE" => "N",
		"USE_COMPARE" => "Y",
		"COMPARE_NAME" => "CATALOG_COMPARE_LIST",
		"COMPARE_FIELD_CODE" => array(
			0 => "NAME",
			1 => "TAGS",
			2 => "SORT",
			3 => "PREVIEW_PICTURE",
			4 => "",
		),
		"COMPARE_PROPERTY_CODE" => array(
			0 => "KATEGORIYA_DLYA_SAYTA",
			1 => "STIL",
			2 => "BRAND",
			3 => "KOLLEKTSIYA",
			4 => "HIT",
			5 => "MINIMUM_PRICE",
			6 => "SHIRINA_IZDELIYA_SM",
			7 => "GLUBINA_IZDELIYA_SM",
			8 => "DLINA_IZDELIYA_SM",
			9 => "VYSOTA_IZDELIYA_SM",
			10 => "MONTAZH",
			11 => "TSVET_KORPUSA_MEBELI",
			12 => "TSVET_RAKOVINY",
			13 => "TSVET_STOLESHNITSY",
			14 => "MATERIAL_KORPUSA",
			15 => "MATERIAL_FASADA",
			16 => "MATERIAL_STOLESHNITSY",
			17 => "MATERIAL_RAKOVINY",
			18 => "FORMA_1",
			19 => "ORIENTATSIYA_DVEREY",
			20 => "TSVET",
			21 => "MATERIAL",
			22 => "INTERNET",
			23 => "OSNASHCHENIE",
			24 => "POKRYTIE_FASADA",
			25 => "SERIYA",
			26 => "GARANTIYNYY_SROK",
			27 => "K_VO_V_UPAKOVKE",
			28 => "POVERKHNOST_FASADA",
			29 => "ARTIKUL_ANALOG",
			30 => "BREND",
			31 => "KOMPANIYA",
			32 => "KOMPLEKTATSIYA_TUMBY",
			33 => "EXPANDABLES",
			34 => "CML2_ARTICLE",
			35 => "CML2_BASE_UNIT",
			36 => "IN_STOCK",
			37 => "VID_OBESPECHENIYA_SKLAD_ZAKAZ",
			38 => "KATEGORIYA",
			39 => "KOMMENTARIY",
			40 => "NOMENKLATURNAYA_GRUPPA",
			41 => "PODBORKI",
			42 => "ASSOCIATED",
			43 => "CML2_MANUFACTURER",
			44 => "RASPRODAZHA",
			45 => "CML2_TRAITS",
			46 => "CML2_TAXES",
			47 => "country",
			48 => "SALE_TEXT",
			49 => "PROP_2033",
			50 => "CML2_ATTRIBUTES",
			51 => "CML2_BAR_CODE",
			52 => "SUPID",
			53 => "AKTSIYA_RASPRODAZHA",
			54 => "KOLICHESTVO_CHASH_RAKOVINY",
			55 => "KREPYEZH_V_KOMPLEKTE",
			56 => "NAIMENOVANIE_ILI_KOD_V_BP",
			57 => "NE_YAVLYAETSYA_KOMPLEKTOM",
			58 => "NOMENKLATURA",
			59 => "KATEGORIYA_TOVARA_A_B_C_D",
			60 => "STRANA",
			61 => "TOVARNAYA_GRUPPA",
			62 => "KOLLEKTSIYA_1",
			63 => "POKRYTIE",
			64 => "SKIDKA",
			65 => "AKTSIYA",
			66 => "NAZNACHENIE",
			67 => "NAIMENOVANIE_DLYA_SAYTA",
			68 => "SHIRINA_INDIVIDUALNOY_UPAKOVKI_SM",
			69 => "VYSOTA_INDIVIDUALNOY_UPAKOVKI_SM",
			70 => "KOMPLEKT_NOZHEK",
			71 => "RASPECHATAN_SBOROCHNYY_LIST",
			72 => "EXPANDABLES_FILTER",
			73 => "LINK_SALE",
			74 => "VES_NETTO_KG",
			75 => "MAXIMUM_PRICE",
			76 => "MEKHANIZM_DOVODCHIKA",
			77 => "MONTAZH_CHASHI_",
			78 => "NOVINKA",
			79 => "OBEM_SMYVNOGO_BACHKA_LITRAKH",
			80 => "ASSOCIATED_FILTER",
			81 => "SMESITEL_V_KOMPLEKTE",
			82 => "TREBUETSYA_SISTEMA_INSTALLYATSII",
			83 => "KRYSHKA_V_KOMPLEKTE",
			84 => "MESTO_PODVODA_VODY",
			85 => "NAZHIMNOY_MEKHANIZM_OTKRYVANIYA_PUSH_TO_OPEN",
			86 => "NOVINKA_DATA",
			87 => "BELEVAYA_KORZINA",
			88 => "BYSTROSEMNAYA_KRYSHKA_SIDENE",
			89 => "RASPOLOZHENIE_PODVODA_VODY",
			90 => "GOTOVYE_OTVERSTIYA_POD_SMESITEL",
			91 => "ZERKALO_S_PODSVETKOY",
			92 => "RASPOLOZHENIE_PODVODA_VODY_1",
			93 => "REVERSIVNOE_ZERKALO",
			94 => "SLIV_PERELIV",
			95 => "DLINA_INDIVIDUALNOY_UPAKOVKI_SM",
			96 => "DONNYY_KLAPAN",
			97 => "PROIZVODITEL",
			98 => "TIP_PODSVETKI",
			99 => "DIAMETR_SLIVA_SM",
			100 => "TIP_LAMPY",
			101 => "DIAMETR_DLYA_SIFONA_NA_SLIV_SM",
			102 => "MIN_K_VO_K_ZAKAZU",
			103 => "MOSHCHNOST_LAMPY_W",
			104 => "ZAKAZNAYA_MEBEL",
			105 => "ZERKALO_S_POLOCHKOY",
			106 => "NAMECHENNYE_OTVERSTIYA_DLYA_SMESITELYA",
			107 => "OBYEM_INDIVIDUALNOY_UPAKOVKI_SM3",
			108 => "VOZMOZHNA_USTANOVKA_NAD_STIR_MASHINOY",
			109 => "MATERIAL_UPAKOVKI",
			110 => "SISTEMA_KHRANENIYA",
			111 => "KOLICHESTVO_UPAKOVOK",
			112 => "NOVINKA_MESYATS",
			113 => "BEZOBODKOVYY_UNITAZ",
			114 => "POSTAVKA_V_PALETAKH",
			115 => "VYSOTA_S_NOZHKAMI",
			116 => "MEZHOSEVOE_RASSTOYANIE_POD_KREPEZHNYE_SHPILKI_SM_D",
			117 => "VYSOTA_CHASHI_SM_BEZ_UCHETA_KRYSHKI_SIDENYA",
			118 => "DATA_SOZDANIYA",
			119 => "DLINA_CHASHI_SM",
			120 => "TIP_OTKRYVANIYA_DVEREY",
			121 => "VES_BRUTTO_KG",
			122 => "KOLICHESTVO_DVEREY",
			123 => "ORGANIZATSIYA_SMYVAYUSHCHEGO_POTOKA",
			124 => "TIP_PALLETY",
			125 => "VYSOTA_TUMBY_S_RAKOVINOY_SM",
			126 => "POKRYTIE_PROFILYA_I_PETEL",
			127 => "SIDENE_V_KOMPLEKTE",
			128 => "SISTEMA_ANTIVSPLESK",
			129 => "POLOCHKA_V_CHASHE",
			130 => "TIP_STEKLA",
			131 => "GLUBINA_INDIVIDUALNOY_UPAKOVKI_SM",
			132 => "NAPRAVLENIE_VYPUSKA",
			133 => "OTVERSTIE_POD_SMESITEL",
			134 => "PODVOD_VODY",
			135 => "AKTSII",
			136 => "METOD_USTANOVKI_SLIVNOGO_BACHKA",
			137 => "OTVERSTIE_POD_SLIV",
			138 => "VNUTRENNYAYA_KOMPLEKTATSIYA_TOVARA",
			139 => "MEKHANIZM_SLIVA",
			140 => "REZHIM_SLIVA_VODY",
			141 => "FURNITURA",
			142 => "ARTS",
			143 => "KRYSHKA_SIDENE_V_KOMPLEKTE",
			144 => "SERIYA_2",
			145 => "SERTIFIKAT_SOOTVESTVIYA",
			146 => "SERIYA_3",
			147 => "BYSTROSEMNOE_SIDENE",
			148 => "VYSOTA_TUMBY_SO_STOLESHNITSEY_SM",
			149 => "EKSPOZITSIYA",
			150 => "KRYSHKA_SIDENE_S_MIKROLIFTOM",
			151 => "RAZMER_DUSH_KABIN",
			152 => "MATERIAL_KRYSHKI_SIDENYA",
			153 => "POKRYTIE_KORPUSA",
			154 => "POVERKHNOST_KORPUSA",
			155 => "FURNITURA_KREPEZH_SIDENYA_KNOPKA",
			156 => "FUNKTSIYA_BIDE",
			157 => "ORIGINALY_DOKUMENTOV_POLUCHENY",
			158 => "UCHASTNIK_BONUSNOY_PROGRAMMY",
			159 => "OPISANIE_DLYA_TAMOZHNI_RUS",
			160 => "OPISANIE_DLYA_TAMOZHNI_ANGL",
			161 => "SBORKU_NE_UKAZYVATV_SCHF",
			162 => "SCHETBANK",
			163 => "VIDEO_YOUTUBE",
			164 => "POPUP_VIDEO",
			165 => "FORUM_MESSAGE_CNT",
			166 => "vote_count",
			167 => "rating",
			168 => "vote_sum",
			169 => "SCHETKASSA",
			170 => "FORUM_TOPIC_ID",
			171 => "SERVICES",
			172 => "OPISANIE_NA_ANGL_YAZYKE",
			173 => "STATYAPROCHIKHDOKHODOVRASKHODOV",
			174 => "DATA_PECHATI_SBOROCHNOGO_LISTA",
			175 => "STATYAPROCHIKHDOKHODOVRASKHODOVVAL",
			176 => "DLYA_OTCHETA",
			177 => "DOPUSTIMOE_OTRITSATELNOE_KOL_VO_V_EDINITSAKH_MESYA",
			178 => "IMPORTER",
			179 => "UDALIT_SHIRINA_INDIVIDUALNOY_UPAKOVKI_SM",
			180 => "GOLOVNAYA_ORGANIZATSIYA",
			181 => "UDALIT_VYSOTA_INDIVIDUALNOY_UPAKOVKI_SM",
			182 => "SERIYA_1",
			183 => "FORMA",
			184 => "MOTS",
			185 => "STAR_NOV",
			186 => "KOMMENTARIY_ADRESA_DOSTAVKI",
			187 => "VID_ZAKUPKI",
			188 => "TSVET_FASADA_MEBELI",
			189 => "EKSPOZITSIYA_NA_OTVETSTVENNOE_KHRANENIE",
			190 => "SCHETKASSA_1",
			191 => "COLOR_REF2",
			192 => "SCHETUCHETARASKHODOV",
			193 => "TEST",
			194 => "SCHETUCHETARASKHODOVNU",
			195 => "SCHETDT",
			196 => "SCHETKT",
			197 => "KOEFFITSIENT_PERESCHETA_PRI_VYGRUZKE",
			198 => "PROP_159",
			199 => "PROP_2052",
			200 => "PROP_2027",
			201 => "PROP_2053",
			202 => "PROP_2083",
			203 => "PROP_2049",
			204 => "PROP_2026",
			205 => "PROP_2044",
			206 => "PROP_162",
			207 => "PROP_2065",
			208 => "PROP_2054",
			209 => "PROP_2017",
			210 => "PROP_2055",
			211 => "PROP_2069",
			212 => "PROP_2062",
			213 => "PROP_2061",
			214 => "",
		),
		"COMPARE_OFFERS_FIELD_CODE" => array(
			0 => "NAME",
			1 => "PREVIEW_PICTURE",
			2 => "",
		),
		"COMPARE_OFFERS_PROPERTY_CODE" => array(
			0 => "",
			1 => "ARTICLE",
			2 => "VOLUME",
			3 => "SIZES",
			4 => "COLOR_REF",
			5 => "MOUNTING",
			6 => "COLOR",
			7 => "DEPTH_CM",
			8 => "WIDTH_CM",
			9 => "",
		),
		"COMPARE_ELEMENT_SORT_FIELD" => "shows",
		"COMPARE_ELEMENT_SORT_ORDER" => "asc",
		"DISPLAY_ELEMENT_SELECT_BOX" => "N",
		"PRICE_CODE" => array(
			0 => "РРЦ",
			1 => "РРЦ Константа",
		),
		"USE_PRICE_COUNT" => "Y",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"PRICE_VAT_SHOW_VALUE" => "N",
		"PRODUCT_PROPERTIES" => array(
		),
		"USE_PRODUCT_QUANTITY" => "Y",
		"CONVERT_CURRENCY" => "Y",
		"CURRENCY_ID" => "RUB",
		"OFFERS_CART_PROPERTIES" => array(
		),
		"SHOW_TOP_ELEMENTS" => "Y",
		"SECTION_COUNT_ELEMENTS" => "Y",
		"SECTION_TOP_DEPTH" => "2",
		"SECTIONS_LIST_PREVIEW_PROPERTY" => "UF_SECTION_DESCR",
		"SHOW_SECTION_LIST_PICTURES" => "Y",
		"PAGE_ELEMENT_COUNT" => "20",
		"LINE_ELEMENT_COUNT" => "4",
		"ELEMENT_SORT_FIELD" => "shows",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_FIELD2" => "shows",
		"ELEMENT_SORT_ORDER2" => "asc",
		"LIST_PROPERTY_CODE" => array(
			0 => "TSVET",
			1 => "BREND",
			2 => "CML2_ARTICLE",
			3 => "STRANA",
			4 => "PROP_159",
			5 => "PROP_2052",
			6 => "PROP_2027",
			7 => "PROP_2053",
			8 => "PROP_2083",
			9 => "PROP_2049",
			10 => "PROP_2026",
			11 => "PROP_2044",
			12 => "PROP_162",
			13 => "PROP_2065",
			14 => "PROP_2054",
			15 => "PROP_2017",
			16 => "PROP_2055",
			17 => "PROP_2069",
			18 => "PROP_2062",
			19 => "PROP_2061",
			20 => "CML2_LINK",
			21 => "",
		),
		"INCLUDE_SUBSECTIONS" => "Y",
		"LIST_META_KEYWORDS" => "-",
		"LIST_META_DESCRIPTION" => "-",
		"LIST_BROWSER_TITLE" => "-",
		"LIST_OFFERS_FIELD_CODE" => array(
			0 => "NAME",
			1 => "CML2_LINK",
			2 => "DETAIL_PAGE_URL",
			3 => "",
		),
		"LIST_OFFERS_PROPERTY_CODE" => array(
			0 => "MORE_PHOTO",
			1 => "SHIRINA_IZDELIYA_SM",
			2 => "ARTICLE",
			3 => "VOLUME",
			4 => "SIZES",
			5 => "COLOR_REF",
			6 => "MOUNTING",
			7 => "COLOR",
			8 => "DEPTH_CM",
			9 => "WIDTH_CM",
			10 => "",
		),
		"LIST_OFFERS_LIMIT" => "10",
		"SORT_BUTTONS" => array(
			0 => "POPULARITY",
			1 => "NAME",
			2 => "PRICE",
		),
		"SORT_PRICES" => "РРЦ",
		"DEFAULT_LIST_TEMPLATE" => "block",
		"SECTION_DISPLAY_PROPERTY" => "UF_SECTION_TEMPLATE",
		"LIST_DISPLAY_POPUP_IMAGE" => "Y",
		"SECTION_PREVIEW_PROPERTY" => "DESCRIPTION",
		"SHOW_SECTION_PICTURES" => "Y",
		"SHOW_SECTION_SIBLINGS" => "Y",
		"USE_DETAIL_PREDICTION" => "N",
		"DETAIL_PROPERTY_CODE" => array(
			0 => "STIL",
			1 => "BRAND",
			2 => "SHIRINA_IZDELIYA_SM",
			3 => "GLUBINA_IZDELIYA_SM",
			4 => "VYSOTA_IZDELIYA_SM",
			5 => "TSVET",
			6 => "MATERIAL",
			7 => "GARANTIYNYY_SROK",
			8 => "METOD_KREPLENIYA",
			9 => "BREND",
			10 => "MONTAZHNAYA_GLUBINA_SM",
			11 => "CML2_ARTICLE",
			12 => "CML2_BASE_UNIT",
			13 => "MONTAZHNAYA_VYSOTA_SM",
			14 => "CML2_TAXES",
			15 => "country",
			16 => "STRANA",
			17 => "NAZNACHENIE",
			18 => "NAIMENOVANIE_DLYA_SAYTA",
			19 => "REGULIRUEMOE_MEZHOSEVOE_RASSTOYANIE_POD_KREPEZHNYE",
			20 => "DIAMETR_PEREKHODNIKA_DLYA_SLIVA_SM",
			21 => "VES_NETTO_KG",
			22 => "UPRAVLENIE",
			23 => "DIAMETR_SLIVA_SM",
			24 => "VES_BRUTTO_KG",
			25 => "PODVOD_VODY",
			26 => "REZHIM_SLIVA_VODY",
			27 => "OBYEM_L",
			28 => "SCHETKASSA_1",
			29 => "SCHETUCHETARASKHODOV",
			30 => "TEST",
			31 => "SCHETUCHETARASKHODOVNU",
			32 => "SCHETDT",
			33 => "SCHETKT",
			34 => "KOEFFITSIENT_PERESCHETA_PRI_VYGRUZKE",
			35 => "QR_KOD",
			36 => "RECOMMEND",
			37 => "NEW",
			38 => "STOCK",
			39 => "VIDEO",
			40 => "GARANTIYNIY_SROK",
			41 => "KATEGORIA_DLYA_SAYTA",
			42 => "KATEGORIA_DLYA_SAYTA1",
			43 => "",
		),
		"DETAIL_META_KEYWORDS" => "-",
		"DETAIL_META_DESCRIPTION" => "-",
		"DETAIL_BROWSER_TITLE" => "-",
		"DETAIL_OFFERS_FIELD_CODE" => array(
			0 => "NAME",
			1 => "PREVIEW_PICTURE",
			2 => "DETAIL_PICTURE",
			3 => "DETAIL_PAGE_URL",
			4 => "",
		),
		"DETAIL_OFFERS_PROPERTY_CODE" => array(
			0 => "MORE_PHOTO",
			1 => "ARTICLE",
			2 => "VOLUME",
			3 => "SIZES",
			4 => "COLOR_REF",
			5 => "MOUNTING",
			6 => "COLOR",
			7 => "DEPTH_CM",
			8 => "WIDTH_CM",
			9 => "FRAROMA",
			10 => "SPORT",
			11 => "VLAGOOTVOD",
			12 => "AGE",
			13 => "RUKAV",
			14 => "KAPUSHON",
			15 => "FRCOLLECTION",
			16 => "FRLINE",
			17 => "FRFITIL",
			18 => "FRMADEIN",
			19 => "FRELITE",
			20 => "TALL",
			21 => "FRFAMILY",
			22 => "FRSOSTAVCANDLE",
			23 => "FRTYPE",
			24 => "FRFORM",
			25 => "",
		),
		"PROPERTIES_DISPLAY_LOCATION" => "DESCRIPTION",
		"SHOW_BRAND_PICTURE" => "Y",
		"SHOW_ASK_BLOCK" => "N",
		"ASK_FORM_ID" => "2",
		"SHOW_ADDITIONAL_TAB" => "N",
		"PROPERTIES_DISPLAY_TYPE" => "TABLE",
		"SHOW_KIT_PARTS" => "Y",
		"SHOW_KIT_PARTS_PRICES" => "Y",
		"LINK_IBLOCK_TYPE" => "aspro_next_content",
		"LINK_IBLOCK_ID" => "",
		"LINK_PROPERTY_SID" => "",
		"LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
		"USE_ALSO_BUY" => "Y",
		"ALSO_BUY_ELEMENT_COUNT" => "5",
		"ALSO_BUY_MIN_BUYES" => "2",
		"USE_STORE" => "Y",
		"USE_STORE_PHONE" => "Y",
		"USE_STORE_SCHEDULE" => "Y",
		"USE_MIN_AMOUNT" => "N",
		"MIN_AMOUNT" => "10",
		"STORE_PATH" => "/contacts/stores/#store_id#/",
		"MAIN_TITLE" => "Наличие на складах",
		"MAX_AMOUNT" => "20",
		"USE_ONLY_MAX_AMOUNT" => "Y",
		"OFFERS_SORT_FIELD" => "shows",
		"OFFERS_SORT_ORDER" => "asc",
		"OFFERS_SORT_FIELD2" => "shows",
		"OFFERS_SORT_ORDER2" => "asc",
		"PAGER_TEMPLATE" => "main",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Товары",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"IBLOCK_STOCK_ID" => "19",
		"SHOW_QUANTITY" => "Y",
		"SHOW_MEASURE" => "Y",
		"SHOW_QUANTITY_COUNT" => "Y",
		"USE_RATING" => "N",
		"DISPLAY_WISH_BUTTONS" => "Y",
		"DEFAULT_COUNT" => "1",
		"SHOW_HINTS" => "Y",
		"AJAX_OPTION_ADDITIONAL" => "",
		"ADD_SECTIONS_CHAIN" => "Y",
		"ADD_ELEMENT_CHAIN" => "Y",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PARTIAL_PRODUCT_PROPERTIES" => "Y",
		"DETAIL_CHECK_SECTION_ID_VARIABLE" => "N",
		"STORES" => array(
			0 => "",
			1 => "376,",
			2 => "",
		),
		"USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"FIELDS" => array(
			0 => "",
			1 => "",
		),
		"SHOW_EMPTY_STORE" => "Y",
		"SHOW_GENERAL_STORE_INFORMATION" => "N",
		"TOP_ELEMENT_COUNT" => "8",
		"TOP_LINE_ELEMENT_COUNT" => "4",
		"TOP_ELEMENT_SORT_FIELD" => "shows",
		"TOP_ELEMENT_SORT_ORDER" => "asc",
		"TOP_ELEMENT_SORT_FIELD2" => "shows",
		"TOP_ELEMENT_SORT_ORDER2" => "asc",
		"TOP_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"COMPONENT_TEMPLATE" => "main",
		"DETAIL_SET_CANONICAL_URL" => "N",
		"SHOW_DEACTIVATED" => "N",
		"TOP_OFFERS_FIELD_CODE" => array(
			0 => "ID",
			1 => "",
		),
		"TOP_OFFERS_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"TOP_OFFERS_LIMIT" => "10",
		"SECTION_TOP_BLOCK_TITLE" => "Лучшие предложения",
		"OFFER_TREE_PROPS" => array(
		),
		"USE_BIG_DATA" => "Y",
		"BIG_DATA_RCM_TYPE" => "bestsell",
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"SHOW_OLD_PRICE" => "Y",
		"VIEWED_ELEMENT_COUNT" => "20",
		"VIEWED_BLOCK_TITLE" => "Ранее вы смотрели",
		"ELEMENT_SORT_FIELD_BOX" => "name",
		"ELEMENT_SORT_ORDER_BOX" => "asc",
		"ELEMENT_SORT_FIELD_BOX2" => "id",
		"ELEMENT_SORT_ORDER_BOX2" => "desc",
		"ADD_PICT_PROP" => "MORE_PHOTO",
		"OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
		"DETAIL_ADD_DETAIL_TO_SLIDER" => "Y",
		"SKU_DETAIL_ID" => "oid",
		"USE_MAIN_ELEMENT_SECTION" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SHOW_404" => "Y",
		"MESSAGE_404" => "",
		"AJAX_FILTER_CATALOG" => "N",
		"SECTION_BACKGROUND_IMAGE" => "-",
		"DETAIL_BACKGROUND_IMAGE" => "-",
		"DISPLAY_ELEMENT_SLIDER" => "10",
		"SHOW_ONE_CLICK_BUY" => "N",
		"USE_GIFTS_DETAIL" => "Y",
		"USE_GIFTS_SECTION" => "Y",
		"USE_GIFTS_MAIN_PR_SECTION_LIST" => "Y",
		"GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => "8",
		"GIFTS_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_DETAIL_BLOCK_TITLE" => "Выберите один из подарков",
		"GIFTS_DETAIL_TEXT_LABEL_GIFT" => "Подарок",
		"GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT" => "8",
		"GIFTS_SECTION_LIST_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_SECTION_LIST_BLOCK_TITLE" => "Подарки к товарам этого раздела",
		"GIFTS_SECTION_LIST_TEXT_LABEL_GIFT" => "Подарок",
		"GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
		"GIFTS_SHOW_OLD_PRICE" => "Y",
		"GIFTS_SHOW_NAME" => "Y",
		"GIFTS_SHOW_IMAGE" => "Y",
		"GIFTS_MESS_BTN_BUY" => "Выбрать",
		"GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT" => "8",
		"GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE" => "Выберите один из товаров, чтобы получить подарок",
		"OFFER_HIDE_NAME_PROPS" => "N",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"DETAIL_SET_VIEWED_IN_COMPONENT" => "N",
		"SECTION_PREVIEW_DESCRIPTION" => "Y",
		"SECTIONS_LIST_PREVIEW_DESCRIPTION" => "Y",
		"SALE_STIKER" => "SALE_TEXT",
		"SHOW_DISCOUNT_TIME" => "Y",
		"SHOW_RATING" => "N",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"DETAIL_OFFERS_LIMIT" => "0",
		"DETAIL_EXPANDABLES_TITLE" => "Аксессуары",
		"DETAIL_ASSOCIATED_TITLE" => "Похожие товары",
		"DETAIL_PICTURE_MODE" => "MAGNIFIER",
		"SHOW_UNABLE_SKU_PROPS" => "Y",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"DETAIL_STRICT_SECTION_CHECK" => "N",
		"COMPATIBLE_MODE" => "Y",
		"TEMPLATE_THEME" => "blue",
		"LABEL_PROP" => "",
		"PRODUCT_DISPLAY_MODE" => "Y",
		"COMMON_SHOW_CLOSE_POPUP" => "N",
		"PRODUCT_SUBSCRIPTION" => "Y",
		"SHOW_MAX_QUANTITY" => "N",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_COMPARE" => "Сравнение",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"SIDEBAR_SECTION_SHOW" => "Y",
		"SIDEBAR_DETAIL_SHOW" => "N",
		"SIDEBAR_PATH" => "",
		"USE_SALE_BESTSELLERS" => "Y",
		"FILTER_VIEW_MODE" => "VERTICAL",
		"FILTER_HIDE_ON_MOBILE" => "N",
		"INSTANT_RELOAD" => "N",
		"COMPARE_POSITION_FIXED" => "Y",
		"COMPARE_POSITION" => "top left",
		"USE_RATIO_IN_RANGES" => "Y",
		"USE_COMMON_SETTINGS_BASKET_POPUP" => "N",
		"COMMON_ADD_TO_BASKET_ACTION" => "ADD",
		"TOP_ADD_TO_BASKET_ACTION" => "ADD",
		"SECTION_ADD_TO_BASKET_ACTION" => "ADD",
		"DETAIL_ADD_TO_BASKET_ACTION" => array(
			0 => "BUY",
		),
		"DETAIL_ADD_TO_BASKET_ACTION_PRIMARY" => array(
			0 => "BUY",
		),
		"TOP_PROPERTY_CODE_MOBILE" => "",
		"TOP_VIEW_MODE" => "SECTION",
		"TOP_PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
		"TOP_PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false}]",
		"TOP_ENLARGE_PRODUCT" => "STRICT",
		"TOP_SHOW_SLIDER" => "Y",
		"TOP_SLIDER_INTERVAL" => "3000",
		"TOP_SLIDER_PROGRESS" => "N",
		"SECTIONS_VIEW_MODE" => "LIST",
		"SECTIONS_SHOW_PARENT_NAME" => "Y",
		"LIST_PROPERTY_CODE_MOBILE" => "",
		"LIST_PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
		"LIST_PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false}]",
		"LIST_ENLARGE_PRODUCT" => "STRICT",
		"LIST_SHOW_SLIDER" => "Y",
		"LIST_SLIDER_INTERVAL" => "3000",
		"LIST_SLIDER_PROGRESS" => "N",
		"DETAIL_MAIN_BLOCK_PROPERTY_CODE" => "",
		"DETAIL_MAIN_BLOCK_OFFERS_PROPERTY_CODE" => "",
		"DETAIL_USE_VOTE_RATING" => "N",
		"DETAIL_USE_COMMENTS" => "N",
		"DETAIL_BRAND_USE" => "N",
		"DETAIL_DISPLAY_NAME" => "Y",
		"DETAIL_IMAGE_RESOLUTION" => "16by9",
		"DETAIL_PRODUCT_INFO_BLOCK_ORDER" => "sku,props",
		"DETAIL_PRODUCT_PAY_BLOCK_ORDER" => "rating,price,priceRanges,quantityLimit,quantity,buttons",
		"DETAIL_SHOW_SLIDER" => "N",
		"DETAIL_DETAIL_PICTURE_MODE" => array(
			0 => "POPUP",
			1 => "MAGNIFIER",
		),
		"DETAIL_DISPLAY_PREVIEW_TEXT_MODE" => "E",
		"MESS_PRICE_RANGES_TITLE" => "Цены",
		"MESS_DESCRIPTION_TAB" => "Описание",
		"MESS_PROPERTIES_TAB" => "Характеристики",
		"MESS_COMMENTS_TAB" => "Комментарии",
		"LAZY_LOAD" => "N",
		"LOAD_ON_SCROLL" => "N",
		"USE_ENHANCED_ECOMMERCE" => "N",
		"DETAIL_DOCS_PROP" => "FILES",
		"STIKERS_PROP" => "HIT",
		"USE_SHARE" => "Y",
		"TAB_OFFERS_NAME" => "",
		"TAB_DESCR_NAME" => "",
		"TAB_CHAR_NAME" => "",
		"TAB_VIDEO_NAME" => "",
		"TAB_REVIEW_NAME" => "",
		"TAB_FAQ_NAME" => "",
		"TAB_STOCK_NAME" => "",
		"TAB_DOPS_NAME" => "",
		"BLOCK_SERVICES_NAME" => "",
		"BLOCK_DOCS_NAME" => "",
		"CHEAPER_FORM_NAME" => "",
		"DIR_PARAMS" => CNext::GetDirMenuParametrs(__DIR__),
		"SHOW_CHEAPER_FORM" => "N",
		"SHOW_LANDINGS" => "Y",
		"LANDING_TITLE" => "Популярные категории",
		"LANDING_SECTION_COUNT" => "7",
		"SHOW_LANDINGS_SEARCH" => "Y",
		"LANDING_SEARCH_TITLE" => "Похожие запросы",
		"LANDING_SEARCH_COUNT" => "7",
		"SECTIONS_TYPE_VIEW" => "sections_1",
		"SECTION_ELEMENTS_TYPE_VIEW" => "list_elements_1",
		"ELEMENT_TYPE_VIEW" => "FROM_MODULE",
		"SHOW_ARTICLE_SKU" => "Y",
		"SORT_REGION_PRICE" => "РРЦ",
		"LANDING_TYPE_VIEW" => "landing_1",
		"BIGDATA_NORMAL" => "bigdata_1",
		"BIGDATA_EXT" => "bigdata_2",
		"SHOW_MEASURE_WITH_RATIO" => "N",
		"SHOW_DISCOUNT_PERCENT_NUMBER" => "N",
		"ALT_TITLE_GET" => "NORMAL",
		"SHOW_COUNTER_LIST" => "Y",
		"SHOW_DISCOUNT_TIME_EACH_SKU" => "N",
		"USER_CONSENT" => "N",
		"USER_CONSENT_ID" => "0",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "N",
		"SHOW_HOW_BUY" => "Y",
		"TITLE_HOW_BUY" => "Как купить",
		"SHOW_DELIVERY" => "Y",
		"TITLE_DELIVERY" => "Доставка",
		"SHOW_PAYMENT" => "Y",
		"TITLE_PAYMENT" => "Оплата",
		"SHOW_GARANTY" => "Y",
		"TITLE_GARANTY" => "Условия гарантии",
		"FILL_COMPACT_FILTER" => "N",
		"USE_FILTER_PRICE" => "Y",
		"DISPLAY_ELEMENT_COUNT" => "Y",
		"RESTART" => "N",
		"USE_LANGUAGE_GUESS" => "N",
		"NO_WORD_LOGIC" => "Y",
		"SECTIONS_SEARCH_COUNT" => "10",
		"SHOW_SECTION_DESC" => "Y",
		"LANDING_POSITION" => "AFTER_PRODUCTS",
		"TITLE_SLIDER" => "Рекомендуем",
		"VIEW_BLOCK_TYPE" => "N",
		"SHOW_SEND_GIFT" => "Y",
		"SEND_GIFT_FORM_NAME" => "",
		"USE_ADDITIONAL_GALLERY" => "N",
		"BLOCK_LANDINGS_NAME" => "",
		"BLOG_IBLOCK_ID" => "",
		"BLOCK_BLOG_NAME" => "",
		"RECOMEND_COUNT" => "5",
		"VISIBLE_PROP_COUNT" => "4",
		"BUNDLE_ITEMS_COUNT" => "3",
		"STORES_FILTER" => "TITLE",
		"STORES_FILTER_ORDER" => "SORT_ASC",
		"OFFER_SHOW_PREVIEW_PICTURE_PROPS" => array(
		),
		"FILE_404" => "",
		"OFFERS_FILTER_PROPS" => array(
			0 => "TSVET_PROFILYA",
			1 => "",
			2 => "SHIRINA_IZDELIYA_SM",
			3 => "POVERKHNOST_STEKLA",
			4 => "MONTAZH",
			5 => "GLUBINA_IZDELIYA_SM",
		),
		"OFFER_FILTER_REPLACED_PICTURE" => array(
			0 => "MATERIAL_STOLESHNITSY",
			1 => "TSVET_PROFILYA",
			2 => "",
		),
		"ADDING_STORE_BASKET" => array(
		),
		"SHOW_SKU_DESCRIPTION" => "N",
		"SEF_URL_TEMPLATES" => array(
			"sections" => "",
			"section" => "#SECTION_CODE_PATH#/",
			"element" => "product/#ELEMENT_ID#/",
			"compare" => "compare.php?action=#ACTION_CODE#",
			"smart_filter" => "#SECTION_CODE_PATH#/filter/#SMART_FILTER_PATH#/apply/",
		),
		"VARIABLE_ALIASES" => array(
			"compare" => array(
				"ACTION_CODE" => "action",
			),
		)
	),
	false
);

##SECTION_CODE_PATH#/#ELEMENT_ID#/
?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>