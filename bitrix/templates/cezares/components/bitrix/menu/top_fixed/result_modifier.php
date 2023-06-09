<?
$arResult = CNext::getChilds($arResult);
global $arRegion, $arTheme;

if($arResult){
	if($bUseMegaMenu = $arTheme['USE_MEGA_MENU']['VALUE'] === 'Y'){
		$arMegaLinks = $arMegaItems = array();

		$menuIblockId = CNextCache::$arIBlocks[SITE_ID]['aspro_next_catalog']['aspro_next_megamenu'][0];
		if($menuIblockId){
			$arMenuSections = CNextCache::CIblockSection_GetList(
				array(
					'SORT' => 'ASC',
					'ID' => 'ASC',
					'CACHE' => array(
						'TAG' => CNextCache::GetIBlockCacheTag($menuIblockId),
						'GROUP' => array('DEPTH_LEVEL'),
						'MULTI' => 'Y',
					)
				),
				array(
					'ACTIVE' => 'Y',
					'GLOBAL_ACTIVE' => 'Y',
					'IBLOCK_ID' => $menuIblockId,
					'<=DEPTH_LEVEL' => $arParams['MAX_LEVEL'],
				),
				false,
				array(
					'ID',
					'NAME',
					'IBLOCK_SECTION_ID',
					'DEPTH_LEVEL',
					'PICTURE',
					'UF_MEGA_MENU_LINK',
				)
			);

			if($arMenuSections){
				$cur_page = $GLOBALS['APPLICATION']->GetCurPage(true);
				$cur_page_no_index = $GLOBALS['APPLICATION']->GetCurPage(false);
				$some_selected = false;
				$bMultiSelect = $arParams['ALLOW_MULTI_SELECT'] === 'Y';

				foreach($arMenuSections as $depth => $arLinks){
					foreach($arLinks as $arLink){
						$url = trim($arLink['UF_MEGA_MENU_LINK']);
						if(
							(
								$depth == 1 &&
								strlen($url)
							) ||
							$depth > 1
						){
							$arMegaItem = array(
								'TEXT' => htmlspecialcharsbx($arLink['NAME']),
								'LINK' => strlen($url) ? $url : 'javascript:;',
								'SELECTED' => false,
								'PARAMS' => array(
									'PICTURE' => $arLink['PICTURE'],
								),
								'CHILD' => array(),
							);

							$arMegaItems[$arLink['ID']] =& $arMegaItem;

							if($depth > 1){
								if(
									strlen($url) &&
									($bMultiSelect || !$some_selected)
								){
									$arMegaItem['SELECTED'] = CMenu::IsItemSelected($url, $cur_page, $cur_page_no_index);
								}

								if($arMegaItems[$arLink['IBLOCK_SECTION_ID']]){
									$arMegaItems[$arLink['IBLOCK_SECTION_ID']]['IS_PARENT'] = 1;
									$arMegaItems[$arLink['IBLOCK_SECTION_ID']]['CHILD'][] =& $arMegaItems[$arLink['ID']];
								}
							}
							else{
								$arMegaLinks[] =& $arMegaItems[$arLink['ID']];
							}

							unset($arMegaItem);
						}
					}
				}
			}
		}
	}

	if($bUseMegaMenu && $arMegaLinks){
		foreach($arResult as $key => $arItem){
			foreach($arMegaLinks as $arLink){
				if($arItem['LINK'] == $arLink['LINK']){
					if($arResult[$key]['PARAMS']['MEGA_MENU_CHILDS']){
						array_splice($arResult, $key, 1, $arLink['CHILD']);
					}
					else{
						$arResult[$key]['CHILD'] =& $arLink['CHILD'];
						$arResult[$key]['IS_PARENT'] = boolval($arLink['CHILD']);
					}
				}
			}
		}
	}

	foreach($arResult as $key=>$arItem)
	{
		if(isset($arItem["PARAMS"]["ONLY_MOBILE"]) && $arItem["PARAMS"]["ONLY_MOBILE"]=="Y") {
		    unset($arResult[$key]);
		    continue;
		}

		if(isset($arItem['CHILD']))
		{
			foreach($arItem['CHILD'] as $key2=>$arItemChild)
			{
				if(isset($arItemChild['PARAMS']) && $arRegion && $arTheme['USE_REGIONALITY']['VALUE'] === 'Y' && $arTheme['USE_REGIONALITY']['DEPENDENT_PARAMS']['REGIONALITY_FILTER_ITEM']['VALUE'] === 'Y')
				{
					// filter items by region
					if(isset($arItemChild['PARAMS']['LINK_REGION']))
					{
						if($arItemChild['PARAMS']['LINK_REGION'])
						{
							if(!in_array($arRegion['ID'], $arItemChild['PARAMS']['LINK_REGION']))
								unset($arResult[$key]['CHILD'][$key2]);
						}
						else
							unset($arResult[$key]['CHILD'][$key2]);
					}
				}
			}
		}
	}
}?>