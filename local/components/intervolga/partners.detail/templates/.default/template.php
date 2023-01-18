<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @global $arResult
 * @global $arParams
 */

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

?>

	<div class="d-flex mt-5">
		<section class="w-20 ml-5">
			<a class="btn btn-default" href="<?=$arResult["BACK_URL"]?>"><?=Loc::getMessage("BACK")?></a>
		</section>
		<section class="w-80">
			<h1><?=$arResult["UF_NAME"]?></h1>
			<ul>
				<?php if($arResult["UF_GOROD"]):?>
					<li><span class="title"><?=Loc::getMessage("CITY")?>: </span>
						<span class="value ml-1"><?=$arResult["UF_GOROD"]?></span></li>
				<?php endif;?>

				<?php if($arResult["UF_METRO"]):?>
					<li><span class="title"><?=Loc::getMessage("METRO")?>: </span>
						<span class="value ml-1"><?=$arResult["UF_METRO"]?></span></li>
				<?php endif;?>

				<?php if($arResult["UF_VREMYARABOTY"]):?>
					<li><span class="title"><?=Loc::getMessage("WORKTIME")?>: </span>
						<span class="value ml-1"><?=$arResult["UF_VREMYARABOTY"]?></span></li>
				<?php endif;?>

			</ul>
				<?php if ($arResult["KONTRAGENTS"]){

					$arPhones = [];
					$arAddresses = [];
					$arEmails = [];

					foreach ($arResult["KONTRAGENTS"] as $arKontragent) {
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
				}

				?>

				<?php if ($arPhones): ?>
					<div class="item-field-block">
						<div class="d-flex">
							<span class="ml-3 item-field-name title"><?= Loc::getMessage("PHONE") ?>:</span>
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
							<span class="ml-3 item-field-name title"><?= Loc::getMessage("ADDRESS") ?>:</span>
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
							<span class="ml-3 item-field-name title"><?= Loc::getMessage("EMAIL") ?>:</span>
							<ul class="ml-1">
								<?php foreach ($arEmails as $email): ?>
									<li><?= $email ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
				<?php endif; ?>
		</section>
	</div>
<?php