<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('.set-composition_checkbox input.add_input_class').prop("checked", false);
			$('.set-composition_checkbox input.add_input_class.default').prop("checked", true);
		});

		$(document).ready(function() {


			$(".component_type,.set_group_items_link,.icon-popup,.include").fancybox({
				'titlePosition': 'inside',
				'transitionIn': 'none',
				'transitionOut': 'none'
			});
			var finish_toggle=0;
			$('.add_sub_item').click(function() {
				if ($(this).children().text() == 'Добавлено') {
					return true;
				}
				var group=$(this).children().data('group');
				var id=$(this).children().data('id');

				$('#add_input_' + id).click();
				$.fancybox.close();

				$('#set_group_' + group + ' .add_span').text('Добавить');
				$('#set_group_' + group + ' .add_span').parent().css({
					backgroundColor: "#107bb1",
					"border-color": "#107bb1"
				});

				if ($('#add_input_' + id).is(':checked') == true) {
					$(this).children().text('Добавлено');
					$(this).css({
						backgroundColor: "#fc3",
						"border-color": "#fc3"
					});

					finish_toggle=1;
				} else {
					if (finish_toggle == 0) {
						$(this).text('Добавить');
					} else {

					}
				}

			});


			var price=calculatePrice();
			if (price.price > 0) {
				showPrice(price);
			}


		});
	</script>

