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

	<div class="d-flex mt-5">
		<section class="w-20">
			<?php if ($arParams["USE_FILTER"] == "Y"): ?>
				<div id="filter" class="w-75 mt-5 mr-3 float-right">

					<span id="filter-title"><?= Loc::getMessage("FILTER_TITLE") ?></span>

					<?php foreach ($arParams["FILTER_VALUES"] as $arFilterField): ?>

						<div class="filter-block">

							<div class="filter-item-title-block d-flex justify-content-space-between cursor-pointer">
								<span class="filter-block-title ml-5"><?= $arResult["MAP_FIELDS"][$arFilterField] ?></span>
								<span class="w-10 down-icon rotated"></span>
							</div>

							<div class="filter-block-inner" style="">

								<?php if($arFilterField == "UF_GOROD"):?>
									<input id="city-filter-search" type="text">
								<?php endif;?>

								<button class="reset-filter-block-btn btn btn-default white grey w-100 mt-2"
										role="button"
										data-filter-field="<?= $arFilterField ?>"><?= Loc::getMessage("RESET_FILTER_BLOCK_BTN") ?></button>

								<?php foreach ($arResult["MAP_FILTER_VALUES"][$arFilterField] as $value => $arFilterItem): ?>

									<?php if (strlen($value) == ""): ?>
										<?php continue; ?>
									<?php endif; ?>

									<?php
									$strIds = implode(",", array_column($arFilterItem, "ID"));
									$checkboxId = md5($value);
									?>

									<div class="filter-value-block">
										<label class="cursor-pointer d-flex w-100" for="<?= $checkboxId ?>">
											<label class="cursor-pointer"
												   for="<?= $checkboxId ?>"></label>
											<input id="<?= $checkboxId ?>"
												   type="radio"
												   class="cursor-pointer"
												   data-filter-field="<?= $arFilterField ?>"
												   data-filter-items="<?= $strIds ?>"
												   name="<?=$arFilterField?>">
											<span class="filter-value-item ml-2"><?= $value ?></span>
										</label>
									</div>

								<?php endforeach; ?>
							</div>
						</div>

					<?php endforeach; ?>
					<button id="submit-filter-btn"
							class="btn btn-default w-100"
							role="button"><?= Loc::getMessage("SUBMIT_FILTER_BTN") ?></button>
					<button id="reset-filter-btn"
							class="btn btn-default white grey w-100"
							role="button"><?= Loc::getMessage("RESET_FILTER_BTN") ?></button>
				</div>
			<?php endif; ?>
		</section>
		<section class="w-80">
			<?php if ($arParams["USE_MAP"] === "Y"): ?>
				<div id="<?= $arResult["MAP_ID"] ?>" class="map-block w-85 ml-5 mt-5"></div>
			<?php endif; ?>

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

						<?php if ($arItem["KONTRAGENTS"]): ?>

							<?php
							$arPhones = [];
							$arAddresses = [];
							$arEmails = [];

							foreach ($arItem["KONTRAGENTS"] as $arKontragent) {
								if ($arKontragent["UF_TELEFON"]) {
									$arPhones[] = $arKontragent["UF_TELEFON"];
								}

								if ($arKontragent["UF_YURIDICHESKIYADRE"]) {
									$arAddresses[] = $arKontragent["UF_YURIDICHESKIYADRE"];
								}

								if ($arKontragent["UF_ELEKTRONNAYAPOCHT"]) {
									$arEmails[] = $arKontragent["UF_ELEKTRONNAYAPOCHT"];
								}
							}
							?>

							<?php if ($arPhones): ?>
								<div class="item-field-block">
									<div class="d-flex">
										<span class="ml-3 item-field-name"><?= Loc::getMessage("PHONE") ?>:</span>
										<ul class="ml-1">
											<?php foreach ($arPhones as $phone): ?>
												<li><?= $phone ?></li>
											<?php endforeach; ?>
										</ul>
									</div>
								</div>
							<?php endif; ?>

							<?php if ($arAddresses): ?>
								<div class="item-field-block">
									<div class="d-flex">
										<span class="ml-3 item-field-name"><?= Loc::getMessage("ADDRESS") ?>:</span>
										<ul class="ml-1">
											<?php foreach ($arAddresses as $address): ?>
												<li><?= $address ?></li>
											<?php endforeach; ?>
										</ul>
									</div>
								</div>
							<?php endif; ?>

							<?php if ($arEmails): ?>
								<div class="item-field-block">
									<div class="d-flex">
										<span class="ml-3 item-field-name"><?= Loc::getMessage("EMAIL") ?>:</span>
										<ul class="ml-1">
											<?php foreach ($arEmails as $email): ?>
												<li><?= $email ?></li>
											<?php endforeach; ?>
										</ul>
									</div>
								</div>
							<?php endif; ?>

						<?php endif; ?>
						
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