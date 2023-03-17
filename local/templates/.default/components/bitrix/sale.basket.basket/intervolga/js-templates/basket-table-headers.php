<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use Bitrix\Main\Localization\Loc;

/**
 * @var array $mobileColumns
 * @var array $arParams
 * @var string $templateFolder
 */

$usePriceInAdditionalColumn = in_array('PRICE', $arParams['COLUMNS_LIST']) && $arParams['PRICE_DISPLAY_MODE'] === 'Y';
$useSumColumn = in_array('SUM', $arParams['COLUMNS_LIST']);
$useActionColumn = in_array('DELETE', $arParams['COLUMNS_LIST']);

$restoreColSpan = 2 + $usePriceInAdditionalColumn + $useSumColumn + $useActionColumn;

$positionClassMap = [
	'left' => 'basket-item-label-left',
	'center' => 'basket-item-label-center',
	'right' => 'basket-item-label-right',
	'bottom' => 'basket-item-label-bottom',
	'middle' => 'basket-item-label-middle',
	'top' => 'basket-item-label-top',
];

$discountPositionClass = '';
if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y' && !empty($arParams['DISCOUNT_PERCENT_POSITION'])) {
	foreach (explode('-', $arParams['DISCOUNT_PERCENT_POSITION']) as $pos) {
		$discountPositionClass .= isset($positionClassMap[$pos]) ? ' ' . $positionClassMap[$pos] : '';
	}
}

$labelPositionClass = '';
if (!empty($arParams['LABEL_PROP_POSITION'])) {
	foreach (explode('-', $arParams['LABEL_PROP_POSITION']) as $pos) {
		$labelPositionClass .= isset($positionClassMap[$pos]) ? ' ' . $positionClassMap[$pos] : '';
	}
}
?>

<script id="basket-table-headers-template" type="text/html">
	<th class="img-header">
		<?
		if (in_array('PREVIEW_PICTURE', $arParams['COLUMNS_LIST'])) {
			?>
			<div class="preview-image-header"><?= Loc::getMessage("IMAGE") ?></div>
			<?
		} ?>
	</th>
	<?
	if ($arParams["SHOW_ARTICLE_BEFORE_NAME"] === "Y"): ?>
		<th>
			<div class="article-header"><?= Loc::getMessage("ARTICLE") ?></div>
		</th>
	<?
	endif; ?>
	<th>
		<div class="product-header"><?= Loc::getMessage("SBB_GOOD_CAP") ?></div>
	</th>

	<?
	if ($arParams["SHOW_STORE_NAME"] === "Y"): ?>
		<th>
			<div class="store_header">
				<div class="store-header">
					<?= Loc::getMessage("STORE") ?>
				</div>
			</div>
		</th>
	<?
	endif; ?>

	<th class="td-header-amount">
		<div class="amount-header">
			<?= Loc::getMessage("AMOUNT") ?>
		</div>
	</th>

	<?
	if ($arParams["DISPLAY_RESTS"] === "Y") {
		?>
		<th class="td-header-rests">
			<div class="rests-header">
				<?= Loc::getMessage("RESTS") ?>
			</div>
		</th>
		<?
	} ?>

	{{#PARAM_HEADERS}}
	<th class="td-basket-header-property-{{CODE}}">
		<div class="property-{{CODE}}">
			{{{NAME}}}
		</div>
	</th>
	{{/PARAM_HEADERS}}

	<?
	if ($usePriceInAdditionalColumn) {
		?>
		<th class="td-price-header">
			<div class="price_header">
				<?= Loc::getMessage("PRICE") ?>
			</div>
		</th>
		<?
	} ?>

	<?
	if ($useSumColumn) {
		?>
		<th class="td-sum-header">
			<div class="sum-header">
				<?= Loc::getMessage("SUM") ?>
			</div>
		</th>
		<?
	} ?>
	<?
	if ($useActionColumn) {
		?>
		<th>
		</th>
		<?
	} ?>

</script>