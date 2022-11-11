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
			0 => "",
			1 => "GLUBINA_IZDELIYA_SM",
			2 => "MONTAZH",
			3 => "TSVET",
			4 => "SHIRINA_IZDELIYA_SM",
			5 => "",
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
			0 => "ARTICLE",
			1 => "VOLUME",
			2 => "SIZES",
			3 => "COLOR_REF",
			4 => "MOUNTING",
			5 => "COLOR",
			6 => "DEPTH_CM",
			7 => "WIDTH_CM",
			8 => "",
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
			0 => "ARTICLE",
			1 => "MORE_PHOTO",
			2 => "VOLUME",
			3 => "SIZES",
			4 => "COLOR_REF",
			5 => "SHIRINA_IZDELIYA_SM",
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
		"SORT_PRICES" => "REGION_PRICE",
		"DEFAULT_LIST_TEMPLATE" => "block",
		"SECTION_DISPLAY_PROPERTY" => "UF_SECTION_TEMPLATE",
		"LIST_DISPLAY_POPUP_IMAGE" => "Y",
		"SECTION_PREVIEW_PROPERTY" => "DESCRIPTION",
		"SHOW_SECTION_PICTURES" => "Y",
		"SHOW_SECTION_SIBLINGS" => "Y",
		"USE_DETAIL_PREDICTION" => "N",
		"DETAIL_PROPERTY_CODE" => array(
			0 => "KATEGORIYA_DLYA_SAYTA",
			1 => "STIL",
			2 => "BRAND",
			3 => "KOLLEKTSIYA",
			4 => "TSVET",
			5 => "MATERIAL",
			6 => "VES_NETTO_KG",
			7 => "MATERIAL_PODDONA",
			8 => "MATERIAL_FASADA",
			9 => "METOD_KREPLENIYA_1",
			10 => "TIP_SMYVNOY_KLAVISHI",
			11 => "UPRAVLENIE",
			12 => "KOLICHESTVO_KRYUCHKOV",
			13 => "MATERIAL_PODLOZHKI",
			14 => "MEKHANIZM",
			15 => "OBLAST_PRIMENENIYA_",
			16 => "OSNASHCHENIE",
			17 => "POKRYTIE_FASADA",
			18 => "FORMA_PODDONA",
			19 => "VODOOTTALKIVAYUSHCHEE_POKRYTIE",
			20 => "GARANTIYNYY_SROK",
			21 => "GLUBINA_PODDONA_SM",
			22 => "METOD_KREPLENIYA",
			23 => "TIP_IZLIVA",
			24 => "FAKTURA",
			25 => "ANTIBAKTERIALNOE_POKRYTIE",
			26 => "BREND",
			27 => "MONTAZHNAYA_GLUBINA_SM",
			28 => "OTVERSTIYA_DLYA_MONTAZHA",
			29 => "CML2_ARTICLE",
			30 => "MONTAZHNAYA_VYSOTA_SM",
			31 => "CML2_MANUFACTURER",
			32 => "country",
			33 => "TSVET_KORPUSA",
			34 => "TSVET_RAKOVINY",
			35 => "TSVET_STEKLA",
			36 => "SHIRINA_NEPODVIZHNOY_CHASTI_DVERI_SM",
			37 => "KOLICHESTVO_CHASH_RAKOVINY",
			38 => "KOMPLEKT_NOZHEK_DLYA_PODDONA",
			39 => "KREPYEZH_V_KOMPLEKTE",
			40 => "STANDART_PODVODKI",
			41 => "STRANA",
			42 => "SHIRINA_NEPODVIZHNOY_STENKI_SM",
			43 => "SHIRINA_NEPODVIZHNOY_CHASTI_DVERI_SM_1",
			44 => "MATERIAL_STOLESHNITSY",
			45 => "POKRYTIE",
			46 => "RAZMER_ROZETKI",
			47 => "SHIRINA_NEPODVIZHNOY_STENKI_SM_1",
			48 => "NAZNACHENIE",
			49 => "REGULIRUEMOE_MEZHOSEVOE_RASSTOYANIE_POD_KREPEZHNYE",
			50 => "TSVET_STOLESHNITSY",
			51 => "DLINA_IZDELIYA_SM",
			52 => "SHIRINA_IZDELIYA_SM",
			53 => "VYSOTA_IZDELIYA_SM",
			54 => "DIAMETR_PEREKHODNIKA_DLYA_SLIVA_SM",
			55 => "OBLAST_PRIMENENIYA",
			56 => "UVELICHENIE_ZERKALA_RAZ",
			57 => "ORIENTATSIYA_DVEREY",
			58 => "KRYSHKA_V_KOMPLEKTE",
			59 => "MESTO_PODVODA_VODY",
			60 => "BELEVAYA_KORZINA",
			61 => "BYSTROSEMNAYA_KRYSHKA_SIDENE",
			62 => "DIAPAZON_REGULIROVKI_SHIRINY_SM",
			63 => "KOLICHESTVO_KRYUCHKOV_SHT_NA_VESHALKE",
			64 => "RASPOLOZHENIE_PODVODA_VODY",
			65 => "FORMA_1",
			66 => "GOTOVYE_OTVERSTIYA_POD_SMESITEL",
			67 => "DIAPAZON_REGULIROVKI_GLUBINY_SM",
			68 => "ZERKALO_S_PODSVETKOY",
			69 => "KOLICHESTVO_OTVODOV",
			70 => "RASPOLOZHENIE_PODVODA_VODY_1",
			71 => "KONSTRUKTSIYA_DVEREY",
			72 => "PROPUSKNAYA_SPOSOBNOST_L_MIN",
			73 => "SLIV_PERELIV",
			74 => "GLUBINA_IZDELIYA_SM",
			75 => "DONNYY_KLAPAN",
			76 => "TIP_PODSVETKI",
			77 => "SHIRINA_VKHODA_SM",
			78 => "DIAMETR_SLIVA_SM",
			79 => "MATERIAL_PROFILYA",
			80 => "MOSHCHNOST_LAMPY_W",
			81 => "TIP_VYKLYUCHATELYA",
			82 => "TSVET_PROFILYA",
			83 => "ZERKALO_S_POLOCHKOY",
			84 => "TEKSTURA_STEKLA",
			85 => "TOLSHCHINA_STEKLA_IZDELIYA_MM",
			86 => "TOLSHCHINA_LISTA_MM",
			87 => "TOLSHCHINA_STEKLA_DVERNOGO_POLOTNA_MM",
			88 => "ANTISKOLZYASHCHEE_POKRYTIE",
			89 => "BEZOBODKOVYY_UNITAZ",
			90 => "KOLICHESTVO_SEKTSIY_DVEREY",
			91 => "MATERIAL_RAKOVINY",
			92 => "ANTIKALTSIEVAYA_OBRABOTKA",
			93 => "VYSOTA_S_NOZHKAMI",
			94 => "VYSOTA_CHASHI_SM_BEZ_UCHETA_KRYSHKI_SIDENYA",
			95 => "NOZHKI",
			96 => "PODDON",
			97 => "DLINA_CHASHI_SM",
			98 => "TIP_OTKRYVANIYA_DVEREY",
			99 => "VES_BRUTTO_KG",
			100 => "GARANTIYNYY_SROK_NA_REZINOTEKHNICHESKIE_IZDELIYA",
			101 => "KOLICHESTVO_DVEREY",
			102 => "ORGANIZATSIYA_SMYVAYUSHCHEGO_POTOKA",
			103 => "RASPOLOZHENIE_PERELIVA",
			104 => "VYSOTA_TUMBY_S_RAKOVINOY_SM",
			105 => "POKRYTIE_PROFILYA_I_PETEL",
			106 => "SIDENE_V_KOMPLEKTE",
			107 => "SISTEMA_ANTIVSPLESK",
			108 => "PODGOLOVNIK",
			109 => "POLOCHKA_V_CHASHE",
			110 => "TIP_STEKLA",
			111 => "KOLICHESTVO_DVEREY_1",
			112 => "OTVERSTIE_POD_SMESITEL",
			113 => "PODVOD_VODY",
			114 => "MATERIAL_RUCHEK",
			115 => "METOD_USTANOVKI_SLIVNOGO_BACHKA",
			116 => "OTVERSTIE_POD_SLIV",
			117 => "SISTEMA_GIDROMASSAZHA",
			118 => "AEROMASSAZH",
			119 => "MEKHANIZM_SLIVA",
			120 => "TSVET_RUCHEK",
			121 => "REZHIM_SLIVA_VODY",
			122 => "FURNITURA",
			123 => "KHROMOTERAPIYA",
			124 => "VYSOTA_IZLIVA",
			125 => "KRYSHKA_SIDENE_V_KOMPLEKTE",
			126 => "PODVODNAYA_PODSVETKA",
			127 => "SERTIFIKAT_SOOTVESTVIYA",
			128 => "TSVET_KORPUSA_MEBELI",
			129 => "VYSOTA_IZLIVA_1",
			130 => "TSVET_FASADA_MEBELI",
			131 => "BYSTROSEMNOE_SIDENE",
			132 => "VYSOTA_TUMBY_SO_STOLESHNITSEY_SM",
			133 => "DLINA_IZLIVA",
			134 => "MATERIAL_KORPUSA",
			135 => "OBYEM_L",
			136 => "MATERIAL_KRYSHKI_SIDENYA",
			137 => "POKRYTIE_KORPUSA",
			138 => "VYSOTA_S_OPOROY_SM",
			139 => "POVERKHNOST_KORPUSA",
			140 => "FUNKTSIYA_BIDE",
			141 => "TSVET_FURNITURY",
			142 => "OBYEM_L_1",
			143 => "AROMATERAPIYA",
			144 => "SCHETKASSA_1",
			145 => "SCHETUCHETARASKHODOV",
			146 => "TEST",
			147 => "SCHETUCHETARASKHODOVNU",
			148 => "SCHETDT",
			149 => "SCHETKT",
			150 => "KOEFFITSIENT_PERESCHETA_PRI_VYGRUZKE",
			151 => "CML2_BASE_UNIT",
			152 => "CML2_TAXES",
			153 => "QR_KOD",
			154 => "NAIMENOVANIE_DLYA_SAYTA",
			155 => "RECOMMEND",
			156 => "NEW",
			157 => "STOCK",
			158 => "VIDEO",
			159 => "GARANTIYNIY_SROK",
			160 => "KATEGORIA_DLYA_SAYTA",
			161 => "KATEGORIA_DLYA_SAYTA1",
			162 => "",
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
			0 => "ARTICLE",
			1 => "MORE_PHOTO",
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
			0 => "374",
			1 => "375",
			2 => "376",
			3 => "377",
			4 => "378",
			5 => "1887",
			6 => "1888",
			7 => "1998",
			8 => "2119",
			9 => "",
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
		),
		"OFFER_FILTER_REPLACED_PICTURE" => array(
		),
		"ADDING_STORE_BASKET" => array(
			0 => "374",
			1 => "375",
			2 => "376",
			3 => "377",
			4 => "378",
			5 => "1829",
			6 => "1830",
			7 => "1831",
			8 => "1832",
			9 => "1833",
			10 => "1834",
			11 => "1835",
			12 => "1836",
			13 => "1837",
			14 => "1838",
			15 => "1839",
			16 => "1840",
			17 => "1841",
			18 => "1842",
			19 => "1843",
			20 => "1844",
			21 => "1845",
			22 => "1846",
			23 => "1847",
			24 => "1848",
			25 => "1849",
			26 => "1850",
			27 => "1851",
			28 => "1852",
			29 => "1853",
			30 => "1854",
			31 => "1855",
			32 => "1856",
			33 => "1857",
			34 => "1858",
			35 => "1859",
			36 => "1860",
			37 => "1861",
			38 => "1862",
			39 => "1863",
			40 => "1864",
			41 => "1865",
			42 => "1866",
			43 => "1867",
			44 => "1868",
			45 => "1869",
			46 => "1870",
			47 => "1871",
			48 => "1872",
			49 => "1873",
			50 => "1874",
			51 => "1875",
			52 => "1876",
			53 => "1877",
			54 => "1878",
			55 => "1879",
			56 => "1880",
			57 => "1881",
			58 => "1882",
			59 => "1883",
			60 => "1884",
			61 => "1885",
			62 => "1886",
			63 => "1887",
			64 => "1888",
			65 => "1889",
			66 => "1890",
			67 => "1891",
			68 => "1892",
			69 => "1893",
			70 => "1894",
			71 => "1895",
			72 => "1896",
			73 => "1897",
			74 => "1898",
			75 => "1899",
			76 => "1900",
			77 => "1901",
			78 => "1902",
			79 => "1903",
			80 => "1904",
			81 => "1905",
			82 => "1906",
			83 => "1907",
			84 => "1908",
			85 => "1909",
			86 => "1910",
			87 => "1911",
			88 => "1912",
			89 => "1913",
			90 => "1914",
			91 => "1915",
			92 => "1916",
			93 => "1917",
			94 => "1918",
			95 => "1919",
			96 => "1920",
			97 => "1921",
			98 => "1922",
			99 => "1923",
			100 => "1924",
			101 => "1925",
			102 => "1926",
			103 => "1927",
			104 => "1928",
			105 => "1929",
			106 => "1930",
			107 => "1931",
			108 => "1932",
			109 => "1933",
			110 => "1934",
			111 => "1935",
			112 => "1936",
			113 => "1937",
			114 => "1938",
			115 => "1939",
			116 => "1940",
			117 => "1941",
			118 => "1942",
			119 => "1943",
			120 => "1944",
			121 => "1945",
			122 => "1946",
			123 => "1947",
			124 => "1948",
			125 => "1949",
			126 => "1950",
			127 => "1951",
			128 => "1952",
			129 => "1953",
			130 => "1954",
			131 => "1955",
			132 => "1956",
			133 => "1957",
			134 => "1958",
			135 => "1959",
			136 => "1960",
			137 => "1961",
			138 => "1962",
			139 => "1963",
			140 => "1964",
			141 => "1965",
			142 => "1966",
			143 => "1967",
			144 => "1968",
			145 => "1969",
			146 => "1970",
			147 => "1971",
			148 => "1972",
			149 => "1973",
			150 => "1974",
			151 => "1975",
			152 => "1976",
			153 => "1977",
			154 => "1978",
			155 => "1979",
			156 => "1980",
			157 => "1981",
			158 => "1982",
			159 => "1983",
			160 => "1984",
			161 => "1985",
			162 => "1986",
			163 => "1987",
			164 => "1988",
			165 => "1989",
			166 => "1990",
			167 => "1991",
			168 => "1992",
			169 => "1993",
			170 => "1994",
			171 => "1995",
			172 => "1996",
			173 => "1997",
			174 => "1998",
			175 => "1999",
			176 => "2000",
			177 => "2001",
			178 => "2002",
			179 => "2003",
			180 => "2004",
			181 => "2005",
			182 => "2006",
			183 => "2007",
			184 => "2008",
			185 => "2009",
			186 => "2010",
			187 => "2011",
			188 => "2012",
			189 => "2013",
			190 => "2014",
			191 => "2015",
			192 => "2016",
			193 => "2017",
			194 => "2018",
			195 => "2019",
			196 => "2020",
			197 => "2021",
			198 => "2022",
			199 => "2023",
			200 => "2024",
			201 => "2025",
			202 => "2026",
			203 => "2027",
			204 => "2028",
			205 => "2029",
			206 => "2030",
			207 => "2031",
			208 => "2032",
			209 => "2033",
			210 => "2034",
			211 => "2035",
			212 => "2036",
			213 => "2037",
			214 => "2038",
			215 => "2039",
			216 => "2040",
			217 => "2041",
			218 => "2042",
			219 => "2043",
			220 => "2044",
			221 => "2045",
			222 => "2046",
			223 => "2047",
			224 => "2048",
			225 => "2049",
			226 => "2050",
			227 => "2051",
			228 => "2052",
			229 => "2053",
			230 => "2054",
			231 => "2055",
			232 => "2056",
			233 => "2057",
			234 => "2058",
			235 => "2059",
			236 => "2060",
			237 => "2061",
			238 => "2062",
			239 => "2063",
			240 => "2064",
			241 => "2065",
			242 => "2066",
			243 => "2067",
			244 => "2068",
			245 => "2069",
			246 => "2070",
			247 => "2071",
			248 => "2072",
			249 => "2073",
			250 => "2074",
			251 => "2075",
			252 => "2076",
			253 => "2077",
			254 => "2078",
			255 => "2079",
			256 => "2080",
			257 => "2081",
			258 => "2082",
			259 => "2083",
			260 => "2084",
			261 => "2085",
			262 => "2086",
			263 => "2087",
			264 => "2088",
			265 => "2089",
			266 => "2090",
			267 => "2091",
			268 => "2092",
			269 => "2093",
			270 => "2094",
			271 => "2095",
			272 => "2096",
			273 => "2097",
			274 => "2098",
			275 => "2099",
			276 => "2100",
			277 => "2101",
			278 => "2102",
			279 => "2103",
			280 => "2104",
			281 => "2105",
			282 => "2106",
			283 => "2107",
			284 => "2108",
			285 => "2109",
			286 => "2110",
			287 => "2111",
			288 => "2112",
			289 => "2113",
			290 => "2114",
			291 => "2115",
			292 => "2116",
			293 => "2117",
			294 => "2118",
			295 => "2119",
			296 => "2120",
			297 => "2121",
			298 => "2122",
			299 => "2123",
			300 => "2124",
			301 => "2125",
			302 => "2126",
			303 => "2127",
			304 => "2128",
			305 => "2129",
			306 => "2130",
			307 => "2131",
			308 => "2132",
			309 => "2133",
			310 => "2134",
			311 => "2135",
			312 => "2136",
			313 => "2137",
			314 => "2138",
			315 => "2139",
			316 => "2140",
			317 => "2141",
			318 => "2142",
			319 => "2143",
			320 => "2144",
			321 => "2145",
			322 => "2146",
			323 => "2147",
			324 => "2148",
			325 => "2149",
			326 => "2150",
			327 => "2151",
			328 => "2152",
			329 => "2153",
			330 => "2154",
			331 => "2155",
			332 => "2156",
			333 => "2157",
			334 => "2158",
			335 => "2159",
			336 => "2160",
			337 => "2161",
			338 => "2162",
			339 => "2163",
			340 => "2164",
			341 => "2165",
			342 => "2166",
			343 => "2167",
			344 => "2168",
			345 => "2169",
			346 => "2170",
			347 => "2171",
			348 => "2172",
			349 => "2173",
			350 => "2174",
			351 => "2175",
			352 => "2176",
			353 => "2177",
			354 => "2178",
			355 => "2179",
			356 => "2180",
			357 => "2181",
			358 => "2182",
			359 => "2183",
			360 => "2184",
			361 => "2185",
			362 => "2186",
			363 => "2187",
			364 => "2188",
			365 => "2189",
			366 => "2190",
			367 => "2191",
			368 => "2192",
			369 => "2193",
			370 => "2194",
			371 => "2195",
			372 => "2196",
			373 => "2197",
			374 => "2198",
			375 => "2199",
			376 => "2200",
			377 => "2201",
			378 => "2202",
			379 => "2203",
			380 => "2204",
			381 => "2205",
			382 => "2206",
			383 => "2207",
			384 => "2208",
			385 => "2209",
			386 => "2210",
			387 => "2211",
			388 => "2212",
			389 => "2213",
			390 => "2214",
			391 => "2215",
			392 => "2216",
			393 => "2217",
			394 => "2218",
			395 => "2219",
			396 => "2220",
			397 => "2221",
			398 => "2222",
			399 => "2223",
			400 => "2224",
			401 => "2225",
			402 => "2226",
			403 => "2227",
			404 => "2228",
			405 => "2229",
			406 => "2230",
			407 => "2231",
			408 => "2232",
			409 => "2233",
			410 => "2234",
			411 => "2235",
			412 => "2236",
			413 => "2237",
			414 => "2238",
			415 => "2239",
			416 => "2240",
			417 => "2241",
			418 => "2242",
			419 => "2243",
			420 => "2244",
			421 => "2245",
			422 => "2246",
			423 => "2247",
			424 => "2248",
			425 => "2249",
			426 => "2250",
			427 => "2251",
			428 => "2252",
			429 => "2253",
			430 => "2254",
			431 => "2255",
			432 => "2256",
			433 => "2257",
			434 => "2258",
			435 => "2259",
			436 => "2260",
			437 => "2261",
			438 => "2262",
			439 => "2263",
			440 => "2264",
			441 => "2265",
			442 => "2266",
			443 => "2267",
			444 => "2268",
			445 => "2269",
			446 => "2270",
			447 => "2271",
			448 => "2272",
			449 => "2273",
			450 => "2274",
			451 => "2275",
			452 => "2276",
			453 => "2277",
			454 => "2278",
			455 => "2279",
			456 => "2280",
			457 => "2281",
			458 => "2282",
			459 => "2283",
			460 => "2284",
			461 => "2285",
			462 => "2286",
			463 => "2287",
			464 => "2288",
			465 => "2289",
			466 => "2290",
			467 => "2291",
			468 => "2292",
			469 => "2293",
			470 => "2294",
			471 => "2295",
			472 => "2296",
			473 => "2297",
			474 => "2298",
			475 => "2299",
			476 => "2300",
			477 => "2301",
			478 => "2302",
			479 => "2303",
			480 => "2304",
			481 => "2305",
			482 => "2306",
			483 => "2307",
			484 => "2308",
			485 => "2309",
			486 => "2310",
			487 => "2311",
			488 => "2312",
			489 => "2313",
			490 => "2314",
			491 => "2315",
			492 => "2316",
			493 => "2317",
			494 => "2318",
			495 => "2319",
			496 => "2320",
			497 => "2321",
			498 => "2322",
			499 => "2323",
			500 => "2324",
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