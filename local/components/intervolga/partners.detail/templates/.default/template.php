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
			<a class="btn btn-default" href="<?= $arResult["BACK_URL"] ?>"><?= Loc::getMessage("BACK") ?></a>
		</section>
		<section class="w-80">
			<h1><?= $arResult["UF_NAME"] ?></h1>
			<ul>
				<?php if ($arResult["UF_GOROD"]): ?>
					<li><span class="title"><?= Loc::getMessage("CITY") ?>: </span>
						<span class="value ml-1"><?= $arResult["UF_GOROD"] ?></span></li>
				<?php endif; ?>

				<?php if ($arResult["UF_METRO"]): ?>
					<li><span class="title"><?= Loc::getMessage("METRO") ?>: </span>
						<span class="value ml-1"><?= $arResult["UF_METRO"] ?></span></li>
				<?php endif; ?>

				<?php if ($arResult["UF_VREMYARABOTY"]): ?>
					<li><span class="title"><?= Loc::getMessage("WORKTIME") ?>: </span>
						<span class="value ml-1"><?= $arResult["UF_VREMYARABOTY"] ?></span></li>
				<?php endif; ?>

			</ul>
			<?php if ($arResult["KONTRAGENTS"]) {

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

			<?php
				$isAutoScroll = $arParams["AUTO_SCROLL"] === "Y";
				$isMaxCarouselItems = count($arResult["PHOTOS"]) > 5;
			?>
				<div class="carousel shadow mb-5">

					<p>Фотографии магазина</p>

					<div class="carousel-buttons-block d-flex justify-content-center">
						<div class="carousel-button-left d-inline-block <?php if ($isAutoScroll || !$isMaxCarouselItems): ?>d-none<?php endif; ?>">
							<a href="#"></a></div>
						<div class="carousel-button-right d-inline-block ml-5 <?php if ($isAutoScroll || !$isMaxCarouselItems): ?>d-none<?php endif; ?>">
							<a href="#"></a></div>
					</div>

					<div class="carousel-wrapper">
						<div class="carousel-items">
							<?php foreach ($arResult["PHOTOS"] as $arPhoto):?>

								<div class="carousel-block cursor-pointer" onclick="showImgPopup(this);">
									<div style="
										background:url('<?=$arPhoto["SRC"]?>');
									"></div>
								</div>

							<?php endforeach;?>
						</div>
					</div>
				</div>


			<script type="text/javascript">

				BX.ready(function () {

					window.CarouselComponent.init({
						duration: <?=$arParams["DURATION"]?>,
						intervalDuration: <?=$arParams["INTERVAL_DURATION"]?>,
						autoScroll: !!<?=intval($isAutoScroll)?>,
						selectorRightBtn: ".carousel-button-right",
						selectorLeftBtn: ".carousel-button-left",
						selectorCarousel: ".carousel"
					});

					window.imgPopup = BX.PopupWindowManager.create("img-popup", null, {
						content: "",
						width: 500,
						height: 500,
						zIndex: 100,
						closeIcon: {
							// объект со стилями для иконки закрытия, при null - иконки не будет
							opacity: 1
						},
						titleBar: 'Фотография магазина',
						closeByEsc: true, // закрытие окна по esc
						darkMode: false, // окно будет светлым или темным
						autoHide: false, // закрытие при клике вне окна
						draggable: true, // можно двигать или нет
						resizable: true, // можно ресайзить
						min_height: 100, // минимальная высота окна
						min_width: 100, // минимальная ширина окна
						lightShadow: true, // использовать светлую тень у окна
						overlay: {
							// объект со стилями фона
							backgroundColor: 'black',
							opacity: 500
						},
						events: {
							onPopupShow: function() {
								// Событие при показе окна
							},
							onPopupClose: function() {
								// Событие при закрытии окна
							}
						}
					});
				});

				function showImgPopup(obj)
				{
					let popupContentNode = BX("popup-window-content-img-popup");

					if (popupContentNode)
					{
						window.imgPopup.show();
						let img = obj.querySelector("div");
						popupContentNode.style.background = img.style.background;
						popupContentNode.style.backgroundRepeat = "no-repeat";
						popupContentNode.style.backgroundPosition = "center";
						popupContentNode.style.backgroundSize = popupContentNode.offsetHeight+"px";
					}
				}

			</script>
		</section>
	</div>
<?php