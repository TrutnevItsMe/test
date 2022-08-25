<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?

$this->setFrameMode(true);
\Bitrix\Main\Page\Asset::getInstance()->addCss($templatePath . "/style.css");
?>



<? if ($arResult['SECTIONS']): ?>
	<? if ($arParams['SHOW_TITLE'] == 'Y'): ?>
		<div class="title-tab-heading visible-xs"><?= $arParams["T_TITLE"]; ?></div>
	<? endif; ?>
	<div class="item-views <?= $arParams['VIEW_TYPE'] ?> <?= $arParams['VIEW_TYPE'] ?>-type-block <?= ($arParams['SHOW_TABS'] == 'Y' ? 'with_tabs' : '') ?> <?= ($arParams['IMAGE_POSITION'] ? 'image_' . $arParams['IMAGE_POSITION'] : '') ?> <?= ($templateName = $component->{'__parent'}->{'__template'}->{'__name'}) ?>">
		<? // top pagination?>
		<? if ($arParams['DISPLAY_TOP_PAGER']): ?>
			<?= $arResult['NAV_STRING'] ?>
		<? endif; ?>

		<? // tabs?>
		<? if ($arParams['SHOW_TABS'] == 'Y'): ?>
		<div class="tabs">
			<ul class="nav nav-tabs">
				<? $i = 0; ?>
				<? foreach ($arResult['SECTIONS'] as $SID => $arSection): ?>
					<? if (!$SID) continue; ?>
					<li class="<?= $i++ == 0 ? 'active' : '' ?>"><a data-toggle="tab"
																	href="#<?= $this->GetEditAreaId($arSection['ID']) ?>"><?= $arSection['NAME'] ?></a>
					</li>
				<? endforeach; ?>
			</ul>
			<? endif; ?>

			<div class="<?= ($arParams['SHOW_TABS'] == 'Y' ? 'tab-content' : 'group-content') ?>">
				<? // group elements by sections?>
				<? foreach ($arResult['SECTIONS'] as $SID => $arSection): ?>

					<?
					// edit/add/delete buttons for edit mode
					$arSectionButtons = CIBlock::GetPanelButtons($arSection['IBLOCK_ID'], 0, $arSection['ID'], array('SESSID' => false, 'CATALOG' => true));
					$this->AddEditAction($arSection['ID'], $arSectionButtons['edit']['edit_section']['ACTION_URL'], CIBlock::GetArrayByID($arSection['IBLOCK_ID'], 'SECTION_EDIT'));
					$this->AddDeleteAction($arSection['ID'], $arSectionButtons['edit']['delete_section']['ACTION_URL'], CIBlock::GetArrayByID($arSection['IBLOCK_ID'], 'SECTION_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
					?>
					<div id="<?= $this->GetEditAreaId($arSection['ID']) ?>"
						 class="tab-pane <?= (!$si++ || !$arSection['ID'] ? 'active' : '') ?>">

						<? if ($arParams['SHOW_SECTION_PREVIEW_DESCRIPTION'] == 'Y'): ?>

							<? if ($arParams['SHOW_SECTION_NAME'] != 'N'): ?>
								<? // section name?>
								<? if (strlen($arSection['NAME'])): ?>
									<h3><?= $arSection['NAME'] ?></h3>
								<? endif; ?>
							<? endif; ?>

							<? // section description text/html?>
							<? if (strlen($arSection['DESCRIPTION']) && strpos($_SERVER['REQUEST_URI'], 'PAGEN') === false): ?>
								<div class="text_before_items">
									<?= $arSection['DESCRIPTION'] ?>
								</div>
								<? if ($arParams['SHOW_SECTION_DESC_DIVIDER'] == 'Y'): ?>
									<hr class="sect-divider"/>
								<? endif; ?>
							<? endif; ?>
						<? endif; ?>

						<? foreach ($arSection['ITEMS'] as $i => $arItem): ?>
							<?
							$arParams['SHOW_DETAIL_LINK'] = "Y";
							// edit/add/delete buttons for edit mode
							$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
							$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
							// use detail link?
							$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
							?>

							<? if ($arItem["DISPLAY_PROPERTIES"]["FILE"]): ?>
							<h4><?=$arItem["NAME"]?></h4>
								<? foreach ($arItem["DISPLAY_PROPERTIES"]["FILE"] as $file): ?>

										<?
										$img = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], [
											"width" => 100,
											"height" => 100
										],
											BX_RESIZE_IMAGE_PROPORTIONAL,
											true);
										?>

									<div class="d-flex justify-content-space-around align-items-baseline align-content-center">
										<div>
											<a href="<?= $arItem["DETAIL_PAGE_URL"] ?>">
												<? if ($img): ?>
													<img src="<?= $img["src"] ?>"
														 width="<?= $img["width"] ?>"
														 height="<?= $img["height"] ?>"/>
												<? else: ?>
													<img src="<?= SITE_TEMPLATE_PATH ?>/images/no_photo_small.png"
														 width="100"
														 height="100">
												<? endif ?>
											</a>
										</div>
										<div>
											<a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"><p><?= $file["ORIGINAL_NAME"] ?></p></a>
										</div>
										<div>
											<p><?= $file["TIMESTAMP_X"]->format("d.m.Y") ?></p>
										</div>
										<div>
											<a href="<?=$file["SRC"] ?>" download="<?= $file["ORIGINAL_NAME"] ?>">
												<p class="btn btn-info"><?=\Bitrix\Main\Localization\Loc::getMessage("DOWNLOAD")?></p>
											</a>
										</div>
									</div>
								<? endforeach; ?>

							<? endif; ?>

						<? endforeach; ?>
					</div>
				<? endforeach; ?>
			</div>

			<? if ($arParams['SHOW_TABS'] == 'Y'): ?>
		</div>
	<? endif; ?>

		<? // bottom pagination?>
		<? if ($arParams['DISPLAY_BOTTOM_PAGER']): ?>
			<?= $arResult['NAV_STRING'] ?>
		<? endif; ?>
	</div>
<? endif; ?>
