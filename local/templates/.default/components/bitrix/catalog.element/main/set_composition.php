<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

?>
	<script type="text/javascript">
		$(document).ready(function ()
		{
			$('.set-composition_checkbox input.add_input_class').prop("checked", false);
			$('.set-composition_checkbox input.add_input_class.default').prop("checked", true);
		});

		$(document).ready(function ()
		{


			$(".component_type,.set_group_items_link,.icon-popup,.include").fancybox({
				'titlePosition': 'inside',
				'transitionIn': 'none',
				'transitionOut': 'none'
			});
			var finish_toggle = 0;
			$('.add_sub_item').click(function ()
			{
				if ($(this).children().text() == 'Добавлено')
				{
					return true;
				}
				var group = $(this).children().data('group');
				var id = $(this).children().data('id');

				$('#add_input_' + id).click();
				$.fancybox.close();

				$('#set_group_' + group + ' .add_span').text('Добавить');
				$('#set_group_' + group + ' .add_span').parent().css({
					backgroundColor: "#107bb1",
					"border-color": "#107bb1"
				});

				if ($('#add_input_' + id).is(':checked') == true)
				{
					$(this).children().text('Добавлено');
					$(this).css({
						backgroundColor: "#fc3",
						"border-color": "#fc3"
					});

					finish_toggle = 1;
				}
				else
				{
					if (finish_toggle == 0)
					{
						$(this).text('Добавить');
					}
					else
					{

					}
				}

			});

			var price = calculatePrice();
			if (price.price > 0)
			{
				showPrice(price);
			}


		});
	</script>

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

		.set_new_ul img
		{
			max-width: 220px;
		}

		.set_new
		{
			float: left;
			background: #fff;
			padding: 10px 0px;
			margin-bottom: 0px;
			height: 550px;
			width: max-content;
		}

		.set_new .stickers
		{
			right: 0px !important;
			left: 0px !important;
			top: 12px !important;
			text-align: right !important;
			padding-right: 6px !important;
		}

		.set_new .stickers [class*="sticker_"]
		{
			font-size: 9px !important;
		}

		.set_new .include, .set_new .include_base
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
			/*width: 19%;*/
			width: 267px;
			border-radius: 4px;
			margin: 4px;
			height: 410px;
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

		.plus1
		{
			cursor: pointer;
			position: absolute;
			z-index: 99;
			width: 30px;
			height: 30px;
			border-radius: 50%;
			border: 2px solid #fff;
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

		.strike
		{
			padding-left: 8px;
			font-size: 14px;
			text-decoration: line-through;
		}

		.dnt-strike
		{
			padding-left: 0px;
			font-size: 22px;
			text-decoration: none;
		}

		.flex-nav-prev, .flex-nav-next
		{
			display: none !important;

		}
	</style>

<? if (is_array($arResult["SET"])):
	?>

	<div style="    width: 100%;    overflow-x: scroll;">
		<div class="set_new">
			<? if ($_GET['DEBUG'] == '1')
			{
				?><?
			} ?>
			<h3>Комплект из:</h3>
			<?
			function getPropValue($IBLOCK_ID, $ITEM_ID, $CODE)
			{
				$return;

				$db_props = CIBlockElement::GetProperty($IBLOCK_ID, $ITEM_ID, array("sort" => "asc"), array("CODE" => $CODE));
				while ($ar_props = $db_props->Fetch())
				{

					$VAL[] = $ar_props["VALUE_ENUM"];
				}

				return $VAL;
			}

			foreach ($arResult["SET"]["SET"] as $arItem)
			{

				$arItem["HIT"] = getPropValue(17, $arItem["ID"], "HIT");

				$db_res = CPrice::GetList(
					array(),
					array(
						"PRODUCT_ID" => $arItem['ID'],
						"CATALOG_GROUP_ID" => 14
					)
				);
				if ($ar_res = $db_res->Fetch())
				{
					$arItem['PRICE'] = $ar_res["PRICE"];
				}
				$db_res = CPrice::GetList(
					array(),
					array(
						"PRODUCT_ID" => $arItem['ID'],
						"CATALOG_GROUP_ID" => 13
					)
				);
				if ($ar_res = $db_res->Fetch())
				{
					$ar_old_price['PRICE'] = $ar_res["PRICE"];
				}
				if (!$arItem["PRICE"]) continue;

				$vigoda = $arItem['PRICE'] - $ar_old_price["PRICE"];

				?>
				<div class="set_item_new set_item_base"
					 data-id="<?= $arItem['ID'] ?>"
					 data-amount="<?= $arItem['AMOUNT'] ?>"

					 data-price="<?= ($vigoda != $arItem['PRICE']) ? $ar_res['PRICE'] : $arItem['PRICE'] ?>"
					 data-old-price="<?= isset($vigoda) ? $arItem['PRICE'] : $arItem['PRICE'] ?>"
					 data-discount="<?= $ar_res['PRICE'] ?>"
				>
					<div class="cont">
						<div class="product-preview-head product-preview-head--left">
							<span class="product-preview-status-btn product-preview-status-btn--added"></span>
						</div>
						<span class="set_item_base_img">
							<? if ($arItem['PREVIEW_PICTURE']) { ?>
								<img src="<?= $arItem['PREVIEW_PICTURE'] ?>">
							<? } else { ?>
								<img src="/bitrix/templates/cezares/images/no_photo_medium.png">
							<? } ?>
							<? if ($arItem['HIT'])
							{
								?>
								<div class="stickers">
							
								<?
								foreach ($arItem['HIT'] as $hit_val)
								{
									if ($hit_val == 'Хит')
									{
										echo '<div><div class="sticker_khit">Хит</div></div>';
									}
									if ($hit_val == 'Акция')
									{
										echo '<div><div class="sticker_aktsiya">Акция</div></div>';
									}
									if ($hit_val == 'Распродажа')
									{
										echo '<div><div class="sticker_rasprodazha">Распродажа</div></div>';
									}
									if ($hit_val == 'Новинка')
									{
										echo '<div><div class="sticker_novinka">Новинка</div></div>';
									}
									if ($hit_val == '10%')
									{
										echo '<div><div class="sticker_10">10%</div></div>';
									}
									if ($hit_val == '5%')
									{
										echo '<div><div class="sticker_5">5%</div></div>';
									}
									if ($hit_val == '6%')
									{
										echo '<div><div class="sticker_6">6%</div></div>';
									}
									if ($hit_val == '7%')
									{
										echo '<div><div class="sticker_7">7%</div></div>';
									}
									if ($hit_val == '9%')
									{
										echo '<div><div class="sticker_9">9%</div></div>';
									}
									if ($hit_val == '15%')
									{
										echo '<div><div class="sticker_15">15%</div></div>';
									}
									if ($hit_val == '20%')
									{
										echo '<div><div class="sticker_20">20%</div></div>';
									}
									if ($hit_val == '25%')
									{
										echo '<div><div class="sticker_25">25%</div></div>';
									}
									if ($hit_val == '30%')
									{
										echo '<div><div class="sticker_30">30%</div></div>';
									}
									if ($hit_val == '40%')
									{
										echo '<div><div class="sticker_40">40%</div></div>';
									}
									if ($hit_val == '50%')
									{
										echo '<div><div class="sticker_50">50%</div></div>';
									}
									if ($hit_val == '58%')
									{
										echo '<div><div class="sticker_58">58%</div></div>';
									}
									if ($hit_val == '70%')
									{
										echo '<div><div class="sticker_70">70%</div></div>';
									}

								} ?>
							</div>
							<? } ?>
						</span>


						<? if ($vigoda and !($vigoda == $arItem['PRICE']))
						{
							?>
							<table cellpadding="0" cellspacing="0">
								<tr>
									<td><span data-price="<?= $arItem['PRICE'] ?>"
											  class="set_item_base_price"><?= number_format($ar_res['PRICE'], 0, ',', ' '); ?></span>
									</td>
									<td><span style="padding-left: 8px;font-size: 14px;"
											  data-old-price="<?= $arItem['PRICE'] ?>"
											  class="set_item_base_price"><s><?= number_format($arItem['PRICE'], 0, ',', ' ') ?>&#160;₽</s></span>
									</td>
								</tr>
							</table>
						<? }
						else
						{
							?>
							<span data-price="<?= $arItem['PRICE'] ?>"
								  class="set_item_base_price"><?= number_format($arItem['PRICE'], 0, ',', ' '); ?>&#160;₽</span>
							<br/>
						<? } ?>
						<br/>
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
							}
						endif;
						?>
						<br/>
						<a class="component_name"
						   href="<?= $arItem['DETAIL_PAGE_URL'] ?>"><?= $arItem['NAME'] ?></a><br/>
						<span class="component_article"><?= $arItem['ARTICLE'] ?></span>
						<div class="include_base">В комплекте</div>
					</div>
				</div>
			<? } ?>

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

					$arItem["HIT"] = getPropValue(17, $arItem["ID"], "HIT");


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
			foreach ($arItems

					 as $arItem)
			{
				if (!$arItem["DEFAULT"]) continue;

				$db_res = CPrice::GetList(
					array(),
					array(
						"PRODUCT_ID" => $arItem['ID'],
						"CATALOG_GROUP_ID" => 13
					)
				);
				if ($ar_res = $db_res->Fetch())
				{
					$arItem['PRICE'] = $ar_res["PRICE"];
				}
				$db_res = CPrice::GetList(
					array(),
					array(
						"PRODUCT_ID" => $arItem['ID'],
						"CATALOG_GROUP_ID" => 14
					)
				);
				if ($ar_res = $db_res->Fetch())
				{
					$arItem['OLD_PRICE'] = $ar_res["PRICE"];
				}
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
							<?
							$is_in_group_default = 0;
							foreach ($arItems as $ar_SUB_Item)
							{
								?>
								<? if ($ar_SUB_Item["DEFAULT"])
							{
								?>
								<span style="display: none" id="plus1_<?= $i ?>"
									  class="icon-popup product-preview-status-btn product-preview-status-btn--not-added"
									  data-fancybox data-src="#set_group_<?= $i ?>" href="#set_group_<?= $i ?>"></span>
								<span id="add1_<?= $i ?>"
									  class="icon-popup product-preview-status-btn product-preview-status-btn--added"
									  data-fancybox data-src="#set_group_<?= $i ?>" href="#set_group_<?= $i ?>"></span>
								<?
								$is_in_group_default = 1; ?>
								<?
							} ?>
								<?
							} ?>
							<? if ($is_in_group_default == 0)
							{
								?>
								<span id="plus1_<?= $i ?>"
									  class="icon-popup product-preview-status-btn product-preview-status-btn--not-added"
									  data-fancybox data-src="#set_group_<?= $i ?>" href="#set_group_<?= $i ?>"></span>
								<span style="display: none" id="add1_<?= $i ?>"
									  class="icon-popup product-preview-status-btn product-preview-status-btn--added"
									  data-fancybox data-src="#set_group_<?= $i ?>" href="#set_group_<?= $i ?>"></span>
								<?
							} ?>
						</div>
						<span id="set_item_base_img_<?= $i ?>" class="set_item_base_img">
								<? if ($arItem['PREVIEW_PICTURE']): ?>
									<img src="<?= $arItem['PREVIEW_PICTURE'] ?>">
								<? else: ?>
									<img src="/bitrix/templates/cezares/images/no_photo_medium.png">
								<? endif ?>
							<? if ($arItem['HIT'])
							{
								?>
								<div class="stickers">
								
									<?
									foreach ($arItem['HIT'] as $hit_val)
									{
										if ($hit_val == 'Хит')
										{
											echo '<div><div class="sticker_khit">Хит</div></div>';
										}
										if ($hit_val == 'Акция')
										{
											echo '<div><div class="sticker_aktsiya">Акция</div></div>';
										}
										if ($hit_val == 'Распродажа')
										{
											echo '<div><div class="sticker_rasprodazha">Распродажа</div></div>';
										}
										if ($hit_val == 'Новинка')
										{
											echo '<div><div class="sticker_novinka">Новинка</div></div>';
										}
										if ($hit_val == '10%')
										{
											echo '<div><div class="sticker_10">10%</div></div>';
										}
										if ($hit_val == '5%')
										{
											echo '<div><div class="sticker_5">5%</div></div>';
										}
										if ($hit_val == '6%')
										{
											echo '<div><div class="sticker_6">6%</div></div>';
										}
										if ($hit_val == '7%')
										{
											echo '<div><div class="sticker_7">7%</div></div>';
										}
										if ($hit_val == '9%')
										{
											echo '<div><div class="sticker_9">9%</div></div>';
										}
										if ($hit_val == '15%')
										{
											echo '<div><div class="sticker_15">15%</div></div>';
										}
										if ($hit_val == '20%')
										{
											echo '<div><div class="sticker_20">20%</div></div>';
										}
										if ($hit_val == '25%')
										{
											echo '<div><div class="sticker_25">25%</div></div>';
										}
										if ($hit_val == '30%')
										{
											echo '<div><div class="sticker_30">30%</div></div>';
										}
										if ($hit_val == '40%')
										{
											echo '<div><div class="sticker_40">40%</div></div>';
										}
										if ($hit_val == '50%')
										{
											echo '<div><div class="sticker_50">50%</div></div>';
										}
										if ($hit_val == '58%')
										{
											echo '<div><div class="sticker_58">58%</div></div>';
										}
										if ($hit_val == '70%')
										{
											echo '<div><div class="sticker_70">70%</div></div>';
										}

									} ?>
								</div>
								<?
							} ?>
							</span>

						<?
						$vigoda;

						$vigoda = $arItem["OLD_PRICE"] - $arItem['PRICE'];

						?>
						<? if ($vigoda and !($vigoda == $arItem['PRICE']))
						{
							?>
							<table cellpadding="0" cellspacing="0">
								<tr>
									<td><span id="set_item_base_price_discount_<?= $i ?>"
											  data-price="<?= $arItem['PRICE'] ?>"
											  class="set_item_base_price"><?= number_format($arItem['PRICE'], 0, ',', ' '); ?>&#160;₽</span>
									</td>
									<td><span class="strike" id="set_item_base_price_<?= $i ?>"
											  data-old-price="<?= $arItem['OLD_PRICE'] ?>"
											  class="set_item_base_price"><?= number_format($arItem['OLD_PRICE'], 0, ',', ' '); ?>&#160;₽</span>
									</td>
								</tr>
							</table>
							<?
						}
						else
						{
							?>
							<span id="set_item_base_price_<?= $i ?>" data-price="<?= $arItem['PRICE'] ?>"
								  class="set_item_base_price"><?= number_format($arItem['PRICE'], 0, ',', ' '); ?>&#160;₽</span>
							<br/>
							<?
						} ?>
						<br/>
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
						   href="<?= $arItem['DETAIL_PAGE_URL'] ?>"><?= $arItem['NAME']; ?></a><br/>
						<span id="set_item_base_article_<?= $i ?>"
							  class="component_article"><?= $arItem['ARTICLE'] ?></span>

						<div class="set_group_items" style="display:none;" id="set_group_<?= $i ?>" href="javascript:;">
							<div style="width:900px;height:800px;">
								<? $a2 = 0; ?>
								<? foreach ($arItems as $ar_SUB_Item)
								{
									?>
									<? if ($ar_SUB_Item['DEFAULT'])
								{
									$this_group_has_default_item = 1;
								} ?>
									<div class="sub_items">
										<table>
											<tr>
												<td style="width:300px">
													<div class="plus1">
													</div>


													<!--/*-----------------------------*/-->
													<?
													$vigoda = 0;
													$db_res = CPrice::GetList(
														array(),
														array(
															"PRODUCT_ID" => $ar_SUB_Item['ID'],
															"CATALOG_GROUP_ID" => 13
														)
													);
													if ($ar_res = $db_res->Fetch())
													{
														$ar_SUB_Item['PRICE_DISCOUNT'] = $ar_res["PRICE"];
													}
													$db_res = CPrice::GetList(
														array(),
														array(
															"PRODUCT_ID" => $ar_SUB_Item['ID'],
															"CATALOG_GROUP_ID" => 14
														)
													);
													if ($ar_res = $db_res->Fetch())
													{
														$ar_SUB_Item['OLD_PRICE'] = $ar_res["PRICE"];
														$vigoda = $ar_SUB_Item['OLD_PRICE'] - $ar_SUB_Item["PRICE_DISCOUNT"];
													}
													?>
													<div class="set-composition set-composition_group_<?= $i ?> plus1">
														<div
																class="set-composition_row" style="border: 0;"
																data-id="<?= $ar_SUB_Item['ID'] ?>"
																data-amount="<?= $ar_SUB_Item['AMOUNT'] ?>"


																data-vigoda="<?= $vigoda ?>"
																data-price="<?= ($vigoda != 0) ? number_format($ar_SUB_Item['PRICE_DISCOUNT'], 0, '', '') : number_format($ar_SUB_Item['OLD_PRICE'], 0, '', '') ?>"
																data-old-price="<?= isset($vigoda) ? number_format($ar_SUB_Item['OLD_PRICE'], 0, '', '') : number_format($ar_SUB_Item['PRICE_DISCOUNT'], 0, '', '') ?>"
																data-discount="<?= number_format($ar_res['PRICE'], 0, '', '') ?>"
														>
																<span class="set-composition_checkbox">
																	<input id="add_input_<?= $ar_SUB_Item['ID'] ?>"
																		   style="display:none"
																		   class="add_input_class <?= $ar_SUB_Item['DEFAULT'] ? 'default' : '' ?>"
																		   data-id="<?= $ar_SUB_Item['ID'] ?>"
																		   data-amount="1"
																		   data-price_discount="<?= number_format($ar_SUB_Item['PRICE_DISCOUNT'], 0, '', '') ?>"
																		   data-article="<?= $ar_SUB_Item['ARTICLE'] ?>"
																		   data-name="<?= $ar_SUB_Item['NAME'] ?>"
																		   data-price="<?= isset($vigoda) ? number_format($ar_SUB_Item['PRICE_DISCOUNT'], 0, '', '') : number_format($ar_SUB_Item['OLD_PRICE'], 0, '', '') ?>"
																		   data-old-price="<?= isset($vigoda) ? number_format($ar_SUB_Item['OLD_PRICE'], 0, '', '') : number_format($ar_SUB_Item['PRICE_DISCOUNT'], 0, '', '') ?>"
																		   data-img="<?= $ar_SUB_Item['PREVIEW_PICTURE'] ?>"
																		   data-group="<?= $i ?>"
																		   type="checkbox"<?= $ar_SUB_Item['DEFAULT'] ? ' checked="checked"' : '' ?>>
																</span>
														</div>
													</div>
													<!--/*-----------------------------*/-->
													<? if ($ar_SUB_Item['PREVIEW_PICTURE']):
														?>
														<ul class="set_new_ul" style="width: 220px;">
															<?
															if ($arResult["MORE_PHOTO"])
															{
																$bMagnifier = ($viewImgType == "MAGNIFIER"); ?>
																<li id="photo-0" class="current">
																	<a href="<?= ($viewImgType == "POPUP" ? $ar_SUB_Item["PREVIEW_PICTURE"] : "javascript:void(0)"); ?>" <?= ($bIsOneImage ? '' : 'data-fancybox-group="item_slider' . $a2 . '"') ?>
																	   class="<?= ($viewImgType == "POPUP" ? "popup_link fancy" : "line_link"); ?>"
																	   target="_blank">
																		<img src="<?= $ar_SUB_Item['PREVIEW_PICTURE'] ?>"/>
																		<div class="zoom"></div>
																	</a>
																<li>
																<?
																$aaa = 0;
																foreach ($arResult["MORE_PHOTO"] as $iii2 => $arImage)
																{

																	if ($iii2 && $bMagnifier):?>
																		<? continue; ?>
																	<? endif; ?>

																	<?
																	if (stristr($arImage["TITLE"], $ar_SUB_Item['ARTICLE']))
																	{
																	}
																	else
																	{
																		continue;
																	}
																	?>

																	<? $aaa++;
																	if ($aaa > 3) continue; ?>

																	<? $isEmpty = ($arImage["SMALL"]["src"] ? false : true); ?>
																	<?
																	$alt = $arImage["ALT"];
																	$title = $arImage["TITLE"];
																	?>
																	<li id="photo-<?= $iii2 + 1 ?>"
																		style="    width: 55px;float: left;margin: 4px;" <? //(!$iii2 ? 'class="current"' : 'style="display: none;"')
																	?>>
																		<? if (!$iii2): ?>
																			<link href="<?= ($isEmpty ? $arImage["BIG"]["src"] : $arImage["SRC"]); ?>"
																				  itemprop="image"/>
																		<? endif; ?>
																		<? if (!$isEmpty)
																		{
																			?>
																			<a href="<?= ($viewImgType == "POPUP" ? $arImage["BIG"]["src"] : "javascript:void(0)"); ?>" <?= ($bIsOneImage ? '' : 'data-fancybox-group="item_slider' . $a2 . '"') ?>
																			   class="<?= ($viewImgType == "POPUP" ? "popup_link fancy" : "line_link"); ?>"
																			   title="<?= $title; ?>">
																				<img width="55px"
																					 src="<?= $arImage["SMALL"]["src"] ?>" <?= ($viewImgType == "MAGNIFIER" ? "class='zoom_picture'" : ""); ?> <?= ($viewImgType == "MAGNIFIER" ? 'data-xoriginal=' . $arImage["BIG"]["src"] : ""); ?>
																					 alt="<?= $alt; ?>"
																					 title="<?= $title; ?>"/>
																				<div class="zoom"></div>
																			</a>
																		<? }
																		else
																		{
																			?>
																			<img width="200px"
																				 src="<?= $arImage["SRC"] ?>"
																				 alt="<?= $alt; ?>"
																				 title="<?= $title; ?>"/>
																		<? } ?>
																	</li>
																<? } ?>

																<div class="wrapp_thumbs xzoom-thumbs">
																	<div class="thumbs flexslider"
																		 data-plugin-options='{"animation": "slide", "selector": ".slides_block_<?= $a2 ?> > li", "directionNav": true, "itemMargin":10, "itemWidth": 54, "controlsContainer": ".thumbs_navigation", "controlNav" :false, "animationLoop": true, "slideshow": false}'
																		 style="max-width:<?= ceil(((count($arResult['MORE_PHOTO']) <= 4 ? count($arResult['MORE_PHOTO']) : 4) * 64) - 10) ?>px;">
																		<ul class="slides_block_<?= $a2 ?>" id="thumbs">
																			<? foreach ($arResult["MORE_PHOTO"] as $iii2 => $arImage): ?>

																				<?
																				if (stristr($arImage["TITLE"], $ar_SUB_Item['ARTICLE']))
																				{
																				}
																				else
																				{
																					continue;

																				}
																				?>
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

																while ($enum_fields1 = $property_enums->GetNext())
																{

																	if ($enum_fields1["ID"] == $FORUM_TOPIC_ID1)
																	{
																		echo "---" . $enum_fields1["VALUE"];
																	}
																}
															endif;
															?>
														</ul>
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

													<?
													if ($vigoda and $vigoda != 0)
													{
														?>
														<table cellpadding="0" cellspacing="0">
															<tr>
																<td><span id="set_item_base_price_discount_<?= $i ?>"
																		  data-price="<?= $ar_SUB_Item['PRICE_DISCOUNT'] ?>"
																		  class="set_item_base_price"><?= number_format($ar_SUB_Item['PRICE_DISCOUNT'], 0, ',', ' '); ?>&#160;₽</span>
																</td>
																<td><span class="strike"
																		  id="set_item_base_price_<?= $i ?>"
																		  data-old-price="<?= $ar_SUB_Item['OLD_PRICE'] ?>"
																		  class="set_item_base_price"><?= number_format($ar_SUB_Item['OLD_PRICE'], 0, ',', ' '); ?>&#160;₽</span>
																</td>
															</tr>
														</table>
														<?
													}
													else
													{
														?>
														<span id="set_item_base_price_<?= $i ?>"
															  data-price="<?= $ar_SUB_Item['OLD_PRICE'] ?>"
															  class="set_item_base_price"><?= number_format($ar_SUB_Item['OLD_PRICE'], 0, ',', ' '); ?>&#160;₽</span>
														<br/>
														<?
													} ?>



													<? if ($ar_SUB_Item["DEFAULT"])
													{
														?>
														<span class="btn btn-default add_sub_item"
															  style="background-color: rgb(255, 204, 51); border-color: rgb(255, 204, 51);">
														<span class="add_span" data-id="<?= $ar_SUB_Item['ID'] ?>"
															  data-group="<?= $i ?>"
															  id="add_span_<?= $ar_SUB_Item['ID'] ?>">Добавлено</span>
													</span>
														<?
													}
													else
													{
														?>
														<span class="btn btn-default add_sub_item">
														<span class="add_span" data-id="<?= $ar_SUB_Item['ID'] ?>"
															  data-group="<?= $i ?>"
															  id="add_span_<?= $ar_SUB_Item['ID'] ?>">Добавить</span>
													</span>
														<?
													} ?>
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
							<div id="include_a_<?= $i ?>" style=" cursor: pointer;" class="include" data-fancybox
								 data-src="#set_group_<?= $i ?>" href="#set_group_<?= $i ?>">В комплекте
							</div>
							<?
						}
						else
						{
							?>
							<div id="include_a_<?= $i ?>" style="display:none;" class="include" data-fancybox
								 data-src="#set_group_<?= $i ?>" href="#set_group_<?= $i ?>">В комплекте
							</div>
							<div id="include_<?= $i ?>" style="border: 1px solid #fc3;color: #fc3;    cursor: pointer;"
								 class="include" data-fancybox data-src="#set_group_<?= $i ?>"
								 href="#set_group_<?= $i ?>">Выбрать из <?= $ccc ?></div>
							<?

						}
						?>
					</div>
					<? if ($this_group_has_default_item)
					{
						?>
						<span style="margin-left:72px" class="epi2">
							<a class="set_group_items_link" data-fancybox data-src="#set_group_<?= $i ?>"
							   href="#set_group_<?= $i ?>">Выбрать другой <!--Вариантов <?= $ccc ?>--></a>
						</span>
						<?
					} ?>
				</div>

			<?
			$i++;
			}
			}
			?>
				<script>
					var item_data = '<?echo json_encode($set_group);?>';
				</script>


			<? endif; ?>

		</div>
	</div>
<?
endif;
