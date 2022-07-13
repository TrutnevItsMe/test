<?
global $arTheme, $arRegion;
$catalog_id = \Bitrix\Main\Config\Option::get("aspro.next", "CATALOG_IBLOCK_ID", CNextCache::$arIBlocks[SITE_ID]['aspro_next_catalog']['aspro_next_catalog'][0]);
$arSectionsFilter = array('IBLOCK_ID' => $catalog_id, 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y', '<DEPTH_LEVEL' => $arParams['MAX_LEVEL']);
$arSections = CNextCache::CIBlockSection_GetList(array('SORT' => 'ASC', "ACTIVE" => "Y", 'ID' => 'ASC', 'CACHE' => array('TAG' => CNextCache::GetIBlockCacheTag($catalog_id), 'GROUP' => array('ID'))), CNext::makeSectionFilterInRegion($arSectionsFilter), false, array("ID", "IBLOCK_ID", "NAME", "PICTURE", "LEFT_MARGIN", "RIGHT_MARGIN", "DEPTH_LEVEL", "SECTION_PAGE_URL", "IBLOCK_SECTION_ID", "UF_CATALOG_ICON", "UF_DISABLE_MENU"));
$brandSections = $arParams["BRAND_IBLOCK_SECTIONS"];

if ($arSections && $brandSections) {
	$arResult = array();
	$arBrandSections = []; // Секции бренда

	$cur_page = $GLOBALS['APPLICATION']->GetCurPage(false);

	// Пробежимся по всем секциям и выберем только те, которые есть у брендов
	foreach ($brandSections as $key => $brandSection){

		// ID раздела -- подраздел глобальной секции
		if ($brandSection['IBLOCK_SECTION_ID']) {

			//
			$arBrandSections[$brandSection['IBLOCK_SECTION_ID']] = &$arSections[$brandSection['IBLOCK_SECTION_ID']];

			if (!isset($arBrandSections[$brandSection['IBLOCK_SECTION_ID']]['CHILD'])) {
				$arBrandSections[$brandSection['IBLOCK_SECTION_ID']]['CHILD'] = array();
			}

			// Картинка секции бренда
			if ($brandSection['PICTURE']){
				$img = CFile::ResizeImageGet($brandSection['PICTURE'], array('width' => 50, 'height' => 50), BX_RESIZE_IMAGE_PROPORTIONAL, true);
				$brandSection['IMAGES'] = $img;
			}
			// Устанавливаем ссылку перехода на товары бренда
			$brandSection["SECTION_PAGE_URL"] = $cur_page . "?section_id=" . $brandSection["ID"];
			$arBrandSections[$brandSection['IBLOCK_SECTION_ID']]['CHILD'][] = $brandSection;
		}
		else{
			$arBrandSections[] = $brandSection;
		}
	}
	foreach ($arBrandSections as $ID => $brandSection){
		$arResult[] = $brandSection;
	}
} ?>