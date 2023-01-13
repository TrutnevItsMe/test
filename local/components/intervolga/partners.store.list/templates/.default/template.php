<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

/**
 * @global $arResult
 * @global $arParams
 */

?>
	<div class="d-flex mt-5">
		<section class="w-20">
			<?php if ($arParams["USE_FILTER"] == "Y"): ?>
				<div id="filter" class="w-75 mt-5 mr-3 float-right">

					<span id="filter-title"><?= Loc::getMessage("FILTER_TITLE") ?></span>

					<?php foreach ($arParams["FILTER_VALUES"] as $arFilterField): ?>

						<span class="filter-block-title"><?= $arResult["MAP_FIELDS"][$arFilterField] ?></span>

						<?php foreach ($arResult["MAP_FILTER_VALUES"][$arFilterField] as $value => $arFilterItem): ?>

							<?php if (strlen($value) == ""): ?>
								<?php continue; ?>
							<?php endif; ?>

							<?
							$strIds = implode(",", array_column($arFilterItem, "ID"));
							$checkboxId = md5($value);
							?>
							<div class="filter-value-block">
								<label class="cursor-pointer d-flex w-100" for="<?= $checkboxId ?>">
									<label class="custom-checkbox cursor-pointer" for="<?= $checkboxId ?>"></label>
									<input id="<?= $checkboxId ?>"
										   type="checkbox"
										   class="cursor-pointer d-none"
										   data-filter-field="<?= $arFilterField ?>"
										   data-filter-items="<?= $strIds ?>">
									<span class="filter-value-item ml-2"><?= $value ?></span>
								</label>
							</div>

						<?php endforeach; ?>

					<?php endforeach; ?>
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

			<?php foreach ($arItemsId as $ID): ?>

				<?php if (!$ID) {
					continue;
				}
				?>

				<?php $arItem = $arResult["ITEMS"][$ID] ?>
				<div class="w-25 d-inline-block m-3 mt-5 ml-5 item-wrap cursor-pointer"
					 data-coordinates="<?= $arItem["UF_KOORDINATY"] ?>"
				>
					<div class="item-field-block ml-3"><a href=""><?= $arItem["UF_NAME"] ?></a></div>

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

		</section>
	</div>

<?php if ($arParams["USE_PAGINATION"] === "Y"): ?>
	<?php echo $arResult["NAV_STRING"]; ?>
<?php endif; ?>

<?php if ($arParams["USE_MAP"] === "Y"): ?>

	<?php

	$arBalloons = [];

	foreach ($arItemsId as $ID) {

		$arBalloon = [];

		if (!$ID) {
			continue;
		}

		$arItem = $arResult["ITEMS"][$ID];

		if (!$arItem["COORDINATES"]) {
			continue;
		}

		$arBalloon["x"] = floatval($arItem["COORDINATES"]["x"]);
		$arBalloon["y"] = floatval($arItem["COORDINATES"]["y"]);
		$arBalloon["hintContent"] = "";
		$arBalloon["balloonContent"] = "";

		foreach ($arItem["KONTRAGENTS"] as $arKontragent)
		{
			if ($arKontragent["UF_YURIDICHESKIYADRE"])
			{
				$arBalloon["hintContent"] .= $arKontragent["UF_YURIDICHESKIYADRE"];
				$arBalloon["balloonContent"] .= $arKontragent["UF_YURIDICHESKIYADRE"];
			}
		}

		$arBalloons[] = $arBalloon;
	}
	?>


	<script>
		BX.ready(function () {

			YandexMap.init(
				<?=$arResult["MAP_ID"]?>,
				<?=Bitrix\Main\Web\Json::encode(
						array_merge(
							$arParams,
							["BALLOONS" => $arBalloons]
						)
				)?>
			);

		});
	</script>
<?php endif; ?>

	<pre>
	<? var_dump($arParams) ?>
</pre>

	<pre>
	<? var_dump($arResult["ITEMS"][2]) ?>
</pre>
<?php