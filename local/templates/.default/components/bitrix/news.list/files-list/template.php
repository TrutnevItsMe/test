<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
} ?>
<?php

$this->setFrameMode(true);
\Bitrix\Main\Page\Asset::getInstance()->addCss($templatePath . "/style.css");
?>



<?php if ($arResult['SECTIONS']): ?>
	<?php if ($arParams['SHOW_TITLE'] == 'Y'): ?>
		<div class="title-tab-heading visible-xs"><?=$arParams["T_TITLE"];?></div>
	<?php endif; ?>
	<div class="info-row-wrapper item-views <?=$arParams['VIEW_TYPE']?> <?=$arParams['VIEW_TYPE']?>-type-block <?=($arParams['SHOW_TABS'] == 'Y' ? 'with_tabs' : '')?> <?=($arParams['IMAGE_POSITION'] ? 'image_' . $arParams['IMAGE_POSITION'] : '')?> <?=($templateName = $component->{'__parent'}->{'__template'}->{'__name'})?>">
		<?php // top pagination?>
		<?php if ($arParams['DISPLAY_TOP_PAGER']): ?>
			<?=$arResult['NAV_STRING']?>
		<?php endif; ?>

		<?php // tabs?>
		<?php if ($arParams['SHOW_TABS'] == 'Y'): ?>
		<div class="tabs">
			<ul class="nav nav-tabs">
				<?php $i = 0; ?>
				<?php foreach ($arResult['SECTIONS'] as $SID => $arSection): ?>
					<?php if (!$SID) {
						continue;
					} ?>
					<li class="<?=$i++ == 0 ? 'active' : ''?>"><a data-toggle="tab"
																  href="#<?=$this->GetEditAreaId($arSection['ID'])?>"><?=$arSection['NAME']?></a>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>

			<div class="<?=($arParams['SHOW_TABS'] == 'Y' ? 'tab-content' : 'group-content')?>">
				<?php // group elements by sections?>
				<?php foreach ($arResult['SECTIONS'] as $SID => $arSection): ?>

					<?php
					// edit/add/delete buttons for edit mode
					$arSectionButtons = CIBlock::GetPanelButtons($arSection['IBLOCK_ID'], 0, $arSection['ID'], [
						'SESSID' => false,
						'CATALOG' => true
					]);
					$this->AddEditAction($arSection['ID'], $arSectionButtons['edit']['edit_section']['ACTION_URL'], CIBlock::GetArrayByID($arSection['IBLOCK_ID'], 'SECTION_EDIT'));
					$this->AddDeleteAction($arSection['ID'], $arSectionButtons['edit']['delete_section']['ACTION_URL'], CIBlock::GetArrayByID($arSection['IBLOCK_ID'], 'SECTION_DELETE'), ['CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
					?>
					<div id="<?=$this->GetEditAreaId($arSection['ID'])?>"
						 class="tab-pane <?=(!$si++ || !$arSection['ID'] ? 'active' : '')?>">

						<?php if ($arParams['SHOW_SECTION_PREVIEW_DESCRIPTION'] == 'Y'): ?>

							<?php if ($arParams['SHOW_SECTION_NAME'] != 'N'): ?>
								<?php // section name?>
								<?php if (strlen($arSection['NAME'])): ?>
									<h3><?=$arSection['NAME']?></h3>
								<?php endif; ?>
							<?php endif; ?>

							<?php // section description text/html?>
							<?php if (strlen($arSection['DESCRIPTION']) && strpos($_SERVER['REQUEST_URI'], 'PAGEN') === false): ?>
								<div class="text_before_items">
									<?=$arSection['DESCRIPTION']?>
								</div>
								<?php if ($arParams['SHOW_SECTION_DESC_DIVIDER'] == 'Y'): ?>
									<hr class="sect-divider" />
								<?php endif; ?>
							<?php endif; ?>
						<?php endif; ?>

						<?php foreach ($arSection['ITEMS'] as $i => $arItem): ?>
							<?php
							$arParams['SHOW_DETAIL_LINK'] = "Y";
							// edit/add/delete buttons for edit mode
							$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
							$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), ['CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
							// use detail link?
							$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
							?>

							<?php if ($arItem["DISPLAY_PROPERTIES"]["FILE"]): ?>
								<h4><?=$arItem["NAME"]?></h4>
								<?php $file = $arItem["DISPLAY_PROPERTIES"]["FILE"][0]; ?>

								<?php
								$img = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], [
									"width" => 100,
									"height" => 100
								],
									BX_RESIZE_IMAGE_PROPORTIONAL,
									true);
								?>

								<div class="info-row">
									<div class="info-row__inner">
										<div class="info-row__item info-row__item_image">
											<a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
												<?php if ($img): ?>
													<img src="<?=$img["src"]?>"
														 width="<?=$img["width"]?>"
														 height="<?=$img["height"]?>" />
												<?php else: ?>
													<img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_small.png"
														 width="100"
														 height="100">
												<?php endif ?>
											</a>
										</div>
										<div class="info-row__item info-row__item_name">
											<a href="<?=$file["SRC"]?>" download="<?=$file["ORIGINAL_NAME"]?>" <?/*href="<?=$arItem["DETAIL_PAGE_URL"]?>"*/?>><p><?=$file["ORIGINAL_NAME"]?></p>
											</a>
										</div>
										<?php if ($file["TIMESTAMP_X"]): ?>
											<div class="info-row__item">
												<p><?=substr($file["TIMESTAMP_X"], 0, -9)?></p>
											</div>
										<?php endif; ?>
										<div class="info-row__item">
											<a href="<?=$file["SRC"]?>" download="<?=$file["ORIGINAL_NAME"]?>">
												<p class="btn btn-info"><?=\Bitrix\Main\Localization\Loc::getMessage("DOWNLOAD")?></p>
											</a>
										</div>
									</div>
								</div>

							<?php endif; ?>

						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
			</div>

			<?php if ($arParams['SHOW_TABS'] == 'Y'): ?>
		</div>
	<?php endif; ?>

		<?php // bottom pagination?>
		<?php if ($arParams['DISPLAY_BOTTOM_PAGER']): ?>
			<?=$arResult['NAV_STRING']?>
		<?php endif; ?>
	</div>
<?php endif; ?>
<script>
$(function($){
	var storage = document.cookie.match(/nav-tabs=(.+?);/);
	
	
	if (storage && storage[1] !== "#") {
		$('.nav-tabs a[href="' + storage[1] + '"]').tab('show');
	}
 
	$('ul.nav li').on('click', function() {
		var id = $(this).find('a').attr('href');
		document.cookie = 'nav-tabs=' + id;
	});
});
</script>
