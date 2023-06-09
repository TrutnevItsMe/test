<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);


$asset = \Bitrix\Main\Page\Asset::getInstance();
$asset->addJs($templateFolder."/script.js");


 if (count($arResult["ITEMS"]) >= 1) { ?>
	 <?php if (($arParams["AJAX_REQUEST"] == "N") || !isset($arParams["AJAX_REQUEST"])) { ?>
		 <?php if (isset($arParams["TITLE"]) && $arParams["TITLE"]): ?>
			<hr/>
			<h5><?= $arParams['TITLE']; ?></h5>
		 <?php endif; ?>
		<div class="top_wrapper row margin0 <?= ($arParams["SHOW_UNABLE_SKU_PROPS"] != "N" ? "show_un_props" : "unshow_un_props"); ?>">
		<div class="catalog_block items block_list">
	 <?php } ?>
	 <?php
	$currencyList = '';
	if (!empty($arResult['CURRENCIES']))
	{
		$templateLibrary[] = 'currency';
		$currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
	}
	$templateData = array(
		'TEMPLATE_LIBRARY' => $templateLibrary,
		'CURRENCIES' => $currencyList
	);
	unset($currencyList, $templateLibrary);

	$arParams["BASKET_ITEMS"] = ($arParams["BASKET_ITEMS"] ? $arParams["BASKET_ITEMS"] : array());
	$arOfferProps = implode(';', $arParams['OFFERS_CART_PROPERTIES']);

	// params for catalog elements compact view
	$arParamsCE_CMP = $arParams;
	$arParamsCE_CMP['TYPE_SKU'] = 'N';

	switch ($arParams["LINE_ELEMENT_COUNT"])
	{
		case '1':
		case '2':
			$col = 2;
			break;
		case '3':
			$col = 3;
			break;
		case '5':
			$col = 5;
			break;
		default:
			$col = 4;
			break;
	}

	if ($arParams["LINE_ELEMENT_COUNT"] > 5)
		$col = 5; ?>

	 <?php foreach ($arResult["ITEMS"] as $arItem) { ?>
		<div class="item_block col-<?= $col; ?> col-md-<?= ceil(12 / $col); ?> col-sm-<?= ceil(12 / round($col / 2)) ?> col-xs-6">
			<div class="catalog_item_wrapp item">
				<div class="basket_props_block" id="bx_basket_div_<?= $arItem["ID"]; ?>" style="display: none;">
					<?php if (!empty($arItem['PRODUCT_PROPERTIES_FILL']))
					{
						foreach ($arItem['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo)
						{
							?>
							<input type="hidden"
								   name="<?php echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<?php echo $propID; ?>]"
								   value="<?php echo htmlspecialcharsbx($propInfo['ID']); ?>">
							<?php if (isset($arItem['PRODUCT_PROPERTIES'][$propID]))
							unset($arItem['PRODUCT_PROPERTIES'][$propID]);
						}
					}
					$arItem["EMPTY_PROPS_JS"] = "Y";
					$emptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
					if (!$emptyProductProperties)
					{
						$arItem["EMPTY_PROPS_JS"] = "N"; ?>
						<div class="wrapper">
							<table>
								<?php foreach ($arItem['PRODUCT_PROPERTIES'] as $propID => $propInfo) {
									?>
									<tr>
										<td><?php echo $arItem['PROPERTIES'][$propID]['NAME']; ?></td>
										<td>
											<?php if ('L' == $arItem['PROPERTIES'][$propID]['PROPERTY_TYPE'] && 'C' == $arItem['PROPERTIES'][$propID]['LIST_TYPE'])
											{
												foreach ($propInfo['VALUES'] as $valueID => $value)
												{
													?>
													<label>
														<input type="radio"
															   name="<?php echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<?php echo $propID; ?>]"
															   value="<?php echo $valueID; ?>" <?php echo($valueID == $propInfo['SELECTED'] ? '"checked"' : ''); ?>><?php echo $value; ?>
													</label>
													<?php
												}
											}
											else
											{
												?>
												<select name="<?php echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<?php echo $propID; ?>]"><?php
													foreach ($propInfo['VALUES'] as $valueID => $value)
													{
														?>
														<option value="<?php echo $valueID; ?>" <?php echo($valueID == $propInfo['SELECTED'] ? '"selected"' : ''); ?>><?php echo $value; ?></option>
													<?php } ?>
												</select>
											<?php } ?>
										</td>
									</tr>
								<?php } ?>
							</table>
						</div>
						<?php
					} ?>
				</div>
				<?php $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));

				$arItem["strMainID"] = $this->GetEditAreaId($arItem['ID']);
				$arItemIDs = CNext::GetItemsIDs($arItem);

				$totalCount = CNext::GetTotalCount($arItem, $arParams);
				$arQuantityData = CNext::GetQuantityArray($totalCount, $arItemIDs["ALL_ITEM_IDS"], "N", $arItem["PRODUCT"]["TYPE"]);

				$bLinkedItems = (isset($arParams["LINKED_ITEMS"]) && $arParams["LINKED_ITEMS"]);
				if ($bLinkedItems)
					$arItem["FRONT_CATALOG"] = "Y";
				$elementName = ((isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arItem['NAME']);


				$item_id = $arItem["ID"];
				$strMeasure = '';
				$arAddToBasketData = array();

				$arCurrentSKU = array();

				if (!$arItem["OFFERS"] || $arParams['TYPE_SKU'] !== 'TYPE_1')
				{
					if ($arParams["SHOW_MEASURE"] == "Y" && $arItem["CATALOG_MEASURE"])
					{
						$arMeasure = CCatalogMeasure::getList(array(), array("ID" => $arItem["CATALOG_MEASURE"]), false, false, array())->GetNext();
						$strMeasure = $arMeasure["SYMBOL_RUS"];
					}
					$arAddToBasketData = CNext::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"],
                        $arParams["BASKET_URL"], ($bLinkedItems ? true : false), $arItemIDs["ALL_ITEM_IDS"],
                        'small' . ($totalCount ? '' : ' disabled'), $arParams);
				}
				elseif ($arItem["OFFERS"])
				{
					$strMeasure = $arItem["MIN_PRICE"]["CATALOG_MEASURE_NAME"];
					if ($arParams['TYPE_SKU'] == 'TYPE_1' && $arItem['OFFERS_PROP'])
					{
						$totalCount = CNext::GetTotalCount($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]], $arParams);
						$arQuantityData = CNext::GetQuantityArray($totalCount, $arItemIDs["ALL_ITEM_IDS"], "N", $arItem["PRODUCT"]["TYPE"]);

						$currentSKUIBlock = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["IBLOCK_ID"];
						$currentSKUID = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["ID"];

						$arItem["DETAIL_PAGE_URL"] = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DETAIL_PAGE_URL"];
						if ($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"])
							$arItem["PREVIEW_PICTURE"] = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"];
						if ($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"])
							$arItem["DETAIL_PICTURE"] = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DETAIL_PICTURE"];

						if ($arParams["SET_SKU_TITLE"] === "Y")
						{
							$skuName = ((isset($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['NAME']);
							$arItem["NAME"] = $elementName = $skuName;
						}
						$item_id = $currentSKUID;

						// ARTICLE
						if ($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"])
						{
							$arItem["ARTICLE"]["NAME"] = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DISPLAY_PROPERTIES"]["ARTICLE"]["NAME"];
							$arItem["ARTICLE"]["VALUE"] = (is_array($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"]) ? reset($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"]) : $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"]);
						}

						$arCurrentSKU = $arItem["JS_OFFERS"][$arItem["OFFERS_SELECTED"]];
						$strMeasure = $arCurrentSKU["MEASURE"];
					}
				}
				?>
				<div class="catalog_item main_item_wrapper item_wrap <?= (($_GET['q'])) ? 's' : '' ?>"
					 id="<?= $arItemIDs["strMainID"]; ?>">
					<div>
						<div class="image_wrapper_block">
							<div class="stickers">
								<?php $prop = ($arParams["STIKERS_PROP"] ? $arParams["STIKERS_PROP"] : "HIT"); ?>
								<?php foreach (CNext::GetItemStickers($arItem["PROPERTIES"][$prop]) as $arSticker): ?>
									<div>
										<div class="<?= $arSticker['CLASS'] ?>"><?= $arSticker['VALUE'] ?></div>
									</div>
								<?php endforeach; ?>
								<?php if ($arParams["SALE_STIKER"] && $arItem["PROPERTIES"][$arParams["SALE_STIKER"]]["VALUE"]) { ?>
									<div>
										<div class="sticker_sale_text"><?= $arItem["PROPERTIES"][$arParams["SALE_STIKER"]]["VALUE"]; ?></div>
									</div>
								<?php } ?>
							</div>
							<?php if ($arParams["DISPLAY_WISH_BUTTONS"] != "N" || $arParams["DISPLAY_COMPARE"] == "Y"): ?>
								<div class="like_icons">
									<?php if ($arParams["DISPLAY_WISH_BUTTONS"] != "N"): ?>
										<?php if (!$arItem["OFFERS"]): ?>
											<div class="wish_item_button" <?= ($arAddToBasketData['CAN_BUY'] ? '' : 'style="display:none"'); ?>>
												<span title="<?= GetMessage('CATALOG_WISH') ?>" class="wish_item to"
													  data-item="<?= $arItem["ID"] ?>"
													  data-iblock="<?= $arItem["IBLOCK_ID"] ?>"><i></i></span>
												<span title="<?= GetMessage('CATALOG_WISH_OUT') ?>"
													  class="wish_item in added" style="display: none;"
													  data-item="<?= $arItem["ID"] ?>"
													  data-iblock="<?= $arItem["IBLOCK_ID"] ?>"><i></i></span>
											</div>
										<?php elseif ($arItem["OFFERS"] && !empty($arItem['OFFERS_PROP'])): ?>
											<div class="wish_item_button ce_cmp_hidden">
												<span title="<?= GetMessage('CATALOG_WISH') ?>"
													  class="wish_item to <?= $arParams["TYPE_SKU"]; ?>"
													  data-item="<?= $currentSKUID; ?>"
													  data-iblock="<?= $currentSKUIBlock ?>" data-offers="Y"
													  data-props="<?= $arOfferProps ?>"><i></i></span>
												<span title="<?= GetMessage('CATALOG_WISH_OUT') ?>"
													  class="wish_item in added <?= $arParams["TYPE_SKU"]; ?>"
													  style="display: none;" data-item="<?= $currentSKUID; ?>"
													  data-iblock="<?= $currentSKUIBlock ?>"><i></i></span>
											</div>
										<?php endif; ?>
									<?php endif; ?>
									<?php if ($arParams["DISPLAY_COMPARE"] == "Y"): ?>
										<?php if (!$arItem["OFFERS"] || ($arParams["TYPE_SKU"] !== 'TYPE_1' || ($arParams["TYPE_SKU"] == 'TYPE_1' && !$arItem["OFFERS_PROP"]))): ?>
											<div class="compare_item_button">
												<span title="<?= GetMessage('CATALOG_COMPARE') ?>"
													  class="compare_item to"
													  data-iblock="<?= $arParams["IBLOCK_ID"] ?>"
													  data-item="<?= $arItem["ID"] ?>"><i></i></span>
												<span title="<?= GetMessage('CATALOG_COMPARE_OUT') ?>"
													  class="compare_item in added" style="display: none;"
													  data-iblock="<?= $arParams["IBLOCK_ID"] ?>"
													  data-item="<?= $arItem["ID"] ?>"><i></i></span>
											</div>
										<?php elseif ($arItem["OFFERS"]): ?>
											<div class="compare_item_button ce_cmp_hidden">
												<span title="<?= GetMessage('CATALOG_COMPARE') ?>"
													  class="compare_item to <?= $arParams["TYPE_SKU"]; ?>"
													  data-item="<?= $currentSKUID; ?>"
													  data-iblock="<?= $arItem["IBLOCK_ID"] ?>"><i></i></span>
												<span title="<?= GetMessage('CATALOG_COMPARE_OUT') ?>"
													  class="compare_item in added <?= $arParams["TYPE_SKU"]; ?>"
													  style="display: none;" data-item="<?= $currentSKUID; ?>"
													  data-iblock="<?= $arItem["IBLOCK_ID"] ?>"><i></i></span>
											</div>
											<div class="compare_item_button ce_cmp_visible">
												<span title="<?= GetMessage('CATALOG_COMPARE') ?>"
													  class="compare_item to"
													  data-iblock="<?= $arParams["IBLOCK_ID"] ?>"
													  data-item="<?= $arItem["ID"] ?>"><i></i></span>
												<span title="<?= GetMessage('CATALOG_COMPARE_OUT') ?>"
													  class="compare_item in added" style="display: none;"
													  data-iblock="<?= $arParams["IBLOCK_ID"] ?>"
													  data-item="<?= $arItem["ID"] ?>"><i></i></span>
											</div>
										<?php endif; ?>
									<?php endif; ?>
								</div>
							<?php endif; ?>
							<a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="thumb shine"
							   id="<?php echo $arItemIDs["ALL_ITEM_IDS"]['PICT']; ?>">
								<?php
								if ($arParams["SET_SKU_TITLE"] === "Y" && $arItem['OFFERS'])
								{
									$a_alt = ($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"] && strlen($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"]['DESCRIPTION']) ? $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"]['DESCRIPTION'] : ($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] ? $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $skuName));
									$a_title = ($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"] && strlen($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"]['DESCRIPTION']) ? $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"]['DESCRIPTION'] : ($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] ? $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] : $skuName));
								}
								else
								{
									$a_alt = ($arItem["PREVIEW_PICTURE"] && strlen($arItem["PREVIEW_PICTURE"]['DESCRIPTION']) ? $arItem["PREVIEW_PICTURE"]['DESCRIPTION'] : ($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arItem["NAME"]));
									$a_title = ($arItem["PREVIEW_PICTURE"] && strlen($arItem["PREVIEW_PICTURE"]['DESCRIPTION']) ? $arItem["PREVIEW_PICTURE"]['DESCRIPTION'] : ($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] : $arItem["NAME"]));
								}
								?>
								<?php if (!empty($arItem["PREVIEW_PICTURE"])): ?>
									<img src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>" alt="<?= $a_alt; ?>"
										 title="<?= $a_title; ?>"/>
								<?php elseif (!empty($arItem["DETAIL_PICTURE"])): ?>
									<?php $img = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array("width" => 170, "height" => 170), BX_RESIZE_IMAGE_PROPORTIONAL, true); ?>
									<img src="<?= $img["src"] ?>" alt="<?= $a_alt; ?>" title="<?= $a_title; ?>"/>
								<?php else: ?>
									<img src="<?= SITE_TEMPLATE_PATH ?>/images/no_photo_medium.png" alt="<?= $a_alt; ?>"
										 title="<?= $a_title; ?>"/>
								<?php endif; ?>
								<?php if ($fast_view_text_tmp = CNext::GetFrontParametrValue('EXPRESSION_FOR_FAST_VIEW'))
									$fast_view_text = $fast_view_text_tmp;
								else
									$fast_view_text = GetMessage('FAST_VIEW'); ?>
							</a>
							<div class="fast_view_block" data-event="jqm" data-param-form_id="fast_view"
								 data-param-iblock_id="<?= $arParams["IBLOCK_ID"]; ?>"
								 data-param-id="<?= $arItem["ID"]; ?>" data-param-fid="<?= $arItemIDs["strMainID"]; ?>"
								 data-param-item_href="<?= urlencode($arItem["DETAIL_PAGE_URL"]); ?>"
								 data-name="fast_view"><?= $fast_view_text; ?></div>
						</div>
						<div class="item_info <?= $arParams["TYPE_SKU"] ?>">
							<div class="item-title">
								<a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"
								   class="dark_link"><span><?= $elementName; ?></span></a>
							</div>
							<?php if ($arParams["SHOW_RATING"] == "Y"): ?>
								<div class="rating">
									<?php $frame = $this->createFrame('dv_' . $arItem["ID"])->begin(''); ?>
									<?php $APPLICATION->IncludeComponent(
										"bitrix:iblock.vote",
										"element_rating_front",
										array(
											"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
											"IBLOCK_ID" => $arItem["IBLOCK_ID"],
											"ELEMENT_ID" => $arItem["ID"],
											"MAX_VOTE" => 5,
											"VOTE_NAMES" => array(),
											"CACHE_TYPE" => $arParams["CACHE_TYPE"],
											"CACHE_TIME" => $arParams["CACHE_TIME"],
											"DISPLAY_AS_RATING" => 'vote_avg'
										),
										$component, array("HIDE_ICONS" => "Y")
									); ?>
									<?php $frame->end(); ?>
								</div>
							<?php endif; ?>
							<div class="sa_block" style="min-height: 190px;">
								<?= $arQuantityData["HTML"]; ?>
								<div class="article_block"
									 <?php if (isset($arItem['ARTICLE']) && $arItem['ARTICLE']['VALUE']): ?>data-name="<?= $arItem['ARTICLE']['NAME']; ?>"
									 data-value="<?= $arItem['ARTICLE']['VALUE']; ?>"<?php endif; ?>>
									<?php if (isset($arItem['ARTICLE']) && $arItem['ARTICLE']['VALUE']) { ?>
										<div><?= $arItem['ARTICLE']['NAME']; ?>
											: <?= $arItem['ARTICLE']['VALUE']; ?></div>
									<?php } ?>
									<table>
										<?php foreach ($arParams["PROPERTY_CODE"] as $prop):?>
											<?php if ($arItem["PROPERTIES"][$prop]["VALUE"]):?>
											<tr>
												<td><?=$arItem["PROPERTIES"][$prop]["NAME"]?>:
													<?=$arItem["PROPERTIES"][$prop]["VALUE"]?></td>
											</tr>
											<?php endif?>
										<?php endforeach;?>
										<tr>
											<td>Код: <?= $arItem["ID"] ?></td>
										</tr>

										<!--<?php if ($arItem["PROPERTIES"]["VYSOTA_IZDELIYA_SM"]["VALUE"] and $arItem["PROPERTIES"]["SHIRINA_IZDELIYA_SM"]["VALUE"] and $arItem["PROPERTIES"]["DLINA_IZDELIYA_SM"]["VALUE"]) { ?>
											<tr><td>Габариты см</td><td><?= $arItem["PROPERTIES"]["VYSOTA_IZDELIYA_SM"]["VALUE"] ?>x<?= $arItem["PROPERTIES"]["SHIRINA_IZDELIYA_SM"]["VALUE"] ?>x<?= $arItem["PROPERTIES"]["DLINA_IZDELIYA_SM"]["VALUE"] ?></td></tr>
											<?php } ?>-->
										<?php if ($arItem["PROPERTIES"]["REZHIM_SLIVA_VODY"]["VALUE"]) { ?>
											<tr>
												<td>Кнопки
													слива: <?= $arItem["PROPERTIES"]["REZHIM_SLIVA_VODY"]["VALUE"] ?></td>
											</tr>
										<?php } ?>
										<?php if ($arItem["PROPERTIES"]["KRYSHKA_SIDENE_S_MIKROLIFTOM"]["VALUE"]) { ?>
											<tr>
												<td>
													Микролифт: <?= $arItem["PROPERTIES"]["KRYSHKA_SIDENE_S_MIKROLIFTOM"]["VALUE"] ?></td>
											</tr>
										<?php } ?>
										<?php if ($arItem["PROPERTIES"]["NAPRAVLENIE_VYPUSKA"]["VALUE"]) { ?>
											<tr>
												<td>Направление
													выпуска: <?= $arItem["PROPERTIES"]["NAPRAVLENIE_VYPUSKA"]["VALUE"] ?></td>
											</tr>
										<?php } ?>
										<?php if ($arItem["PROPERTIES"]["BEZOBODKOVYY_UNITAZ"]["VALUE"]) { ?>
											<tr>
												<td>
													Безободковый: <?= $arItem["PROPERTIES"]["BEZOBODKOVYY_UNITAZ"]["VALUE"] ?></td>
											</tr>
										<?php } ?>
										<?php if ($arItem["PROPERTIES"]["DLINA_CHASHI_SM"]["VALUE"]) { ?>
											<tr>
												<td>Длинна
													чаши: <?= $arItem["PROPERTIES"]["DLINA_CHASHI_SM"]["VALUE"] ?></td>
											</tr>
										<?php } ?>
									</table>
								</div>
							</div>
							<div class="cost prices clearfix">
								<?php if ($arItem["OFFERS"]) { ?>
									<div class="with_matrix <?= ($arParams["SHOW_OLD_PRICE"] == "Y" ? 'with_old' : ''); ?>"
										 style="display:none;">
										<div class="price price_value_block"><span class="values_wrapper"></span></div>
										<?php if ($arParams["SHOW_OLD_PRICE"] == "Y"): ?>
											<div class="price discount"></div>
										<?php endif; ?>
										<?php if ($arParams["SHOW_DISCOUNT_PERCENT"] == "Y") { ?>
											<div class="sale_block matrix" style="display:none;">
												<div class="sale_wrapper">
													<?php if ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] != "Y"): ?>
														<div class="text">
															<span class="title"><?= GetMessage("CATALOG_ECONOMY"); ?></span>
															<span class="values_wrapper"></span>
														</div>
													<?php else: ?>
														<div class="value">-<span></span>%</div>
														<div class="text">
															<span class="title"><?= GetMessage("CATALOG_ECONOMY"); ?></span>
															<span class="values_wrapper"></span>
														</div>
													<?php endif; ?>
													<div class="clearfix"></div>
												</div>
											</div>
										<?php } ?>
									</div>
									<?php if ($arCurrentSKU): ?>
										<div class="ce_cmp_visible">
											<?php \Aspro\Functions\CAsproSku::showItemPrices($arParamsCE_CMP, $arItem, $item_id, $min_price_id, $arItemIDs, ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y")); ?>
										</div>
									<?php endif; ?>
									<div class="js_price_wrapper price">
										<?php if ($arCurrentSKU): ?>
											<?php
											$item_id = $arCurrentSKU["ID"];
											$arCurrentSKU['PRICE_MATRIX'] = $arCurrentSKU['PRICE_MATRIX_RAW'];
											$arCurrentSKU['CATALOG_MEASURE_NAME'] = $arCurrentSKU['MEASURE'];
											if (isset($arCurrentSKU['PRICE_MATRIX']) && $arCurrentSKU['PRICE_MATRIX']) // USE_PRICE_COUNT
											{
												?>
												<?php if ($arCurrentSKU['ITEM_PRICE_MODE'] == 'Q' && count($arCurrentSKU['PRICE_MATRIX']['ROWS']) > 1):?>
												<?= CNext::showPriceRangeTop($arCurrentSKU, $arParams, GetMessage("CATALOG_ECONOMY")); ?>
											<?php endif; ?>
												<?= CNext::showPriceMatrix($arCurrentSKU, $arParams, $strMeasure, $arAddToBasketData); ?>
												<?php $arMatrixKey = array_keys($arCurrentSKU['PRICE_MATRIX']['MATRIX']);
												$min_price_id = current($arMatrixKey); ?>
												<?php
											}
											else
											{
												$arCountPricesCanAccess = 0;
												$min_price_id = 0; ?>
												<?php \Aspro\Functions\CAsproItem::showItemPrices($arParams, $arCurrentSKU["PRICES"], $strMeasure, $min_price_id, ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y")); ?>
											<?php } ?>
										<?php else: ?>
											<?php \Aspro\Functions\CAsproSku::showItemPrices($arParams, $arItem, $item_id, $min_price_id, $arItemIDs, ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y")); ?>
										<?php endif; ?>
									</div>
								<?php } else { ?>
									<?php
									$item_id = $arItem["ID"];
									if (isset($arItem['PRICE_MATRIX']) && $arItem['PRICE_MATRIX']) // USE_PRICE_COUNT
									{
										?>
										<?php if ($arItem['ITEM_PRICE_MODE'] == 'Q' && count($arItem['PRICE_MATRIX']['ROWS']) > 1):?>
										<?= CNext::showPriceRangeTop($arItem, $arParams, GetMessage("CATALOG_ECONOMY")); ?>
									<?php endif; ?>
										<?php $arMatrixKey = array_keys($arItem['PRICE_MATRIX']['MATRIX']);
										$min_price_id = current($arMatrixKey); ?>
										<?php
									}
									else
									{
										$arCountPricesCanAccess = 0;
										$min_price_id = 0; ?>
										<?php \Aspro\Functions\CAsproItem::showItemPrices($arParams, $arItem["PRICES"], $strMeasure, $min_price_id, ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y")); ?>
									<?php } ?>
								<?php } ?>
								<div class="price_matrix_block">
									<div class="price_matrix_wrapper ">
										<div class="price" data-currency="RUB" data-value="<?= $arItem['PRICE'] ?>">
											<?php
											$db_res = CPrice::GetList(
												array(),
												array(
													"PRODUCT_ID" => $arItem['ID'],
													"CATALOG_GROUP_ID" => 13
												)
											);
											?>

											<?php
											$db_res = CPrice::GetList(
												array(),
												array(
													"PRODUCT_ID" => $arItem['ID'],
													"CATALOG_GROUP_ID" => 14
												)
											);
											if ($ar_res14 = $db_res->Fetch())
											{
												$vigoda = $ar_res13['PRICE'] - $ar_res14["PRICE"];
											}

											$discount_price = $arItem['PRICE_MATRIX']["MATRIX"][13]["ZERO-INF"]["PRICE"];
											$price = $arItem['PRICE_MATRIX']["MATRIX"][14]["ZERO-INF"]["PRICE"];
											$vigoda = $price - $discount_price;


											if ($vigoda and (intval($discount_price)))
											{
												?>
												<table cellpadding="4">
													<tr>
														<td>
															<span style=""><?= number_format($arItem['PRICE_MATRIX']["MATRIX"][13]["ZERO-INF"]["PRICE"], 0, ',', ' '); ?> руб.</span>
														</td>
														<td>
															<s style="padding-left: 8px; color: #333; font-size:15px;font-weight: normal; "><?= number_format($arItem['PRICE_MATRIX']["MATRIX"][14]["ZERO-INF"]["PRICE"], 0, ',', ' '); ?>
																руб.</s></td>
													</tr>
												</table>
												<?php
											} else {
												?>
												<?= number_format($arItem['PRICE_MATRIX']["MATRIX"][13]["ZERO-INF"]["PRICE"], 0, ',', ' '); ?> руб.
												<?php
											}
											$vigoda = 0;
											?>

										</div>
									</div>
								</div>
							</div>
							<?php if ($arParams["SHOW_DISCOUNT_TIME"] == "Y" && $arParams['SHOW_COUNTER_LIST'] != 'N') { ?>
								<?php $arUserGroups = $USER->GetUserGroupArray(); ?>
								<?php if ($arParams['SHOW_DISCOUNT_TIME_EACH_SKU'] != 'Y' || ($arParams['SHOW_DISCOUNT_TIME_EACH_SKU'] == 'Y' && !$arItem['OFFERS'])): ?>
									<?php $arDiscounts = CCatalogDiscount::GetDiscountByProduct($item_id, $arUserGroups, "N", $min_price_id, SITE_ID);
									$arDiscount = array();
									if ($arDiscounts)
										$arDiscount = current($arDiscounts);
									if ($arDiscount["ACTIVE_TO"])
									{
										?>
										<div class="view_sale_block <?= ($arQuantityData["HTML"] ? '' : 'wq'); ?>">
											<div class="count_d_block">
												<span class="active_to hidden"><?= $arDiscount["ACTIVE_TO"]; ?></span>
												<div class="title"><?= GetMessage("UNTIL_AKC"); ?></div>
												<span class="countdown values"><span class="item"></span><span
															class="item"></span><span class="item"></span><span
															class="item"></span></span>
											</div>
											<?php if ($arQuantityData["HTML"]):?>
												<div class="quantity_block">
													<div class="title"><?= GetMessage("TITLE_QUANTITY_BLOCK"); ?></div>
													<div class="values">
															<span class="item">
																<span class="value"><?= $totalCount; ?></span>
																<span class="text"><?= GetMessage("TITLE_QUANTITY"); ?></span>
															</span>
													</div>
												</div>
											<?php endif; ?>
										</div>
									<?php } ?>
								<?php else: ?>
									<?php $arDiscounts = CCatalogDiscount::GetDiscountByProduct($item_id, $arUserGroups, "N", array(), SITE_ID);
									$arDiscount = array();
									if ($arDiscounts)
										$arDiscount = current($arDiscounts);
									?>
									<div class="view_sale_block <?= ($arQuantityData["HTML"] ? '' : 'wq'); ?>" <?= ($arDiscount["ACTIVE_TO"] ? '' : 'style="display:none;"'); ?> >
										<div class="count_d_block">
											<span class="active_to hidden"><?= ($arDiscount["ACTIVE_TO"] ? $arDiscount["ACTIVE_TO"] : ""); ?></span>
											<div class="title"><?= GetMessage("UNTIL_AKC"); ?></div>
											<span class="countdown values"><span class="item"></span><span
														class="item"></span><span class="item"></span><span
														class="item"></span></span>
										</div>
										<?php if ($arQuantityData["HTML"]): ?>
											<div class="quantity_block">
												<div class="title"><?= GetMessage("TITLE_QUANTITY_BLOCK"); ?></div>
												<div class="values">
														<span class="item">
															<span class="value"><?= $totalCount; ?></span>
															<span class="text"><?= GetMessage("TITLE_QUANTITY"); ?></span>
														</span>
												</div>
											</div>
										<?php endif; ?>
									</div>
								<?php endif; ?>
							<?php } ?>
						</div>

						<div class="footer_button <?= ($arItem["OFFERS"] && $arItem['OFFERS_PROP'] ? 'has_offer_prop' : ''); ?> inner_content js_offers__<?= $arItem['ID']; ?>">
							<div class="sku_props">
								<?php if ($arItem["OFFERS"]) { ?>
									<?php if (!empty($arItem['OFFERS_PROP'])) { ?>
										<div class="bx_catalog_item_scu wrapper_sku"
											 id="<?php echo $arItemIDs["ALL_ITEM_IDS"]['PROP_DIV']; ?>"
											 data-site_id="<?= SITE_ID; ?>" data-id="<?= $arItem["ID"]; ?>"
											 data-offer_id="<?= $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["ID"]; ?>"
											 data-propertyid="<?= $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PROPERTIES"]["CML2_LINK"]["ID"]; ?>"
											 data-offer_iblockid="<?= $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["IBLOCK_ID"]; ?>">
											<?php $arSkuTemplate = array(); ?>
											<?php $arSkuTemplate = CNext::GetSKUPropsArray($arItem['OFFERS_PROPS_JS'], $arResult["SKU_IBLOCK_ID"], $arParams["DISPLAY_TYPE"], $arParams["OFFER_HIDE_NAME_PROPS"], "N", $arItem, $arParams['OFFER_SHOW_PREVIEW_PICTURE_PROPS']); ?>
											<?php foreach ($arSkuTemplate as $code => $strTemplate)
											{
												if (!isset($arItem['OFFERS_PROP'][$code]))
													continue;
												echo '<div class="item_wrapper">', str_replace('#ITEM#_prop_', $arItemIDs["ALL_ITEM_IDS"]['PROP'], $strTemplate), '</div>';
											} ?>
										</div>
										<?php $arItemJSParams = CNext::GetSKUJSParams($arResult, $arParams, $arItem); ?>
									<?php } ?>
								<?php } ?>
							</div>
							<?php if ((!$arItem["OFFERS"] || $arParams['TYPE_SKU'] !== 'TYPE_1') && !isset($arItem["SET"])): ?>
								<div class="counter_wrapp <?= ($arItem["OFFERS"] && $arParams["TYPE_SKU"] == "TYPE_1" ? 'woffers' : '') ?>">
									<?php if (($arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] && $arAddToBasketData["ACTION"] == "ADD") && $arAddToBasketData["CAN_BUY"]): ?>
										<div class="counter_block <?=$totalCount ? '' : 'disabled'?>" data-offers="<?= ($arItem["OFFERS"] ? "Y" : "N"); ?>"
											 data-item="<?= $arItem["ID"]; ?>">
											<span class="minus  <?=$totalCount ? '' : 'disabled'?>"
												  id="<?php echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY_DOWN']; ?>">-</span>
											<input type="text" class="text"
												   id="<?php echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY']; ?>"
												   name="<?php echo $arParams["PRODUCT_QUANTITY_VARIABLE"]; ?>"
												   value="<?= $arAddToBasketData["MIN_QUANTITY_BUY"] ?>"/>
											<span class="plus <?=$totalCount ? '' : 'disabled'?>"
												  id="<?php echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY_UP']; ?>" <?= ($arAddToBasketData["MAX_QUANTITY_BUY"] ? "data-max='" . $arAddToBasketData["MAX_QUANTITY_BUY"] . "'" : "") ?>>+</span>
										</div>
									<?php endif; ?>
									<div id="<?= $arItemIDs["ALL_ITEM_IDS"]['BASKET_ACTIONS']; ?>"
										 class="button_block <?= (($arAddToBasketData["ACTION"] == "ORDER") || !$arAddToBasketData["CAN_BUY"] || !$arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] || $arAddToBasketData["ACTION"] == "SUBSCRIBE" ? "wide" : ""); ?>">
										<?= $arAddToBasketData["HTML"] ?>
									</div>
								</div>
							<?php
							if (isset($arItem['PRICE_MATRIX']) && $arItem['PRICE_MATRIX']) // USE_PRICE_COUNT
							{
							?>
							<?php if ($arItem['ITEM_PRICE_MODE'] == 'Q' && count($arItem['PRICE_MATRIX']['ROWS']) > 1): ?>
							<?php $arOnlyItemJSParams = array(
								"ITEM_PRICES" => $arItem["ITEM_PRICES"],
								"ITEM_PRICE_MODE" => $arItem["ITEM_PRICE_MODE"],
								"ITEM_QUANTITY_RANGES" => $arItem["ITEM_QUANTITY_RANGES"],
								"MIN_QUANTITY_BUY" => $arAddToBasketData["MIN_QUANTITY_BUY"],
								"SHOW_DISCOUNT_PERCENT_NUMBER" => $arParams["SHOW_DISCOUNT_PERCENT_NUMBER"],
								"ID" => $arItemIDs["strMainID"],
							) ?>
								<script type="text/javascript">
									var <?php echo $arItemIDs["strObName"]; ?>el = new JCCatalogSectionOnlyElement(<?php echo CUtil::PhpToJSObject($arOnlyItemJSParams, false, true); ?>);
								</script>
							<?php endif;
							?>
							<?php } ?>
							<?php elseif ($arItem["OFFERS"]): ?>
							<?php if (empty($arItem['OFFERS_PROP'])){ ?>
								<div class="offer_buy_block buys_wrapp woffers">
									<?php
									$arItem["OFFERS_MORE"] = "Y";
									$arAddToBasketData = CNext::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'small read_more1', $arParams); ?>
									<?= $arAddToBasketData["HTML"] ?>
									<!--/noindex-->
								</div>
							<?php }else{ ?>
								<div class="offer_buy_block">
									<?php
									$arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['IS_OFFER'] = 'Y';
									$arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['IBLOCK_ID'] = $arParams['IBLOCK_ID'];
									$arAddToBasketData = CNext::GetAddToBasketArray($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]], $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'small', $arParams);
									?>
									<div class="counter_wrapp">
										<?php if (($arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] && $arAddToBasketData["ACTION"] == "ADD") && $arAddToBasketData["CAN_BUY"]): ?>
											<div class="counter_block"
												 data-item="<?= $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["ID"]; ?>">
												<span class="minus"
													  id="<?php echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY_DOWN']; ?>">-</span>
												<input type="text" class="text"
													   id="<?php echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY']; ?>"
													   name="<?php echo $arParams["PRODUCT_QUANTITY_VARIABLE"]; ?>"
													   value="<?= $arAddToBasketData["MIN_QUANTITY_BUY"] ?>"/>
												<span class="plus"
													  id="<?php echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY_UP']; ?>" <?= ($arAddToBasketData["MAX_QUANTITY_BUY"] ? "data-max='" . $arAddToBasketData["MAX_QUANTITY_BUY"] . "'" : "") ?>>+</span>
											</div>
										<?php endif; ?>
										<div id="<?= $arItemIDs["ALL_ITEM_IDS"]['BASKET_ACTIONS']; ?>"
											 class="button_block <?= (($arAddToBasketData["ACTION"] == "ORDER") || !$arAddToBasketData["CAN_BUY"] || !$arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] || $arAddToBasketData["ACTION"] == "SUBSCRIBE" ? "wide" : ""); ?>">
											<?= $arAddToBasketData["HTML"] ?>
										</div>
									</div>
								</div>
							<?php
							if (isset($arCurrentSKU['PRICE_MATRIX']) && $arCurrentSKU['PRICE_MATRIX']) // USE_PRICE_COUNT
							{
							?>
							<?php if ($arCurrentSKU['ITEM_PRICE_MODE'] == 'Q' && count($arCurrentSKU['PRICE_MATRIX']['ROWS']) > 1): ?>
							<?php $arOnlyItemJSParams = array(
								"ITEM_PRICES" => $arCurrentSKU["ITEM_PRICES"],
								"ITEM_PRICE_MODE" => $arCurrentSKU["ITEM_PRICE_MODE"],
								"ITEM_QUANTITY_RANGES" => $arCurrentSKU["ITEM_QUANTITY_RANGES"],
								"MIN_QUANTITY_BUY" => $arAddToBasketData["MIN_QUANTITY_BUY"],
								"SHOW_DISCOUNT_PERCENT_NUMBER" => $arParams["SHOW_DISCOUNT_PERCENT_NUMBER"],
								"ID" => $arItemIDs["strMainID"],
								"NOT_SHOW" => "Y",
							) ?>
								<script type="text/javascript">
									var <?php echo $arItemIDs["strObName"]; ?>el = new JCCatalogSectionOnlyElement(<?php echo CUtil::PhpToJSObject($arOnlyItemJSParams, false, true); ?>);
								</script>
							<?php endif;
							?>
							<?php } ?>
							<?php } ?>
								<div class="counter_wrapp ce_cmp_visible">
									<div id="<?= $arItemIDs["ALL_ITEM_IDS"]['BASKET_ACTIONS']; ?>"
										 class="button_block wide">
										<a class="btn btn-default basket read_more" rel="nofollow"
										   data-item="<?= $arItem['ID'] ?>"
										   href="<?= $arItem['DETAIL_PAGE_URL'] ?>"><?= GetMessage('CATALOG_READ_MORE') ?></a>
									</div>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	 <?php } ?>
	 <?php if (($arParams["AJAX_REQUEST"] == "N") || !isset($arParams["AJAX_REQUEST"])) { ?>
		</div>
		</div>
	 <?php } ?>
	 <?php if ($arParams["AJAX_REQUEST"] == "Y") { ?>
		<div class="wrap_nav">
	 <?php } ?>
	<div class="bottom_nav <?= $arParams["DISPLAY_TYPE"]; ?>" <?= ($arParams["AJAX_REQUEST"] == "Y" ? "style='display: none; '" : ""); ?>>
		<?php if ($arParams["DISPLAY_BOTTOM_PAGER"] == "Y") { ?><?= $arResult["NAV_STRING"] ?><?php } ?>
	</div>
	 <?php if ($arParams["AJAX_REQUEST"] == "Y") { ?>
		</div>
	 <?php } ?>
 <?php } else { ?>
	<script>
		$('.sort_header').animate({'opacity': '1'}, 500);
	</script>
	<div class="no_goods catalog_block_view">
		<div class="no_products">
			<div class="wrap_text_empty">
				<?php if ($_REQUEST["set_filter"]) { ?>
					<?php $APPLICATION->IncludeFile(SITE_DIR . "include/section_no_products_filter.php", array(), array("MODE" => "html", "NAME" => GetMessage('EMPTY_CATALOG_DESCR'))); ?>
				<?php } else { ?>
					<?php $APPLICATION->IncludeFile(SITE_DIR . "include/section_no_products.php", array(), array("MODE" => "html", "NAME" => GetMessage('EMPTY_CATALOG_DESCR'))); ?>
				<?php } ?>
			</div>
		</div>
		<?php if ($_REQUEST["set_filter"]) { ?>
			<span class="button wide btn btn-default"><?= GetMessage('RESET_FILTERS'); ?></span>
		<?php } ?>
	</div>
 <?php } ?>

<script>
	BX.message({
		QUANTITY_AVAILIABLE: '<?php echo COption::GetOptionString("aspro.next", "EXPRESSION_FOR_EXISTS", GetMessage("EXPRESSION_FOR_EXISTS_DEFAULT"), SITE_ID); ?>',
		QUANTITY_NOT_AVAILIABLE: '<?php echo COption::GetOptionString("aspro.next", "EXPRESSION_FOR_NOTEXISTS", GetMessage("EXPRESSION_FOR_NOTEXISTS"), SITE_ID); ?>',
		ADD_ERROR_BASKET: '<?php echo GetMessage("ADD_ERROR_BASKET"); ?>',
		ADD_ERROR_COMPARE: '<?php echo GetMessage("ADD_ERROR_COMPARE"); ?>',
	})
	sliceItemBlock();
</script>
