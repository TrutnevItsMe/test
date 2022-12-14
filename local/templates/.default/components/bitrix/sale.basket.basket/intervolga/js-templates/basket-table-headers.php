<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die();

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
	'top' => 'basket-item-label-top'
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
	<td class="img-header">
		<?
		if (in_array('PREVIEW_PICTURE', $arParams['COLUMNS_LIST'])) {
			?>
			<div class="preview-image-header"><?=Loc::getMessage("IMAGE")?></div>
			<?
		} ?>
	</td>
	<? if ($arParams["SHOW_ARTICLE_BEFORE_NAME"] === "Y"): ?>
		<td>
			<div class="article-header"><?=Loc::getMessage("ARTICLE")?></div>
		</td>
	<? endif; ?>
	<td>
		<div class="product-header"><?=Loc::getMessage("SBB_GOOD_CAP")?></div>
	</td>

	<? if ($arParams["SHOW_STORE_NAME"] === "Y"): ?>
		<td>
			<div class="store_header">
				<div class="store-header">
					<?=Loc::getMessage("STORE")?>
				</div>
			</div>
		</td>
	<? endif; ?>

	<td class="td-header-amount">
		<div class="amount-header">
			<?=Loc::getMessage("AMOUNT")?>
		</div>
	</td>

	<? if ($arParams["DISPLAY_RESTS"] === "Y") {
		?>
		<td class="td-header-rests">
			<div class="rests-header">
				<?=Loc::getMessage("RESTS")?>
			</div>
		</td>
		<?
	} ?>

	{{#PARAM_HEADERS}}
	<td class="td-basket-header-property-{{CODE}}">
		<div class="property-{{CODE}}">
			{{{NAME}}}
		</div>
	</td>
	{{/PARAM_HEADERS}}
	<? /*
		if (!empty($arParams['PRODUCT_BLOCKS_ORDER'])) {
			foreach ($arParams['PRODUCT_BLOCKS_ORDER'] as $blockName) {
				switch (trim((string)$blockName)) {
					case 'props':
						if (in_array('PROPS', $arParams['COLUMNS_LIST'])) {
							 */ ?><!--
							{{#PROPS}}
							<td class="td-basket-header-property-{{CODE}}">
								<div class="property-{{CODE}}">
									{{{NAME}}}
								</div>
							</td>
							{{/PROPS}}
							<? /*
						}

						break;
					case 'sku':
						 */ ?>
						{{#SKU_BLOCK_LIST}}
						<td class="td-basket-header-property-{{CODE}}">
							<div class="property-{{CODE}}">
								{{{NAME}}}
							</div>
						</td>
						{{/SKU_BLOCK_LIST}}

						<? /*
						break;
					case 'columns':
						 */ ?>
						{{#COLUMN_LIST}}
						<td class="td-basket-header-property-{{CODE}}">
							<div class="property-{{CODE}}">
								{{{NAME}}}
							</div>
						</td>
						{{/COLUMN_LIST}}
						--><? /*
						break;
				}
			}
		}  */ ?>

	<?
	if ($usePriceInAdditionalColumn) {
		?>
		<td class="td-price-header">
			<div class="price_header">
				<?=Loc::getMessage("PRICE")?>
			</div>
		</td>
		<?
	} ?>

	<?
	if ($useSumColumn) {
		?>
		<td class="td-sum-header">
			<div class="sum-header">
				<?=Loc::getMessage("SUM")?>
			</div>
		</td>
		<?
	} ?>
	<?
	if ($useActionColumn) {
		?>
		<td>
		</td>
		<?
	} ?>

</script>