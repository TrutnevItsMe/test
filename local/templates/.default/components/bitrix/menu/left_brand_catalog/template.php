<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$this->setFrameMode(true);
if (!empty($arResult)):
    CJSCore::Init(["jquery"]);
    global $arTheme; ?>
	<div class="menu_top_block catalog_block brand-left-dropdown-menu">
		<ul class="menu dropdown">
			<?php foreach($arResult as $key => $arItem ): ?>
                <li class="full <?= ($arItem["CHILD"] ? "has-child" : "") ?> <?= ($arItem["SELECTED"] ? "current opened" : "") ?>
                    m_<?= strtolower($arTheme["MENU_POSITION"]["VALUE"]) ?> v_<?= strtolower($arTheme["MENU_TYPE_VIEW"]["VALUE"]) ?>">
					<a class="icons_fa <?= ($arItem["CHILD"] ? "parent" : "") ?>" href="<?= $arItem["SECTION_PAGE_URL"] ?>" >
						<?php if($arItem["IMAGES"] && $arTheme["LEFT_BLOCK_CATALOG_ICONS"]["VALUE"] == "Y"): ?>
							<span class="image">
                                <img src="<?= $arItem["IMAGES"]["src"] ?>" alt="<?= $arItem["NAME"] ?>" />
                            </span>
						<?php endif;?>
						<span class="name"><?=$arItem["NAME"]?></span>
						<div class="toggle_block"></div>
						<div class="clearfix"></div>
					</a>
					<?php if($arItem["CHILD"]): ?>
						<ul class="dropdown brand-dropdown">
							<?php foreach($arItem["CHILD"] as $arChildItem):
								if($arChildItem["UF_DISABLE_MENU"] == 1) continue;
								?>
								<li class="<?= ($arChildItem["CHILD"] ? "has-childs" : "") ?> <?= $arChildItem["SELECTED"] ?  'current' : '' ?>">
                                    <?php if ($arChildItem["IMAGES"] && $arTheme['SHOW_CATALOG_SECTIONS_ICONS']['VALUE'] == 'Y' && $arTheme["MENU_TYPE_VIEW"]["VALUE"] !== 'BOTTOM'): ?>
										<span class="image">
                                            <a href="<?= $arChildItem["SECTION_PAGE_URL"] ?>">
                                                <img src="<?=$arChildItem["IMAGES"]["src"];?>" alt="<?= $arChildItem["NAME"] ?>" />
                                            </a>
                                        </span>
									<?php endif?>
									<a class="section" href="<?=$arChildItem["SECTION_PAGE_URL"];?>">
                                        <span><?=$arChildItem["NAME"];?></span>
                                    </a>
									<?php if($arChildItem["CHILD"]): ?>
										<ul class="dropdown">
											<?php foreach($arChildItem["CHILD"] as $arChildItem1):
												if($arChildItem1["UF_DISABLE_MENU"] == 1) continue;
                                            ?>
												<li class="menu_item <?= $arChildItem1["SELECTED"] ? 'current' : '' ?>">
													<a class="parent1 section1" href="<?=$arChildItem1["SECTION_PAGE_URL"];?>"><span><?=$arChildItem1["NAME"];?></span></a>
												</li>
											<?php endforeach; ?>
										</ul>
									<?php endif;?>
									<div class="clearfix"></div>
								</li>
							<?php endforeach;?>
						</ul>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>
