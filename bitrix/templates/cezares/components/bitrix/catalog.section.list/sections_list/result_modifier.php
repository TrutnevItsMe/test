<?


if ($arResult['SECTIONS'])
{
	foreach ($arResult['SECTIONS'] as $i => $arSection)
	{
		if ($arSection["CODE"] == "snyato_s_proizvodstva"
			|| strpos($arSection["CODE"], "komplektuyushchie_dlya") !== false)
		{
			unset($arResult['SECTIONS'][$i]);
		}
	}
}

// count elements with region filter
if(
	$arResult['SECTIONS'] &&
	$arParams['COUNT_ELEMENTS'] &&
	$arParams['FILTER_NAME'] &&
	$GLOBALS[$arParams['FILTER_NAME']]['PROPERTY_LINK_REGION']
){

	$elementFilter = array(
		'IBLOCK_ID' => $arParams['IBLOCK_ID'],
		'CHECK_PERMISSIONS' => 'Y',
		'MIN_PERMISSION' => 'R',
		'INCLUDE_SUBSECTIONS' => (isset($sectionFilter['ELEMENT_SUBSECTIONS']) && $sectionFilter['ELEMENT_SUBSECTIONS'] == 'N' ? 'N' : 'Y')
	);
	CNext::makeElementFilterInRegion($elementFilter, $GLOBALS[$arParams['FILTER_NAME']]['PROPERTY_LINK_REGION']);

	switch($arParams['COUNT_ELEMENTS_FILTER']){
		case 'CNT_ALL':
			break;
		case 'CNT_ACTIVE':
			$elementFilter['ACTIVE'] = 'Y';
			$elementFilter['ACTIVE_DATE'] = 'Y';
			break;
		case 'CNT_AVAILABLE':
			$elementFilter['ACTIVE'] = 'Y';
			$elementFilter['ACTIVE_DATE'] = 'Y';
			$elementFilter['AVAILABLE'] = 'Y';
			break;
	}

	foreach($arResult['SECTIONS'] as &$arSection){
		$elementFilter['SECTION_ID'] = $arSection["ID"];
		$arSection['ELEMENT_CNT'] = CIBlockElement::GetList(array(), $elementFilter, array());
	}
	unset($arSection);
}

if($arParams["TOP_DEPTH"]>1){
	$arSections = array();
	$arSectionsDepth3 = array();
	foreach( $arResult["SECTIONS"] as $arItem ) {
		if( $arItem["DEPTH_LEVEL"] == 1 ) { $arSections[$arItem["ID"]] = $arItem;}
		elseif( $arItem["DEPTH_LEVEL"] == 2 ) {$arSections[$arItem["IBLOCK_SECTION_ID"]]["SECTIONS"][$arItem["ID"]] = $arItem;}
		elseif( $arItem["DEPTH_LEVEL"] == 3 ) {$arSectionsDepth3[] = $arItem;}
	}
	if($arSectionsDepth3){
		foreach( $arSectionsDepth3 as $arItem) {
			foreach( $arSections as $key => $arSection) {
				if (is_array($arSection["SECTIONS"][$arItem["IBLOCK_SECTION_ID"]]) && !empty($arSection["SECTIONS"][$arItem["IBLOCK_SECTION_ID"]])) {
					$arSections[$key]["SECTIONS"][$arItem["IBLOCK_SECTION_ID"]]["SECTIONS"][$arItem["ID"]] = $arItem;
				}
			}
		}
	}
	$arResult["SECTIONS"] = $arSections;
}
?>