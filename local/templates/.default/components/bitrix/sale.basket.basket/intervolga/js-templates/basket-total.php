<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 */
?>
<script id="basket-total-template" type="text/html">
	<div class="basket-checkout-container" data-entity="basket-checkout-aligner">
		<?
		if ($arParams['HIDE_COUPON'] !== 'Y')
		{
			?>
			<div class="basket-coupon-section">
				<div class="basket-coupon-block-field">
					<div class="basket-coupon-block-field-description">
						<?=Loc::getMessage('SBB_COUPON_ENTER')?>:
					</div>
					<div class="form">
						<div class="form-group" style="position: relative;">
							<input type="text" class="form-control" id="" placeholder="" data-entity="basket-coupon-input">
							<span class="basket-coupon-block-coupon-btn"></span>
						</div>
					</div>
				</div>
			</div>
			<?
		}
		?>
		<div class="basket-checkout-section">
			<div class="basket-checkout-section-inner">

				<div class="basket-checkout-block basket-checkout-block-total">
					<div class="basket-checkout-block-total-inner">
						<div class="basket-checkout-block-total-title">
                            <?=Loc::getMessage('SBB_TOTAL')?>:
                            <span class="basket-coupon-block-total-price-current" data-entity="basket-total-price">
                                {{{PRICE_FORMATED}}}
                            </span>
                        </div>
						<? if (in_array("WEIGHT", $arParams["COLUMNS_COMMON_INFO"])): ?>
							<div class="basket-checkout-block-total-description">
								{{#WEIGHT_FORMATED}}
									<?=Loc::getMessage('SBB_WEIGHT')?>: {{{WEIGHT_FORMATED}}}
								{{/WEIGHT_FORMATED}}
							</div>
						<? endif; ?>
						<? if (in_array("COUNT", $arParams["COLUMNS_COMMON_INFO"])): ?>
							<div class="basket-checkout-block-total-description">
								<?=Loc::getMessage('IN_BASKET')?> {{{PRODUCT_COUNT}}} <?=Loc::getMessage('PRODUCTS')?>
							</div>
						<? endif; ?>
						<? if (in_array("VOLUME", $arParams["COLUMNS_COMMON_INFO"])): ?>
						<div class="basket-checkout-block-total-description">
							<?=Loc::getMessage("COMMON_VOLUME")?>{{{COMMON_VOLUME_FORMATED}}}
						</div>
						<? endif; ?>
					</div>
				</div>

				<div class="basket-checkout-block basket-checkout-block-btn">
                    <div>
                        <button class="btn btn-lg btn-default basket-btn-checkout{{#DISABLE_CHECKOUT}} disabled{{/DISABLE_CHECKOUT}}"
                            data-entity="basket-checkout-button">
                            <?=Loc::getMessage('SBB_ORDER')?>
                        </button>
                    </div>
                </div>

                <div class="basket-checkout-block clear-box" id="clear-box">
                    <div class="basket-items-list-header-filter">
                    </div>
                </div>
			</div>
		</div>

		<?
		if ($arParams['HIDE_COUPON'] !== 'Y')
		{
		?>
			<div class="basket-coupon-alert-section">
				<div class="basket-coupon-alert-inner">
					{{#COUPON_LIST}}
					<div class="basket-coupon-alert text-{{CLASS}}">
						<span class="basket-coupon-text">
							<strong>{{COUPON}}</strong> - <?=Loc::getMessage('SBB_COUPON')?> {{JS_CHECK_CODE}}
							{{#DISCOUNT_NAME}}({{DISCOUNT_NAME}}){{/DISCOUNT_NAME}}
						</span>
						<span class="close-link" data-entity="basket-coupon-delete" data-coupon="{{COUPON}}">
							<?=Loc::getMessage('SBB_DELETE')?>
						</span>
					</div>
					{{/COUPON_LIST}}
				</div>
			</div>
			<?
		}
		?>
	</div>
</script>