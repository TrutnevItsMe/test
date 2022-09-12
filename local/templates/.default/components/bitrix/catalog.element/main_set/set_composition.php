<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

?>

	<!--<sctipt src="/bitrix/templates/cezares/js/fancybox/dist/jquery.fancybox.js"></sctipt>
	<style src="/bitrix/templates/cezares/js/fancybox/dist/jquery.fancybox.css"></style>-->

	<script type="text/javascript">
		$(document).ready(function ()
		{
			$(".component_type,.set_group_items_link,.icon-popup").fancybox({
				//'modal' : true
				//'height'			: '75%',
				//'autoScale'     	: false,
				'titlePosition': 'inside',
				'transitionIn': 'none',
				'transitionOut': 'none'
			});

			$('.add_span').click(function ()
			{
				var group = $(this).data('group');
				var id = $(this).data('id');

				$('#add_input_' + id).click();
				if ($('#add_input_' + id).is(':checked') == true)
				{
					$('#set_group_' + group + ' .add_span').text('Добавить');
					$(this).text('Добавлено');
				}
				else
				{
					$('#set_group_' + group + ' .add_span').text('Добавить');
					$(this).text('Добавить');
				}

			});
		});
	</script>
	<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css">
	 <script src="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>-->

	<style>
		@media (min-width: 1200px)
		{
			.right_block.wide_, .right_block.wide_N
			{
				width: 100% !important;
			}

			.left_block
			{
				display: none;
			}
		}

		.set_new_ul li:before
		{
			content: "";
			color: #383838;
		}

		.set_new
		{
			float: left;
			background: #fff;
			padding: 10px 0px;
			margin-bottom: 55px;
			height: 424px;
			width: 100%;
		}

		.set_new .include
		{
			color: #81c822;
			padding: 4px 40px;
			border: #81c822 solid 1px;
			text-align: center;
			margin: 8px 23px;
			position: absolute;
			bottom: 0px;
		}

		}
		.set_new .epi2
		{
			color: #fc3;
			position: absolute;
			bottom: -20px;
		}

		.set_item_new
		{
			float: left;
			width: 19%;
			border-radius: 4px;
			margin: 4px;
			height: 370px;
			position: relative;
		}

		.set_item_new .cont
		{
			height: 165px;
			padding: 4px;
			border: 1px solid #fc3;
			height: 100%;
		}

		.set_item_base .cont
		{
			border: 1px solid #81c822;
		}

		.set_item_base_img
		{
			height: 180px;
			width: 100%;
			display: block;
			text-align: center;
		}

		.set_item_base_img img
		{
			height: 180px;
		}

		.set_item_base_price
		{
			font-size: 22px;
			font-weight: bold;
		}

		.component_name
		{
			color: #000;
		}

		.component_article
		{
			color: #5555556e;
			font-size: 12px;

		}

		.sub_items
		{
		}

		.plus1
		{
			cursor: pointer;
			position: absolute;
			z-index: 99;
			/*top: -2px;
			left: -2px;*/
			width: 30px;
			height: 30px;
			border-radius: 50%;
			border: 2px solid #fff;
			/*background: #fc3 url(/images/icon-plus.svg) no-repeat 50%/16px;*/

		}

		.product-preview-status-btn
		{
			display: flex;
			width: 100%;
			height: 100%;
			-webkit-box-pack: center;
			justify-content: center;
			-webkit-box-align: center;
			align-items: center;
			color: rgb(255, 255, 255);
			cursor: pointer;
			background-size: 14px;
			border-width: 2px;
			border-style: solid;
			border-color: rgb(255, 255, 255);
			border-image: initial;
			border-radius: 50%;
			background-repeat: no-repeat;
			background-position: 50% center;
		}

		.product-preview-head
		{
			display: flex;
			-webkit-box-pack: center;
			justify-content: center;
			-webkit-box-align: center;
			align-items: center;
			position: absolute;
			z-index: 4;
			top: 7px;
			width: 32px;
			height: 32px;
			left: 7px;
		}

		.product-preview-status-btn--not-added
		{
			background-color: rgb(255, 204, 51);
			background-image: url(/images/icon-plus.svg);
		}

		.product-preview-status-btn--added
		{
			background-color: rgb(129, 200, 34);
			background-image: url(/images/icon-check.svg);
		}
	</style>