<? if (is_array($arResult["SET"])):
	?>

	<div style="    width: 100%;">
	<div class="set_new">
		<? if ($_GET['DEBUG'] == '1') {
			?><?
		} ?>
		<h3>Комплект из:</h3>
		<div class="set_new_container">


			<?
			function getPropValue($IBLOCK_ID, $ITEM_ID, $CODE)
			{
				$return;

				$db_props = CIBlockElement::GetProperty($IBLOCK_ID, $ITEM_ID, ["sort" => "asc"], ["CODE" => $CODE]);
				while ($ar_props = $db_props->Fetch()) {

					$VAL[] = $ar_props["VALUE_ENUM"];
				}

				return $VAL;
			}

			foreach ($arResult["SET"]["SET"] as $arItem) {
				$arItem["HIT"] = getPropValue(17, $arItem["ID"], "HIT");

				$db_res = CPrice::GetList(
					[],
					[
						"PRODUCT_ID" => $arItem['ID'],
						"CATALOG_GROUP_ID" => 14
					]
				);
				if ($ar_res = $db_res->Fetch()) {
					$arItem['PRICE'] = $ar_res["PRICE"];
				}
				$db_res = CPrice::GetList(
					[],
					[
						"PRODUCT_ID" => $arItem['ID'],
						"CATALOG_GROUP_ID" => 13
					]
				);
				if ($ar_res = $db_res->Fetch()) {
					$ar_old_price['PRICE'] = $ar_res["PRICE"];
				}
				if (!$arItem["PRICE"])
					continue;

				$vigoda = $arItem['PRICE'] - $ar_old_price["PRICE"];

				?>
				<div class="set_item_new set_item_base"
					 data-id="<?=$arItem['ID']?>"
					 data-amount="<?=$arItem['AMOUNT']?>"

					 data-price="<?=($vigoda != $arItem['PRICE']) ? $ar_res['PRICE'] : $arItem['PRICE']?>"
					 data-old-price="<?=isset($vigoda) ? $arItem['PRICE'] : $arItem['PRICE']?>"
					 data-discount="<?=$ar_res['PRICE']?>"
				>
					<div class="cont">
						<div class="product-preview-head product-preview-head--left">
							<span class="product-preview-status-btn product-preview-status-btn--added"></span>
						</div>
						<span class="set_item_base_img">
							<? if ($arItem['PREVIEW_PICTURE']) { ?>
								<img src="<?=$arItem['PREVIEW_PICTURE']?>">
							<? } else { ?>
								<img src="/bitrix/templates/cezares/images/no_photo_medium.png">
							<? } ?>
							<? if ($arItem['HIT']) {
								?>
								<div class="stickers">
							
								<?
								foreach ($arItem['HIT'] as $hit_val) {
									if ($hit_val == 'Хит') {
										echo '<div><div class="sticker_khit">Хит</div></div>';
									}
									if ($hit_val == 'Акция') {
										echo '<div><div class="sticker_aktsiya">Акция</div></div>';
									}
									if ($hit_val == 'Распродажа') {
										echo '<div><div class="sticker_rasprodazha">Распродажа</div></div>';
									}
									if ($hit_val == 'Новинка') {
										echo '<div><div class="sticker_novinka">Новинка</div></div>';
									}
									if(preg_match("[\d]+%", $hit_val)){
										echo '<div><div class="sticker_"' . trim($hit_val, "%") . '>' . $hit_val . '</div></div>';
									}
/*									if ($hit_val == '10%') {
										echo '<div><div class="sticker_10">10%</div></div>';
									}
									if ($hit_val == '5%') {
										echo '<div><div class="sticker_5">5%</div></div>';
									}
									if ($hit_val == '6%') {
										echo '<div><div class="sticker_6">6%</div></div>';
									}
									if ($hit_val == '7%') {
										echo '<div><div class="sticker_7">7%</div></div>';
									}
									if ($hit_val == '9%') {
										echo '<div><div class="sticker_9">9%</div></div>';
									}
									if ($hit_val == '15%') {
										echo '<div><div class="sticker_15">15%</div></div>';
									}
									if ($hit_val == '20%') {
										echo '<div><div class="sticker_20">20%</div></div>';
									}
									if ($hit_val == '25%') {
										echo '<div><div class="sticker_25">25%</div></div>';
									}
									if ($hit_val == '30%') {
										echo '<div><div class="sticker_30">30%</div></div>';
									}
									if ($hit_val == '40%') {
										echo '<div><div class="sticker_40">40%</div></div>';
									}
									if ($hit_val == '50%') {
										echo '<div><div class="sticker_50">50%</div></div>';
									}
									if ($hit_val == '58%') {
										echo '<div><div class="sticker_58">58%</div></div>';
									}
									if ($hit_val == '70%') {
										echo '<div><div class="sticker_70">70%</div></div>';
									}*/

								} ?>
							</div>
							<? } ?>
						</span>


						<? if ($vigoda and !($vigoda == $arItem['PRICE'])) {
							?>
							<table cellpadding="0" cellspacing="0">
								<tr>
									<td><span data-price="<?=$arItem['PRICE']?>"
											  class="set_item_base_price"><?=number_format($ar_res['PRICE'], 0, ',', ' ');?></span>
									</td>
									<td><span style="padding-left: 8px;font-size: 14px;"
											  data-old-price="<?=$arItem['PRICE']?>"
											  class="set_item_base_price"><s><?=number_format($arItem['PRICE'], 0, ',', ' ')?>&#160;₽</s></span>
									</td>
								</tr>
							</table>
						<? } else {
							?>
							<span data-price="<?=$arItem['PRICE']?>"
								  class="set_item_base_price"><?=number_format($arItem['PRICE'], 0, ',', ' ');?>&#160;₽</span>
							<br />
						<? } ?>
						<? $db_props = CIBlockElement::GetProperty(17, $arItem["ID"], ["sort" => "asc"], ["CODE" => "NAZNACHENIE"]);
						if ($ar_props = $db_props->Fetch())
							$FORUM_TOPIC_ID = IntVal($ar_props["VALUE"]);
						else
							$FORUM_TOPIC_ID = false;

						if (CModule::IncludeModule("iblock")):

							$property_enums = CIBlockPropertyEnum::GetList([
								"ID" => "ASC",
								"SORT" => "ASC"
							], [
								"IBLOCK_ID" => 17,
								"CODE" => "NAZNACHENIE"
							]);
							while ($enum_fields = $property_enums->GetNext()) {
								if ($enum_fields["ID"] == $FORUM_TOPIC_ID)
									$enum_fields["VALUE"];
							}
						endif;
						?>
						<a class="component_name"
						   href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a><br />
						<span class="component_article"><?=$arItem['ARTICLE']?></span>
						<div class="include_base">В комплекте</div>
					</div>
				</div>
			<? } ?>

			<? if (count($arResult["SET"]["OPTIONAL"])>0): ?>

			<?

			$set_group;
			$i = 1;
			foreach ($arResult["SET"]["OPTIONAL"] as $arItem) {
				$db_props = CIBlockElement::GetProperty(17, $arItem["ID"], ["sort" => "asc"], ["CODE" => "NAZNACHENIE"]);
				if ($ar_props = $db_props->Fetch())
					$FORUM_TOPIC_ID = IntVal($ar_props["VALUE"]);
				else
					$FORUM_TOPIC_ID = false;

				$arItem["HIT"] = getPropValue(17, $arItem["ID"], "HIT");

				if (CModule::IncludeModule("iblock")):

					$property_enums = CIBlockPropertyEnum::GetList([
						"ID" => "ASC",
						"SORT" => "ASC"
					], [
						"IBLOCK_ID" => 17,
						"CODE" => "NAZNACHENIE"
					]);
					while ($enum_fields = $property_enums->GetNext()) {
						if ($enum_fields["ID"] == $FORUM_TOPIC_ID) {
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
			if (!$arItem["DEFAULT"])
				continue;

			$db_res = CPrice::GetList(
				[],
				[
					"PRODUCT_ID" => $arItem['ID'],
					"CATALOG_GROUP_ID" => 13
				]
			);
			if ($ar_res = $db_res->Fetch()) {
				$arItem['PRICE'] = $ar_res["PRICE"];
			}
			$db_res = CPrice::GetList(
				[],
				[
					"PRODUCT_ID" => $arItem['ID'],
					"CATALOG_GROUP_ID" => 14
				]
			);
			if ($ar_res = $db_res->Fetch()) {
				$arItem['OLD_PRICE'] = $ar_res["PRICE"];
			}
			$ccc = count($arItems);
			$this_group_has_default_item = 0;
			?>
			<div class="set_item_new" id="set_item_new_<?=$i?>"
				 data-id="<?=$arItem['ID']?>"
				 data-amount="<?=$arItem['AMOUNT']?>"
				 data-price="<?=$arItem['PRICE']?>"
				 data-old-price="<?=isset($arItem['OLD_PRICE']) ? $arItem['OLD_PRICE'] : $arItem['PRICE']?>"
			>
				<div class="cont">
					<div class="product-preview-head product-preview-head--left">
						<?
						$is_in_group_default = 0;
						foreach ($arItems as $ar_SUB_Item) {
							?>
							<? if ($ar_SUB_Item["DEFAULT"]) {
								?>
								<span style="display: none" id="plus1_<?=$i?>"
									  class="icon-popup product-preview-status-btn product-preview-status-btn--not-added"
									  data-fancybox data-src="#set_group_<?=$i?>"
									  href="#set_group_<?=$i?>"></span>
								<span id="add1_<?=$i?>"
									  class="icon-popup product-preview-status-btn product-preview-status-btn--added"
									  data-fancybox data-src="#set_group_<?=$i?>"
									  href="#set_group_<?=$i?>"></span>
								<?
								$is_in_group_default = 1; ?>
								<?
							} ?>
							<?
						} ?>
						<? if ($is_in_group_default == 0) {
							?>
							<span id="plus1_<?=$i?>"
								  class="icon-popup product-preview-status-btn product-preview-status-btn--not-added"
								  data-fancybox data-src="#set_group_<?=$i?>" href="#set_group_<?=$i?>"></span>
							<span style="display: none" id="add1_<?=$i?>"
								  class="icon-popup product-preview-status-btn product-preview-status-btn--added"
								  data-fancybox data-src="#set_group_<?=$i?>" href="#set_group_<?=$i?>"></span>
							<?
						} ?>
					</div>
					<span id="set_item_base_img_<?=$i?>" class="set_item_base_img">
								<? if ($arItem['PREVIEW_PICTURE']): ?>
									<img src="<?=$arItem['PREVIEW_PICTURE']?>">
								<? else: ?>
									<img src="/bitrix/templates/cezares/images/no_photo_medium.png">
								<? endif ?>
						<? if ($arItem['HIT']) {
							?>
							<div class="stickers">
								
									<?
									foreach ($arItem['HIT'] as $hit_val) {
										if ($hit_val == 'Хит') {
											echo '<div><div class="sticker_khit">Хит</div></div>';
										}
										if ($hit_val == 'Акция') {
											echo '<div><div class="sticker_aktsiya">Акция</div></div>';
										}
										if ($hit_val == 'Распродажа') {
											echo '<div><div class="sticker_rasprodazha">Распродажа</div></div>';
										}
										if ($hit_val == 'Новинка') {
											echo '<div><div class="sticker_novinka">Новинка</div></div>';
										}
										if ($hit_val == '10%') {
											echo '<div><div class="sticker_10">10%</div></div>';
										}
										if ($hit_val == '5%') {
											echo '<div><div class="sticker_5">5%</div></div>';
										}
										if ($hit_val == '6%') {
											echo '<div><div class="sticker_6">6%</div></div>';
										}
										if ($hit_val == '7%') {
											echo '<div><div class="sticker_7">7%</div></div>';
										}
										if ($hit_val == '9%') {
											echo '<div><div class="sticker_9">9%</div></div>';
										}
										if ($hit_val == '15%') {
											echo '<div><div class="sticker_15">15%</div></div>';
										}
										if ($hit_val == '20%') {
											echo '<div><div class="sticker_20">20%</div></div>';
										}
										if ($hit_val == '25%') {
											echo '<div><div class="sticker_25">25%</div></div>';
										}
										if ($hit_val == '30%') {
											echo '<div><div class="sticker_30">30%</div></div>';
										}
										if ($hit_val == '40%') {
											echo '<div><div class="sticker_40">40%</div></div>';
										}
										if ($hit_val == '50%') {
											echo '<div><div class="sticker_50">50%</div></div>';
										}
										if ($hit_val == '58%') {
											echo '<div><div class="sticker_58">58%</div></div>';
										}
										if ($hit_val == '70%') {
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
					<? if ($vigoda and !($vigoda == $arItem['PRICE'])) {
						?>
						<table cellpadding="0" cellspacing="0">
							<tr>
								<td><span id="set_item_base_price_discount_<?=$i?>"
										  data-price="<?=$arItem['PRICE']?>"
										  class="set_item_base_price"><?=number_format($arItem['PRICE'], 0, ',', ' ');?>&#160;₽</span>
								</td>
								<td><span class="strike" id="set_item_base_price_<?=$i?>"
										  data-old-price="<?=$arItem['OLD_PRICE']?>"
										  class="set_item_base_price"><?=number_format($arItem['OLD_PRICE'], 0, ',', ' ');?>&#160;₽</span>
								</td>
							</tr>
						</table>
						<?
					} else {
						?>
						<span id="set_item_base_price_<?=$i?>" data-price="<?=$arItem['PRICE']?>"
							  class="set_item_base_price"><?=number_format($arItem['PRICE'], 0, ',', ' ');?>&#160;₽</span>
						<br />
						<?
					} ?>
					<?

					$db_props = CIBlockElement::GetProperty(17, $arItem["ID"], ["sort" => "asc"], ["CODE" => "NAZNACHENIE"]);
					if ($ar_props = $db_props->Fetch())
						$FORUM_TOPIC_ID = IntVal($ar_props["VALUE"]);
					else
						$FORUM_TOPIC_ID = false;

					if (CModule::IncludeModule("iblock")):

						$property_enums = CIBlockPropertyEnum::GetList([
							"ID" => "ASC",
							"SORT" => "ASC"
						], [
							"IBLOCK_ID" => 17,
							"CODE" => "NAZNACHENIE"
						]);
						while ($enum_fields = $property_enums->GetNext()) {
							if ($enum_fields["ID"] == $FORUM_TOPIC_ID) {
								$temp_name = $enum_fields["VALUE"];
							}
						}
					endif;

					?>

					<a id="set_item_base_name_<?=$i?>" class="component_name"
					   href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME'];?></a><br />
					<span id="set_item_base_article_<?=$i?>"
						  class="component_article"><?=$arItem['ARTICLE']?></span>
					<div class="include_base">В комплекте</div>
				</div>

				<?
				$i++;
				}
				}
				?>
				<script>
					var item_data='<?echo json_encode($set_group);?>';
				</script>


				<? endif; ?>
			</div>
		</div>
	</div>
<?
endif;
?>