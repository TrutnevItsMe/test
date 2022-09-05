<?
global $arTheme, $arRegion;
$catalog_id = \Bitrix\Main\Config\Option::get(
	"aspro.next",
	"CATALOG_IBLOCK_ID",
	CNextCache::$arIBlocks[SITE_ID]['aspro_next_catalog']['aspro_next_catalog'][0]
);
$arSectionsFilter = [
	"IBLOCK_ID" => $catalog_id,
	"ACTIVE" => "Y",
	"GLOBAL_ACTIVE" => "Y",
	"ACTIVE_DATE" => "Y",
	"<DEPTH_LEVEL" => $arParams["MAX_LEVEL"]
];
$arSections = CNextCache::CIBlockSection_GetList(
	[
		"SORT" => "ASC",
		"ACTIVE" => "Y",
		"ID" => "ASC",
		"CACHE" => [
			"TAG" => CNextCache::GetIBlockCacheTag($catalog_id),
			"GROUP" => ["ID"]
		]
	],
	CNext::makeSectionFilterInRegion($arSectionsFilter),
	false,
	[
		"ID",
		"IBLOCK_ID",
		"NAME",
		"PICTURE",
		"LEFT_MARGIN",
		"RIGHT_MARGIN",
		"DEPTH_LEVEL",
		"SECTION_PAGE_URL",
		"IBLOCK_SECTION_ID",
		"UF_CATALOG_ICON",
		"UF_DISABLE_MENU"]);

$brandSections = $arParams["BRAND_IBLOCK_SECTIONS"];

$arResult = array();
$cur_page = $GLOBALS["APPLICATION"]->GetCurPage(true);
$cur_page_no_index = $GLOBALS["APPLICATION"]->GetCurPage(false);
// IBLOCK_SECTION_ID из разделов бренда
$arBrandsIblockSectionsIds = array_column($brandSections, "IBLOCK_SECTION_ID");
$arBrandsIblockSectionsIds = array_unique($arBrandsIblockSectionsIds);
// ID разделов бренда
$arBrandsIds = array_column($brandSections, "ID");

$topSectionIds = [];

// Выбираем верхний уровень разделов бренда
foreach ($arBrandsIblockSectionsIds as $sectionId)
{
	$_sectionId = $sectionId;

	do
	{
		if (is_array($arSections[$_sectionId]))
		{
			if ($arSections[$_sectionId]["DEPTH_LEVEL"] != 1)
			{
				$_sectionId = $arSections[$_sectionId]["IBLOCK_SECTION_ID"];
			}
			else
			{
				$topSectionIds[] = $_sectionId;
				break;
			}
		}
		else
		{
			$topSectionIds[] = $_sectionId;
			break;
		}
	} while(true);
}

$arBrandsIblockSectionsIds = $topSectionIds;

if (is_array($arParams["READY_MENU"]))
{
	$arResult = $arParams["READY_MENU"];
}
else
{
	foreach ($arSections as $ID => $arSection)
	{
		$arSections[$ID]["SELECTED"] = CMenu::IsItemSelected($arSection["SECTION_PAGE_URL"], $cur_page, $cur_page_no_index);
		$arSections[$ID]["SECTION_PAGE_URL"] = $cur_page . "?section_id=" . $ID;

		if ($arSection["UF_CATALOG_ICON"])
		{
			$img = CFile::ResizeImageGet($arSection["UF_CATALOG_ICON"], array("width" => 36, "height" => 36), BX_RESIZE_IMAGE_PROPORTIONAL, true);
			$arSections[$ID]["IMAGES"] = $img;
		}
		elseif ($arSection["PICTURE"])
		{
			$img = CFile::ResizeImageGet($arSection["PICTURE"], array("width" => 50, "height" => 50), BX_RESIZE_IMAGE_PROPORTIONAL, true);
			$arSections[$ID]["IMAGES"] = $img;
		}

		if ($arSection["IBLOCK_SECTION_ID"])
		{
			if (!isset($arSections[$arSection["IBLOCK_SECTION_ID"]]["CHILD"]))
			{
				$arSections[$arSection["IBLOCK_SECTION_ID"]]["CHILD"] = [];
			}

			// Id секции == Id секции бренда (есть в списке брендов)
			$isIdInBrandId = in_array($arSections[$arSection["ID"]]["ID"], $arBrandsIds);
			// Id секции == IBLOCK_SECTION_ID бренда (есть в списке брендов)
			$isIdInIdSection = in_array($arSections[$arSection["IBLOCK_SECTION_ID"]]["ID"], $arBrandsIblockSectionsIds);
			// IBLOCK_SECTION_ID раздела (папки) == IBLOCK_SECTION_ID бренда
			$isSectionIdInIdSection = in_array($arSections[$arSection["ID"]]["IBLOCK_SECTION_ID"], $arBrandsIblockSectionsIds);

			if ($isIdInIdSection || $isIdInBrandId)
			{
				$section_id = $arSections[$arSection["ID"]]["ID"];
				$arSections[$arSection["ID"]]["SECTION_PAGE_URL"] = $cur_page . "?section_id=" . $section_id;
				$arSections[$arSection["IBLOCK_SECTION_ID"]]["CHILD"][$arSection["ID"]] = &$arSections[$arSection["ID"]];
			}
			// Вложенная в секцию бренда
			if ($isSectionIdInIdSection && $isIdInBrandId)
			{
				$section_id = $arSections[$arSection["ID"]]["ID"];
				$arSections[$arSection["ID"]]["SECTION_PAGE_URL"] = $cur_page . "?section_id=" . $section_id;
				$arSections[$arSection["IBLOCK_SECTION_ID"]]["CHILD"][$arSection["ID"]] = &$arSections[$arSection["ID"]];
			}
		}

		// Формируем левое меню
		if ($arSection["DEPTH_LEVEL"] == 1 && in_array($arSection["ID"], $arBrandsIblockSectionsIds))
		{
			$arResult[] = &$arSections[$arSection["ID"]];
		}
	}
}

?>