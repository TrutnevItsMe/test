<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @global $arParams
 * @global $arResult
 * @global $component
 */

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if ($arParams["USE_PAGINATION"] === "Y")
{
	CJSCore::Init(["fx"]);
}

if ($component->getParent())
{
	$detailUrlTemplate = $component->getParent()->arParams["SEF_URL_TEMPLATES"]["detail"];
}
else
{
	$detailUrlTemplate = "";
}

?>

<div class="loader d-none"></div>

	<div class="d-flex mt-5 shops_regions">
		<section class="w-40" style="margin-left: 15px;">
			<?php if ($arParams["USE_FILTER"] == "Y"): ?>
				<div id="filter" class="w-75 mt-5 mr-3 float-right">

					<?/*<span id="filter-title"><?= Loc::getMessage("FILTER_TITLE") ?></span>*/?>

					<?php foreach ($arParams["FILTER_VALUES"] as $arFilterField): ?>
						<?//php if($arFilterField == "UF_GOROD") { ?>
						

						<div class="filter-block col-sm-6">
						 <?php if($arFilterField == "UF_GOROD") { ?>
						  <label>Город</label>
						 <?php } else { ?>
						  <label>Тип магазина</label>
						 <?php } ?>
							<?/*<div class="filter-item-title-block d-flex justify-content-space-between cursor-pointer">
								<span class="filter-block-title ml-5"><?= $arResult["MAP_FIELDS"][$arFilterField] ?></span>
								<span class="w-10 down-icon rotated"></span>
							</div>*/?>
							<div class="filter-block-inner" style="">

								<?php if($arFilterField == "UF_GOROD"):?>
									<input id="city-filter-search" placeholder="Поиск по городу" type="text" style="display:none">
								<?php endif;?>

								<?/*<button class="reset-filter-block-btn btn btn-default white grey w-100 mt-2"
										role="button"
										data-filter-field="<?= $arFilterField ?>"><?= Loc::getMessage("RESET_FILTER_BLOCK_BTN") ?></button>*/?>
								<select name="shops_region" class="shops_region">
								<option id="reset-filter-btn"
										class="btn btn-default white grey w-100"
										role="button">Показать все</option>
								<?php foreach ($arResult["MAP_FILTER_VALUES"][$arFilterField] as $value => $arFilterItem): ?>
									<?php if (strlen($value) == ""): ?>
										<?php continue; ?>
									<?php endif; ?>
									<?php
									$strIds = implode(",", array_column($arFilterItem, "ID"));
									$checkboxId = md5($value);
									?>
									<option class="filter-value-block" id="<?= $checkboxId ?>"
												   type="option"
												   class="cursor-pointer"
												   data-filter-field="<?= $arFilterField ?>"
												   data-filter-items="<?= $strIds ?>"
												   name="<?=$arFilterField?>">
											<span class="filter-value-item ml-2"><?= $value ?></span>
									</option>
								<?php endforeach; ?>
								</select>
							</div>
						</div>
						<?//php } ?>
					<?php endforeach; ?>
					<button id="submit-filter-btn"
							class="btn btn-default w-100"
							role="button"><?= Loc::getMessage("SUBMIT_FILTER_BTN") ?></button>
				</div>
			<?php endif; ?>
		<section class="magaz_items over_items">

			<?php if ($arParams["USE_PAGINATION"] === "Y") {
				$arItemsId = $arResult["PAGINATION_ELEMENT_IDS"];
			} else {
				$arItemsId = array_keys($arResult["ITEMS"]);
			}
			?>
			<div id="items">
				<?php foreach ($arItemsId as $ID): ?>

					<?php if (!$ID) {
						continue;
					}
					?>

					<?php $arItem = $arResult["ITEMS"][$ID] ?>
					<div class="w-25 d-inline-block m-3 mt-5 ml-5 item-wrap cursor-pointer"
						 data-coordinates="<?= $arItem["UF_KOORDINATY"] ?>"
					 style="width: 100% !important;">
						<?php
							$detailUrl = str_replace("#ELEMENT_ID#", $ID, $detailUrlTemplate) . "?";

							foreach ($_GET as $key => $value)
							{
								$detailUrl .= "&$key=$value";
							}

							$detailUrl .= "&backurl=" . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
						?>
					 
<div class="dropdown">
  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
   <?= $arItem["UF_NAME"] ?>
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
<svg style="margin-left: 5px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" class="m_map"><path d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"/></svg>
	<div class="drop_fields">
	<?php foreach ($arParams["DISPLAY_FIELDS"] as $field): ?>
		<?php if ($arItem[$field] != ""): ?>
			<div class="item-field-block dropdown-item">
				<span class="ml-3 item-field-name"><?= $arResult["MAP_FIELDS"][$field] ?>:</span>
				<span class="ml-1"><?= $arItem[$field] ?></span>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
    <a onclick="window.ElementComponent.clickDetailShop(this)" class="dropdown-item" href="<?=$detailUrl?>" style="text-align: right;padding-right: 10px;text-decoration: underline;">Подробнее</a>
	</div>
  </div>
