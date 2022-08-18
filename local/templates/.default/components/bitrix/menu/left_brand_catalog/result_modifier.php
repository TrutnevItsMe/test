<?
global $arTheme, $arRegion;
$catalog_id = \Bitrix\Main\Config\Option::get("aspro.next", "CATALOG_IBLOCK_ID", CNextCache::$arIBlocks[SITE_ID]['aspro_next_catalog']['aspro_next_catalog'][0]);
$arSectionsFilter = array('IBLOCK_ID' => $catalog_id, 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y', '<DEPTH_LEVEL' => $arParams['MAX_LEVEL']);
$arSections = CNextCache::CIBlockSection_GetList(array('SORT' => 'ASC', "ACTIVE" => "Y", 'ID' => 'ASC', 'CACHE' => array('TAG' => CNextCache::GetIBlockCacheTag($catalog_id), 'GROUP' => array('ID'))), CNext::makeSectionFilterInRegion($arSectionsFilter), false, array("ID", "IBLOCK_ID", "NAME", "PICTURE", "LEFT_MARGIN", "RIGHT_MARGIN", "DEPTH_LEVEL", "SECTION_PAGE_URL", "IBLOCK_SECTION_ID", "UF_CATALOG_ICON", "UF_DISABLE_MENU"));
$brandSections = $arParams["BRAND_IBLOCK_SECTIONS"];

$arResult = array();
$cur_page = $GLOBALS['APPLICATION']->GetCurPage(true);
$cur_page_no_index = $GLOBALS['APPLICATION']->GetCurPage(false);
// IBLOCK_SECTION_ID из разделов бренда
$arBrandsIblockSectionsIds = array_column($brandSections, "IBLOCK_SECTION_ID");
// ID разделов бренда
$arBrandsIds = array_column($brandSections, "ID");

if (is_array($arParams["READY_MENU"])){
	$arResult = $arParams["READY_MENU"];
}
else{

	foreach ($arSections as $ID => $arSection) {

		$arSections[$ID]['SELECTED'] = CMenu::IsItemSelected($arSection['SECTION_PAGE_URL'], $cur_page, $cur_page_no_index);

		if ($arSection['UF_CATALOG_ICON']) {
			$img = CFile::ResizeImageGet($arSection['UF_CATALOG_ICON'], array('width' => 36, 'height' => 36), BX_RESIZE_IMAGE_PROPORTIONAL, true);
			$arSections[$ID]['IMAGES'] = $img;
		} elseif ($arSection['PICTURE']) {
			$img = CFile::ResizeImageGet($arSection['PICTURE'], array('width' => 50, 'height' => 50), BX_RESIZE_IMAGE_PROPORTIONAL, true);
			$arSections[$ID]['IMAGES'] = $img;
		}
		if ($arSection['IBLOCK_SECTION_ID'] && in_array($arSection["IBLOCK_SECTION_ID"], $arBrandsIblockSectionsIds)) {
			if (!isset($arSections[$arSection['IBLOCK_SECTION_ID']]['CHILD'])) {
				$arSections[$arSection['IBLOCK_SECTION_ID']]['CHILD'] = array();
			}

			// Id секции == Id секции бренда (есть в списке брендов)
			$isIdInBrandId = in_array($arSections[$arSection['ID']]["ID"], $arBrandsIds);
			// Id секции == IBLOCK_SECTION_ID бренда (есть в списке брендов)
			$isIdInIdSection = in_array($arSections[$arSection['ID']]["ID"], $arBrandsIblockSectionsIds);
			// IBLOCK_SECTION_ID раздела (папки) == IBLOCK_SECTION_ID бренда
			$isSectionIdInIdSection = in_array($arSections[$arSection['ID']]["IBLOCK_SECTION_ID"], $arBrandsIblockSectionsIds);

			// DEPTH_LEVEL == 2 -- Текущий уровень вложенности секции в брендах
			if ($arSections[$arSection['ID']]["DEPTH_LEVEL"] == 2 &&
				($isIdInIdSection || $isIdInBrandId)) {

				$section_id = $arSections[$arSection['ID']]["ID"];
				$arSections[$arSection['ID']]["SECTION_PAGE_URL"] = $cur_page . "?section_id=" . $section_id;
				$arSections[$arSection['IBLOCK_SECTION_ID']]['CHILD'][] = &$arSections[$arSection['ID']];
			}
			// Вложенная в секцию бренда
			elseif ($isSectionIdInIdSection && $isIdInBrandId) {

				$section_id = $arSections[$arSection['ID']]["ID"];
				$arSections[$arSection['ID']]["SECTION_PAGE_URL"] = $cur_page . "?section_id=" . $section_id;
				$arSections[$arSection['IBLOCK_SECTION_ID']]['CHILD'][] = &$arSections[$arSection['ID']];
			}

		}
		// Формируем левое меню
		if ($arSection['DEPTH_LEVEL'] == 1 && in_array($arSection["ID"], $arBrandsIblockSectionsIds)) {
			$arResult[] = &$arSections[$arSection['ID']];
		}
	}
}

?>