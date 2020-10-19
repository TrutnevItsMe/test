<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

if (is_array($arResult["SET"])):?>
	<div class="set-composition">
		<div class="set-composition_base">
			<h4 class="set-composition_header">Базовые элементы</h4>
			<? foreach ($arResult["SET"]["SET"] as $arItem): ?>
				<div
					class="set-composition_row"
					data-id="<?= $arItem['ID'] ?>"
					data-amount="<?= $arItem['AMOUNT'] ?>"
				>
			<span class="set-composition_checkbox">
				<input type="checkbox" checked disabled>
			</span>
					<span class="set-composition_picture">
				<? if ($arItem['PREVIEW_PICTURE']): ?>
					<img src="<?= $arItem['PREVIEW_PICTURE'] ?>">
				<? else: ?>
					<img src="/bitrix/templates/aspro_next/images/no_photo_medium.png">
				<? endif ?>
			</span>
					<span class="set-composition_title">
				<div class="set-composition_name">
					<a href="<?= $arItem['DETAIL_PAGE_URL'] ?>"><?= $arItem['NAME'] ?></a>
				</div>
				<div class="set-composition_article">Артикул: <?= $arItem['ARTICLE'] ?></div>
			</span>
					<span class="set-composition_price">
				<div class="set-composition_price__old">нет цены</div>
				<div class="set-composition_price__new"><?=
					number_format($arItem['PRICE'], 2, ',', '') ?> руб.</div>
			</span>
				</div>
			<? endforeach; ?>
		</div>
		<? if (count($arResult["SET"]["OPTIONAL"]) > 0): ?>
			<div class="set-composition_accesories">
				<h4 class="set-composition_header">Комплектующие (<?= count($arResult["SET"]["OPTIONAL"]) ?>)</h4>
				<? foreach ($arResult["SET"]["OPTIONAL"] as $arItem): ?>
					<div
						class="set-composition_row"
						data-id="<?= $arItem['ID'] ?>"
						data-amount="<?= $arItem['AMOUNT'] ?>"
					>
			<span class="set-composition_checkbox">
				<input type="checkbox"<?= $arItem['DEFAULT'] ? ' checked' : '' ?>>
			</span>
						<span class="set-composition_picture">
				<? if ($arItem['PREVIEW_PICTURE']): ?>
					<img src="<?= $arItem['PREVIEW_PICTURE'] ?>">
				<? else: ?>
					<img src="/bitrix/templates/aspro_next/images/no_photo_medium.png">
				<? endif ?>
			</span>
						<span class="set-composition_title">
				<div class="set-composition_name">
					<a href="<?= $arItem['DETAIL_PAGE_URL'] ?>"><?= $arItem['NAME'] ?></a>
				</div>
				<div class="set-composition_article">Артикул: <?= $arItem['ARTICLE'] ?></div>
			</span>
						<span class="set-composition_price">
				<div class="set-composition_price__old">нет цены</div>
				<div class="set-composition_price__new"><?=
					number_format($arItem['PRICE'], 2, ',', '') ?> руб.</div>
			</span>
					</div>
				<? endforeach; ?>
			</div>
		<? endif ?>
	</div>
<? endif;