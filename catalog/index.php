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
			0 => "CML2_ARTICLE",
			1 => "IN_STOCK",
			2 => "",
		),
		"FILTER_PRICE_CODE" => array(
		),
		"FILTER_OFFERS_FIELD_CODE" => array(
			0 => "NAME",
			1 => "",
		),
		"FILTER_OFFERS_PROPERTY_CODE" => array(
			0 => "GLUBINA_IZDELIYA_SM",
			1 => "MONTAZH",
			2 => "TSVET",
			3 => "SHIRINA_IZDELIYA_SM",
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
			1 => "HIT",
			2 => "STIL",
			3 => "BRAND",
			4 => "KOLLEKTSIYA",
			5 => "TSVET",
			6 => "MATERIAL",
			7 => "OBEM_SMYVNOGO_BACHKA_LITRAKH",
			8 => "MAXIMUM_PRICE",
			9 => "MINIMUM_PRICE",
			10 => "EXPANDABLES_FILTER",
			11 => "LINK_SALE",
			12 => "VES_NETTO_KG",
			13 => "MATERIAL_FASADA",
			14 => "NOVINKA",
			15 => "ORIGINALY_DOKUMENTOV_POLUCHENY",
			16 => "ASSOCIATED_FILTER",
			17 => "TREBUETSYA_SISTEMA_INSTALLYATSII",
			18 => "UCHASTNIK_BONUSNOY_PROGRAMMY",
			19 => "INTERNET",
			20 => "OPISANIE_DLYA_TAMOZHNI_RUS",
			21 => "OSNASHCHENIE",
			22 => "POKRYTIE_FASADA",
			23 => "SERIYA",
			24 => "GARANTIYNYY_SROK",
			25 => "K_VO_V_UPAKOVKE",
			26 => "OPISANIE_DLYA_TAMOZHNI_ANGL",
			27 => "POVERKHNOST_FASADA",
			28 => "ARTIKUL_ANALOG",
			29 => "BREND",
			30 => "KOMPANIYA",
			31 => "KOMPLEKTATSIYA_TUMBY",
			32 => "SBORKU_NE_UKAZYVATV_SCHF",
			33 => "SCHETBANK",
			34 => "EXPANDABLES",
			35 => "CML2_ARTICLE",
			36 => "IN_STOCK",
			37 => "VID_OBESPECHENIYA_SKLAD_ZAKAZ",
			38 => "VIDEO_YOUTUBE",
			39 => "POPUP_VIDEO",
			40 => "KATEGORIYA",
			41 => "FORUM_MESSAGE_CNT",
			42 => "vote_count",
			43 => "KOMMENTARIY",
			44 => "NOMENKLATURNAYA_GRUPPA",
			45 => "PODBORKI",
			46 => "ASSOCIATED",
			47 => "CML2_MANUFACTURER",
			48 => "RASPRODAZHA",
			49 => "rating",
			50 => "CML2_TRAITS",
			51 => "country",
			52 => "vote_sum",
			53 => "SCHETKASSA",
			54 => "SALE_TEXT",
			55 => "FORUM_TOPIC_ID",
			56 => "PROP_2033",
			57 => "SERVICES",
			58 => "CML2_ATTRIBUTES",
			59 => "TSVET_RAKOVINY",
			60 => "CML2_BAR_CODE",
			61 => "SUPID",
			62 => "AKTSIYA_RASPRODAZHA",
			63 => "KOLICHESTVO_CHASH_RAKOVINY",
			64 => "KREPYEZH_V_KOMPLEKTE",
			65 => "NAIMENOVANIE_ILI_KOD_V_BP",
			66 => "NE_YAVLYAETSYA_KOMPLEKTOM",
			67 => "NOMENKLATURA",
			68 => "KATEGORIYA_TOVARA_A_B_C_D",
			69 => "STRANA",
			70 => "TOVARNAYA_GRUPPA",
			71 => "KOLLEKTSIYA_1",
			72 => "MATERIAL_STOLESHNITSY",
			73 => "OPISANIE_NA_ANGL_YAZYKE",
			74 => "POKRYTIE",
			75 => "SKIDKA",
			76 => "AKTSIYA",
			77 => "NAZNACHENIE",
			78 => "TSVET_STOLESHNITSY",
			79 => "SHIRINA_INDIVIDUALNOY_UPAKOVKI_SM",
			80 => "DLINA_IZDELIYA_SM",
			81 => "SHIRINA_IZDELIYA_SM",
			82 => "VYSOTA_IZDELIYA_SM",
			83 => "VYSOTA_INDIVIDUALNOY_UPAKOVKI_SM",
			84 => "KOMPLEKT_NOZHEK",
			85 => "RASPECHATAN_SBOROCHNYY_LIST",
			86 => "STATYAPROCHIKHDOKHODOVRASKHODOV",
			87 => "DATA_PECHATI_SBOROCHNOGO_LISTA",
			88 => "MEKHANIZM_DOVODCHIKA",
			89 => "MONTAZH_CHASHI_",
			90 => "ORIENTATSIYA_DVEREY",
			91 => "SMESITEL_V_KOMPLEKTE",
			92 => "STATYAPROCHIKHDOKHODOVRASKHODOVVAL",
			93 => "DLYA_OTCHETA",
			94 => "KRYSHKA_V_KOMPLEKTE",
			95 => "MESTO_PODVODA_VODY",
			96 => "NAZHIMNOY_MEKHANIZM_OTKRYVANIYA_PUSH_TO_OPEN",
			97 => "NOVINKA_DATA",
			98 => "BELEVAYA_KORZINA",
			99 => "BYSTROSEMNAYA_KRYSHKA_SIDENE",
			100 => "DOPUSTIMOE_OTRITSATELNOE_KOL_VO_V_EDINITSAKH_MESYA",
			101 => "RASPOLOZHENIE_PODVODA_VODY",
			102 => "FORMA_1",
			103 => "GOTOVYE_OTVERSTIYA_POD_SMESITEL",
			104 => "ZERKALO_S_PODSVETKOY",
			105 => "RASPOLOZHENIE_PODVODA_VODY_1",
			106 => "IMPORTER",
			107 => "REVERSIVNOE_ZERKALO",
			108 => "SLIV_PERELIV",
			109 => "GLUBINA_IZDELIYA_SM",
			110 => "DLINA_INDIVIDUALNOY_UPAKOVKI_SM",
			111 => "DONNYY_KLAPAN",
			112 => "PROIZVODITEL",
			113 => "TIP_PODSVETKI",
			114 => "UDALIT_SHIRINA_INDIVIDUALNOY_UPAKOVKI_SM",
			115 => "GOLOVNAYA_ORGANIZATSIYA",
			116 => "DIAMETR_SLIVA_SM",
			117 => "TIP_LAMPY",
			118 => "UDALIT_VYSOTA_INDIVIDUALNOY_UPAKOVKI_SM",
			119 => "DIAMETR_DLYA_SIFONA_NA_SLIV_SM",
			120 => "MIN_K_VO_K_ZAKAZU",
			121 => "MOSHCHNOST_LAMPY_W",
			122 => "ZAKAZNAYA_MEBEL",
			123 => "ZERKALO_S_POLOCHKOY",
			124 => "NAMECHENNYE_OTVERSTIYA_DLYA_SMESITELYA",
			125 => "OBYEM_INDIVIDUALNOY_UPAKOVKI_SM3",
			126 => "VOZMOZHNA_USTANOVKA_NAD_STIR_MASHINOY",
			127 => "MATERIAL_UPAKOVKI",
			128 => "SERIYA_1",
			129 => "SISTEMA_KHRANENIYA",
			130 => "KOLICHESTVO_UPAKOVOK",
			131 => "MONTAZH",
			132 => "NOVINKA_MESYATS",
			133 => "BEZOBODKOVYY_UNITAZ",
			134 => "MATERIAL_RAKOVINY",
			135 => "POSTAVKA_V_PALETAKH",
			136 => "VYSOTA_S_NOZHKAMI",
			137 => "MEZHOSEVOE_RASSTOYANIE_POD_KREPEZHNYE_SHPILKI_SM_D",
			138 => "FORMA",
			139 => "VYSOTA_CHASHI_SM_BEZ_UCHETA_KRYSHKI_SIDENYA",
			140 => "MOTS",
			141 => "DATA_SOZDANIYA",
			142 => "DLINA_CHASHI_SM",
			143 => "TIP_OTKRYVANIYA_DVEREY",
			144 => "VES_BRUTTO_KG",
			145 => "KOLICHESTVO_DVEREY",
			146 => "ORGANIZATSIYA_SMYVAYUSHCHEGO_POTOKA",
			147 => "TIP_PALLETY",
			148 => "VYSOTA_TUMBY_S_RAKOVINOY_SM",
			149 => "POKRYTIE_PROFILYA_I_PETEL",
			150 => "SIDENE_V_KOMPLEKTE",
			151 => "SISTEMA_ANTIVSPLESK",
			152 => "POLOCHKA_V_CHASHE",
			153 => "TIP_STEKLA",
			154 => "GLUBINA_INDIVIDUALNOY_UPAKOVKI_SM",
			155 => "NAPRAVLENIE_VYPUSKA",
			156 => "STAR_NOV",
			157 => "OTVERSTIE_POD_SMESITEL",
			158 => "PODVOD_VODY",
			159 => "AKTSII",
			160 => "METOD_USTANOVKI_SLIVNOGO_BACHKA",
			161 => "OTVERSTIE_POD_SLIV",
			162 => "VNUTRENNYAYA_KOMPLEKTATSIYA_TOVARA",
			163 => "MEKHANIZM_SLIVA",
			164 => "KOMMENTARIY_ADRESA_DOSTAVKI",
			165 => "REZHIM_SLIVA_VODY",
			166 => "FURNITURA",
			167 => "ARTS",
			168 => "VID_ZAKUPKI",
			169 => "KRYSHKA_SIDENE_V_KOMPLEKTE",
			170 => "SERIYA_2",
			171 => "SERTIFIKAT_SOOTVESTVIYA",
			172 => "TSVET_KORPUSA_MEBELI",
			173 => "SERIYA_3",
			174 => "TSVET_FASADA_MEBELI",
			175 => "EKSPOZITSIYA_NA_OTVETSTVENNOE_KHRANENIE",
			176 => "BYSTROSEMNOE_SIDENE",
			177 => "VYSOTA_TUMBY_SO_STOLESHNITSEY_SM",
			178 => "EKSPOZITSIYA",
			179 => "KRYSHKA_SIDENE_S_MIKROLIFTOM",
			180 => "MATERIAL_KORPUSA",
			181 => "RAZMER_DUSH_KABIN",
			182 => "MATERIAL_KRYSHKI_SIDENYA",
			183 => "POKRYTIE_KORPUSA",
			184 => "POVERKHNOST_KORPUSA",
			185 => "FURNITURA_KREPEZH_SIDENYA_KNOPKA",
			186 => "FUNKTSIYA_BIDE",
			187 => "SCHETKASSA_1",
			188 => "COLOR_REF2",
			189 => "SCHETUCHETARASKHODOV",
			190 => "TEST",
			191 => "SCHETUCHETARASKHODOVNU",
			192 => "SCHETDT",
			193 => "SCHETKT",
			194 => "KOEFFITSIENT_PERESCHETA_PRI_VYGRUZKE",
			195 => "PROP_159",
			196 => "PROP_2052",
			197 => "PROP_2027",
			198 => "PROP_2053",
			199 => "PROP_2083",
			200 => "PROP_2049",
			201 => "PROP_2026",
			202 => "PROP_2044",
			203 => "PROP_162",
			204 => "PROP_2065",
			205 => "PROP_2054",
			206 => "PROP_2017",
			207 => "CML2_BASE_UNIT",
			208 => "CML2_TAXES",
			209 => "NAIMENOVANIE_DLYA_SAYTA",
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
<<<<<<< HEAD
			1 => "ARTICLE",
			2 => "VOLUME",
			3 => "SIZES",
			4 => "COLOR_REF",
			5 => "MOUNTING",
			6 => "COLOR",
			7 => "DEPTH_CM",
			8 => "WIDTH_CM",
=======
			1 => "MOUNTING",
			2 => "COLOR",
			3 => "DEPTH_CM",
			4 => "WIDTH_CM",
			5 => "ARTICLE",
			6 => "VOLUME",
			7 => "SIZES",
			8 => "COLOR_REF",
>>>>>>> e6d048cda66d7afb014a3bfef1c3f77893a72b42
			9 => "",
		),
		"COMPARE_ELEMENT_SORT_FIELD" => "shows",
		"COMPARE_ELEMENT_SORT_ORDER" => "asc",
		"DISPLAY_ELEMENT_SELECT_BOX" => "N",
		"PRICE_CODE" => array(
			0 => "РРЦ Константа",
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
			0 => "BRAND",
			1 => "TSVET",
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
<<<<<<< HEAD
			2 => "ARTICLE",
			3 => "VOLUME",
			4 => "SIZES",
			5 => "COLOR_REF",
			6 => "MOUNTING",
			7 => "COLOR",
			8 => "DEPTH_CM",
			9 => "WIDTH_CM",
=======
			2 => "MOUNTING",
			3 => "COLOR",
			4 => "DEPTH_CM",
			5 => "WIDTH_CM",
			6 => "ARTICLE",
			7 => "VOLUME",
			8 => "SIZES",
			9 => "COLOR_REF",
>>>>>>> e6d048cda66d7afb014a3bfef1c3f77893a72b42
			10 => "",
		),
		"LIST_OFFERS_LIMIT" => "10",
		"SORT_BUTTONS" => array(
			0 => "POPULARITY",
			1 => "NAME",
			2 => "PRICE",
		),
		"SORT_PRICES" => "REGION_PRICE",
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
			2 => "TSVET",
			3 => "MATERIAL",
			4 => "VES_NETTO_KG",
			5 => "UPRAVLENIE",
			6 => "GARANTIYNYY_SROK",
			7 => "METOD_KREPLENIYA",
			8 => "BREND",
			9 => "MONTAZHNAYA_GLUBINA_SM",
			10 => "CML2_ARTICLE",
			11 => "MONTAZHNAYA_VYSOTA_SM",
			12 => "country",
			13 => "STRANA",
			14 => "NAZNACHENIE",
			15 => "REGULIRUEMOE_MEZHOSEVOE_RASSTOYANIE_POD_KREPEZHNYE",
			16 => "SHIRINA_IZDELIYA_SM",
			17 => "VYSOTA_IZDELIYA_SM",
			18 => "DIAMETR_PEREKHODNIKA_DLYA_SLIVA_SM",
			19 => "GLUBINA_IZDELIYA_SM",
			20 => "DIAMETR_SLIVA_SM",
			21 => "VES_BRUTTO_KG",
			22 => "PODVOD_VODY",
			23 => "REZHIM_SLIVA_VODY",
			24 => "OBYEM_L",
			25 => "SCHETKASSA_1",
			26 => "SCHETUCHETARASKHODOV",
			27 => "TEST",
			28 => "SCHETUCHETARASKHODOVNU",
			29 => "SCHETDT",
			30 => "SCHETKT",
			31 => "KOEFFITSIENT_PERESCHETA_PRI_VYGRUZKE",
			32 => "CML2_BASE_UNIT",
			33 => "CML2_TAXES",
			34 => "QR_KOD",
			35 => "NAIMENOVANIE_DLYA_SAYTA",
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
<<<<<<< HEAD
			1 => "ARTICLE",
			2 => "VOLUME",
			3 => "SIZES",
			4 => "COLOR_REF",
			5 => "MOUNTING",
			6 => "COLOR",
			7 => "DEPTH_CM",
			8 => "WIDTH_CM",
=======
			1 => "MOUNTING",
			2 => "COLOR",
			3 => "DEPTH_CM",
			4 => "WIDTH_CM",
			5 => "ARTICLE",
			6 => "VOLUME",
			7 => "SIZES",
			8 => "COLOR_REF",
>>>>>>> e6d048cda66d7afb014a3bfef1c3f77893a72b42
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
			0 => "374",
			1 => "375",
			2 => "376",
			3 => "377",
			4 => "378",
			5 => "",
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
		"SORT_REGION_PRICE" => "РРЦ 2022",
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
			0 => "TSVET",
			1 => "SHIRINA_IZDELIYA_SM",
			2 => "MONTAZH",
			3 => "GLUBINA_IZDELIYA_SM",
		),
		"OFFER_FILTER_REPLACED_PICTURE" => array(
			0 => "TSVET",
		),
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