<? if (is_array($arResult["SET"])): ?>
	<div class="set_new">
		<h3>Комплект из:</h3>
		<? foreach ($arResult["SET"]["SET"] as $arItem): ?>
			<div class="set_item_new set_item_base"
				 data-id="<?= $arItem['ID'] ?>"
				 data-amount="<?= $arItem['AMOUNT'] ?>"
				 data-price="<?= $arItem['PRICE'] ?>"
				 data-old-price="<?= isset($arItem['OLD_PRICE']) ? $arItem['OLD_PRICE'] : $arItem['PRICE'] ?>"
			>
				<div class="cont">
					<div class="product-preview-head product-preview-head--left">
						<span class="product-preview-status-btn product-preview-status-btn--added"></span>
					</div>
					<span class="set_item_base_img">
						<? if ($arItem['PREVIEW_PICTURE']): ?>
							<img src="<?= $arItem['PREVIEW_PICTURE'] ?>">
						<? else: ?>
							<img src="/bitrix/templates/cezares/images/no_photo_medium.png">
						<? endif ?>
					</span>
					<span data-price="<?= $arItem['PRICE'] ?>" class="set_item_base_price"><?= $arItem['PRICE'] ?>&#160;₽</span><br/>
					<!--<a class="component_type" href="javascript:;">-->
					<? $db_props = CIBlockElement::GetProperty(17, $arItem["ID"], array("sort" => "asc"), array("CODE" => "NAZNACHENIE"));
					if ($ar_props = $db_props->Fetch())
						$FORUM_TOPIC_ID = IntVal($ar_props["VALUE"]);
					else
						$FORUM_TOPIC_ID = false;

					if (CModule::IncludeModule("iblock")):

						$property_enums = CIBlockPropertyEnum::GetList(array("ID" => "ASC", "SORT" => "ASC"), array("IBLOCK_ID" => 17, "CODE" => "NAZNACHENIE"));
						while ($enum_fields = $property_enums->GetNext())
						{
							if ($enum_fields["ID"] == $FORUM_TOPIC_ID)
								$enum_fields["VALUE"];
							// echo $enum_fields["ID"]." - ".$enum_fields["VALUE"]."<br>";
						}
					endif;
					?>
					<!--</a>-->
					<br/>
					<a class="component_name"
					   href="<?= $arItem['DETAIL_PAGE_URL'] ?>"><?= $arItem['NAME']//mb_substr($arItem['NAME'],0,30)... ?></a><br/>
					<span class="component_article"><?= $arItem['ARTICLE'] ?></span>
					<div class="include">В комплекте</div>
				</div>
			</div>
		<? endforeach; ?>

		<? if (count($arResult["SET"]["OPTIONAL"]) > 0): ?>

			<?
			$set_group;
			$i = 1;
			foreach ($arResult["SET"]["OPTIONAL"] as $arItem)
			{
				$db_props = CIBlockElement::GetProperty(17, $arItem["ID"], array("sort" => "asc"), array("CODE" => "NAZNACHENIE"));
				if ($ar_props = $db_props->Fetch())
					$FORUM_TOPIC_ID = IntVal($ar_props["VALUE"]);
				else
					$FORUM_TOPIC_ID = false;

				if (CModule::IncludeModule("iblock")):

					$property_enums = CIBlockPropertyEnum::GetList(array("ID" => "ASC", "SORT" => "ASC"), array("IBLOCK_ID" => 17, "CODE" => "NAZNACHENIE"));

					while ($enum_fields = $property_enums->GetNext())
					{

						if ($enum_fields["ID"] == $FORUM_TOPIC_ID)
						{
							$set_group[$enum_fields["VALUE"]][] = $arItem;
						}
					}
				endif;
			}

			$i = 0;

		foreach ($set_group

				 as $arItems)
		{
			$arItem = $arItems[0];
			$ccc = count($arItems);
			$this_group_has_default_item = 0;
			?>
			<div class="set_item_new" id="set_item_new_<?= $i ?>"
				 data-id="<?= $arItem['ID'] ?>"
				 data-amount="<?= $arItem['AMOUNT'] ?>"
				 data-price="<?= $arItem['PRICE'] ?>"
				 data-old-price="<?= isset($arItem['OLD_PRICE']) ? $arItem['OLD_PRICE'] : $arItem['PRICE'] ?>"
			>
				<div class="cont">
					<div class="product-preview-head product-preview-head--left">
						<span id="plus1_<?= $i ?>"
							  class="icon-popup product-preview-status-btn product-preview-status-btn--not-added"
							  data-fancybox data-src="#set_group_<?= $i ?>" href="#set_group_<?= $i ?>"></span>
						<span style="display: none" id="add1_<?= $i ?>"
							  class="icon-popup product-preview-status-btn product-preview-status-btn--added"
							  data-fancybox data-src="#set_group_<?= $i ?>" href="#set_group_<?= $i ?>"></span>
					</div>
					<span id="set_item_base_img_<?= $i ?>" class="set_item_base_img">
						<? if ($arItem['PREVIEW_PICTURE']): ?>
							<img src="<?= $arItem['PREVIEW_PICTURE'] ?>">
						<? else: ?>
							<img src="/bitrix/templates/cezares/images/no_photo_medium.png">
						<? endif ?>
					</span>
					<span id="set_item_base_price_<?= $i ?>"
						  class="set_item_base_price"><?= $arItem['PRICE'] ?>&#160;₽</span><br/>
					<?


					$db_props = CIBlockElement::GetProperty(17, $arItem["ID"], array("sort" => "asc"), array("CODE" => "NAZNACHENIE"));
					if ($ar_props = $db_props->Fetch())
						$FORUM_TOPIC_ID = IntVal($ar_props["VALUE"]);
					else
						$FORUM_TOPIC_ID = false;

					if (CModule::IncludeModule("iblock")):

						$property_enums = CIBlockPropertyEnum::GetList(array("ID" => "ASC", "SORT" => "ASC"), array("IBLOCK_ID" => 17, "CODE" => "NAZNACHENIE"));
						while ($enum_fields = $property_enums->GetNext())
						{
							if ($enum_fields["ID"] == $FORUM_TOPIC_ID)
							{
								$temp_name = $enum_fields["VALUE"];
							}
						}
					endif;

					?>
					<br/>
					<a id="set_item_base_name_<?= $i ?>" class="component_name"
					   href="<?= $arItem['DETAIL_PAGE_URL'] ?>"><?= $arItem['NAME'];//=mb_substr($arItem['NAME'],0,30)... ?></a><br/>
					<span id="set_item_base_article_<?= $i ?>"
						  class="component_article"><?= $arItem['ARTICLE'] ?></span>

					<div class="set_group_items" style="display:none;" id="set_group_<?= $i ?>" href="javascript:;">
						<div style="width:900px;height:800px;">
							<? $a2 = 0; ?>
							<? foreach ($arItems as $ar_SUB_Item) {
								?>
								<? if ($ar_SUB_Item['DEFAULT']) {
									$this_group_has_default_item = 1;
								} ?>
								<div class="sub_items">
									<table>
										<tr>
											<td style="width:300px">
												<div class="plus1">
												</div>

												<!--/*-----------------------------*/-->
												<div class="set-composition set-composition_group_<?= $i ?> plus1">
													<div
															class="set-composition_row" style="border: 0;"
															data-id="<?= $ar_SUB_Item['ID'] ?>"
															data-amount="<?= $ar_SUB_Item['AMOUNT'] ?>"
															data-price="<?= $ar_SUB_Item['PRICE'] ?>"
															data-old-price="<?= isset($ar_SUB_Item['OLD_PRICE']) ? $ar_SUB_Item['OLD_PRICE'] : $ar_SUB_Item['PRICE'] ?>"
													>
														<span class="set-composition_checkbox">
															<input id="add_input_<?= $ar_SUB_Item['ID'] ?>"
																   data-article="<?= $ar_SUB_Item['ARTICLE'] ?>"
																   data-name="<?= $ar_SUB_Item['NAME'] ?>"
																   data-price="<?= $ar_SUB_Item['PRICE'] ?>"
																   data-img="<?= $ar_SUB_Item['PREVIEW_PICTURE'] ?>"
																   data-group="<?= $i ?>"
																   type="checkbox"<?= $ar_SUB_Item['DEFAULT'] ? ' checked' : '' ?>>
														</span>

														<!--<span class="set-composition_title">
															<div class="set-composition_name">
																<a href="<?= $ar_SUB_Item['DETAIL_PAGE_URL'] ?>"><?= $ar_SUB_Item['NAME'] ?></a>
															</div>
															<div class="set-composition_article">Артикул: <?= $ar_SUB_Item['ARTICLE'] ?></div>
															<? if ($ar_SUB_Item['AMOUNT'] > 1):?>
																<div class="set-composition_amount">В набор входит: <?= $ar_SUB_Item['AMOUNT'] ?> шт.</div>
															<?endif ?>
														</span>
														<span class="set-composition_price">
														<? if (isset($ar_SUB_Item['OLD_PRICE'])): ?>
															<div class="set-composition_price__old"><?=
															($ar_SUB_Item['AMOUNT'] > 1) ? $ar_SUB_Item['AMOUNT'] . ' x ' : '' ?><?=
															number_format(
																$ar_SUB_Item['OLD_PRICE'] * $ar_SUB_Item['AMOUNT'],
																2,
																',',
																'&nbsp;'
															) ?> руб.</div>
														<? endif ?>
															<div class="set-composition_price__new"><?=
														($ar_SUB_Item['AMOUNT'] > 1) ? $ar_SUB_Item['AMOUNT'] . ' x ' : '' ?><?=
														number_format(
															$ar_SUB_Item['PRICE'],
															2,
															',',
															'&nbsp;'
														) ?> руб.</div>
														</span>-->
													</div>
												</div>
												<!--/*-----------------------------*/-->
												<? if ($ar_SUB_Item['PREVIEW_PICTURE']): ?>
													<!--<a href="<?= $ar_SUB_Item["DETAIL_PAGE_URL"] ?>" target="_blank">
													<img src="<?= $ar_SUB_Item['PREVIEW_PICTURE'] ?>" />
												</a>-->
													<?
													if ($arResult["MORE_PHOTO"])
													{
														$bMagnifier = ($viewImgType == "MAGNIFIER"); ?>
														<ul class="set_new_ul">
															<? foreach ($arResult["MORE_PHOTO"] as $iii2 => $arImage)
															{
																if ($iii2 && $bMagnifier):?>
																	<? continue; ?>
																<? endif; ?>
																<? $isEmpty = ($arImage["SMALL"]["src"] ? false : true); ?>
																<?
																$alt = $arImage["ALT"];
																$title = $arImage["TITLE"];
																?>
																<li id="photo-<?= $iii2 ?>" <?= (!$iii2 ? 'class="current"' : 'style="display: none;"') ?>>
																	<? if (!$iii2):?>
																		<link href="<?= ($isEmpty ? $arImage["BIG"]["src"] : $arImage["SRC"]); ?>"
																			  itemprop="image"/>
																	<? endif; ?>
																	<? if (!$isEmpty) {
																		?>
																		<a href="<?= ($viewImgType == "POPUP" ? $arImage["BIG"]["src"] : "javascript:void(0)"); ?>" <?= ($bIsOneImage ? '' : 'data-fancybox-group="item_slider2"') ?>
																		   class="<?= ($viewImgType == "POPUP" ? "popup_link fancy" : "line_link"); ?>"
																		   title="<?= $title; ?>">
																			<img width="200px"
																				 src="<?= $arImage["SMALL"]["src"] ?>" <?= ($viewImgType == "MAGNIFIER" ? "class='zoom_picture'" : ""); ?> <?= ($viewImgType == "MAGNIFIER" ? 'data-xoriginal=' . $arImage["BIG"]["src"] : ""); ?>
																				 alt="<?= $alt; ?>"
																				 title="<?= $title; ?>"/>
																			<div class="zoom"></div>
																		</a>
																	<? } else {
																		?>
																		<img width="200px" src="<?= $arImage["SRC"] ?>"
																			 alt="<?= $alt; ?>" title="<?= $title; ?>"/>
																	<? } ?>
																</li>
															<? } ?>
														</ul>
														<div class="wrapp_thumbs xzoom-thumbs">
															<div class="thumbs flexslider"
																 data-plugin-options='{"animation": "slide", "selector": ".slides_block_<?= $a2 ?> > li", "directionNav": true, "itemMargin":10, "itemWidth": 54, "controlsContainer": ".thumbs_navigation", "controlNav" :false, "animationLoop": true, "slideshow": false}'
																 style="max-width:<?= ceil(((count($arResult['MORE_PHOTO']) <= 4 ? count($arResult['MORE_PHOTO']) : 4) * 64) - 10) ?>px;">
																<ul class="slides_block_<?= $a2 ?>" id="thumbs">
																	<? foreach ($arResult["MORE_PHOTO"] as $iii2 => $arImage):?>
																		<li <?= (!$iii2 ? 'class="current"' : '') ?>
																				data-big_img="<?= $arImage["BIG"]["src"] ?>"
																				data-small_img="<?= $arImage["SMALL"]["src"] ?>">
																			<span><img class="xzoom-gallery" width="50"
																					   data-xpreview="<?= $arImage["THUMB"]["src"]; ?>"
																					   src="<?= $arImage["THUMB"]["src"] ?>"
																					   alt="<?= $arImage["ALT"]; ?>"
																					   title="<?= $arImage["TITLE"]; ?>"/></span>
																		</li>
																	<? endforeach; ?>
																</ul>
																<span class="thumbs_navigation custom_flex"></span>
															</div>
														</div>
													<? } ?>
													<?
													$db_props = CIBlockElement::GetProperty(17, 11584, array("sort" => "asc"), array("CODE" => "MORE_PHOTO"));
													if ($ar_props1 = $db_props->Fetch())
														$FORUM_TOPIC_ID1 = IntVal($ar_props1["VALUE"]);
													else
														$FORUM_TOPIC_ID1 = false;

													if (CModule::IncludeModule("iblock")):

														$property_enums = CIBlockPropertyEnum::GetList(array("ID" => "ASC", "SORT" => "ASC"), array("IBLOCK_ID" => 17, "CODE" => "MORE_PHOTO"));
														echo $FORUM_TOPIC_ID1;
														while ($enum_fields1 = $property_enums->GetNext())
														{

															if ($enum_fields1["ID"] == $FORUM_TOPIC_ID1)
															{
																//$temp_name = $enum_fields["VALUE"];
																echo "---" . $enum_fields1["VALUE"];
															}
														}
													endif;
													?>

												<? else: ?>
													<img src="/bitrix/templates/cezares/images/no_photo_medium.png"/>
												<? endif ?>
											</td>
											<td style="width:450px">
												<a href="<?= $ar_SUB_Item["DETAIL_PAGE_URL"] ?>"
												   target="_blank"><b><?= $temp_name ?></b></a><br/>
												<?= $ar_SUB_Item['NAME'] ?><br/><br/>
												Артикул: <?= $ar_SUB_Item['ARTICLE'] ?>
											</td>
											<td style="width:200px">
												<b style="font-size:25px;"><?= $ar_SUB_Item['PRICE'] ?>&#160;₽</b><br/>
												<span class="btn btn-default add_sub_item">
												<span class="add_span" data-id="<?= $ar_SUB_Item['ID'] ?>"
													  data-group="<?= $i ?>" id="add_span_<?= $ar_SUB_Item['ID'] ?>">Добавить</span>
												
											</span>
											</td>
										</tr>
									</table>
								</div>
								<hr/>
								<?
								$a2++;
							} ?>
						</div>
					</div>
					<?
					if ($this_group_has_default_item)
					{
						?>
						<style>
							#set_item_new_<?=$i?> .cont
							{
								border: 1px solid #81c822;
							}
						</style>
						<div id="include_<?= $i ?>" class="include">В комплекте</div>
						<?
					}
					else
					{
						?>
						<div id="include_<?= $i ?>" style="display:none;" class="include">В комплекте</div>
						<?

					}
					?>
				</div>
				<span class="epi2">
					<a class="set_group_items_link" data-fancybox data-src="#set_group_<?= $i ?>"
					   href="#set_group_<?= $i ?>">Вариантов <?= $ccc ?></a>
				</span>
			</div>

		<?
		$i++;
		}
		?>
			<script>
				var item_data = '<?echo json_encode($set_group);?>';
			</script>
		<?endif; ?>

	</div>

<?
endif;