</div>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" class="m_map"><path d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"/></svg>
						<?php foreach ($arParams["DISPLAY_FIELDS"] as $field): ?>
							<?php if ($arItem[$field] != ""): ?>
							 <?php if($arResult["MAP_FIELDS"][$field] == 'Город') { ?>
								<div class="item-field-block">
									<span class="ml-3 item-field-name"><?= $arResult["MAP_FIELDS"][$field] ?>:</span>
									<span class="ml-1"><?= $arItem[$field] ?></span>
								</div>
							 <?php } elseif($arResult["MAP_FIELDS"][$field] == 'Адрес') { ?>
								<div class="item-field-block">
									<span class="ml-3 item-field-name"><?= $arResult["MAP_FIELDS"][$field] ?>:</span>
									<span class="ml-1"><?= $arItem[$field] ?></span>
								</div>
							 <?php } ?>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
			</div>

		</section>
		</section>
		<section class="w-60 magaz_items">
			<?php if ($arParams["USE_MAP"] === "Y"): ?>
				<div id="<?= $arResult["MAP_ID"] ?>" class="map-block w-85 ml-5 mt-5"></div>
			<?php endif; ?>

			<?php if ($arParams["USE_PAGINATION"] === "Y") {
				$arItemsId = $arResult["PAGINATION_ELEMENT_IDS"];
			} else {
				$arItemsId = array_keys($arResult["ITEMS"]);
			}
			?>
			<div id="items" style="display:none">
				<?php foreach ($arItemsId as $ID): ?>

					<?php if (!$ID) {
						continue;
					}
					?>

					<?php $arItem = $arResult["ITEMS"][$ID] ?>
					<div class="w-25 d-inline-block m-3 mt-5 ml-5 item-wrap cursor-pointer"
						 data-coordinates="<?= $arItem["UF_KOORDINATY"] ?>"
					>
						<?php
							$detailUrl = str_replace("#ELEMENT_ID#", $ID, $detailUrlTemplate) . "?";

							foreach ($_GET as $key => $value)
							{
								$detailUrl .= "&$key=$value";
							}

							$detailUrl .= "&backurl=" . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
						?>

						<div class="item-field-block ml-3"><a onclick="window.ElementComponent.clickDetailShop(this)" href="<?=$detailUrl?>"><?= $arItem["UF_NAME"] ?></a></div>

						<?php foreach ($arParams["DISPLAY_FIELDS"] as $field): ?>
							<?php if ($arItem[$field] != ""): ?>
								<div class="item-field-block">
									<span class="ml-3 item-field-name"><?= $arResult["MAP_FIELDS"][$field] ?>:</span>
									<span class="ml-1"><?= $arItem[$field] ?></span>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
			</div>

		</section>
	</div>

<?php if ($arParams["USE_PAGINATION"] === "Y"): ?>
	<?php echo $arResult["NAV_STRING"]; ?>
<?php endif; ?>

<?php if ($arParams["USE_MAP"] === "Y"): ?>

	<script id="script-pagination">
		BX.ready(function () {
			window.PaginationComponent.init({
				result: <?=Bitrix\Main\Web\Json::encode($arResult)?>,
				params: <?=Bitrix\Main\Web\Json::encode($arParams)?>
			});
		});
	</script>

	<script>
		BX.ready(function () {

			BX.message({
				"WORKTIME": "<?=Loc::getMessage("WORKTIME")?>",
				"PHONE": "<?=Loc::getMessage("PHONE")?>",
				"EMAIL": "<?=Loc::getMessage("EMAIL")?>"
			});

			window.FilterComponent.init({
				result: <?=Bitrix\Main\Web\Json::encode($arResult)?>,
				params: <?=Bitrix\Main\Web\Json::encode($arParams)?>,
				detailUrlTemplate: "<?=$detailUrlTemplate?>"
			});

			let ids = window.FilterComponent.getIdsFromUrl();

			if (!ids)
			{
				ids = Object.keys(<?=Bitrix\Main\Web\Json::encode($arResult)?>["ITEMS"]);
			}

			let balloons = window.FilterComponent.getBalloons(ids);
			let params = <?=Bitrix\Main\Web\Json::encode($arParams)?>;
			params["BALLOONS"] = balloons;

			YandexMap.init(
				<?=$arResult["MAP_ID"]?>,
				params
			);

		});
	</script>
<?php endif; ?>

<?php