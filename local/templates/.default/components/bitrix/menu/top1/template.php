<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

    $this->setFrameMode(true);

    global $arTheme;
    $iVisibleItemsMenu = ($arTheme['MAX_VISIBLE_ITEMS_MENU']['VALUE'] ? $arTheme['MAX_VISIBLE_ITEMS_MENU']['VALUE'] : 10);
    $iVisibleItemsMenu = 5;
?>
<?php if ($arResult): ?>
    <div class="table-menu">
        <table>
            <tr>
                <?php foreach ($arResult as $arItem): ?>
                        <?php $bShowChilds = $arParams["MAX_LEVEL"] > 1;
                        $bWideMenu = (isset($arItem['PARAMS']['CLASS']) && strpos($arItem['PARAMS']['CLASS'], 'wide_menu') !== false); ?>
                        <td class="menu-item unvisible <?= ($arItem["CHILD"] ? "dropdown" : "") ?> <?= (isset($arItem["PARAMS"]["CLASS"]) ? $arItem["PARAMS"]["CLASS"] : ""); ?>  <?= ($arItem["SELECTED"] ? "active" : "") ?>">
                            <div class="wrap">
                                <a class="<?= ($arItem["CHILD"] && $bShowChilds ? "dropdown-toggle" : "") ?>"
                                   href="<?= $arItem["LINK"] ?>">
                                    <div>
                                        <?php if (isset($arItem["PARAMS"]["CLASS"]) && strpos($arItem["PARAMS"]["CLASS"], "sale_icon") !== false): ?>
                                            <?= CNext::showIconSvg('sale', SITE_TEMPLATE_PATH . '/images/svg/Sale.svg', '', ''); ?>
                                        <?php endif; ?>
                                        <?= $arItem["TEXT"] ?>
                                        <div class="line-wrapper"><span class="line"></span></div>
                                    </div>
                                </a>
                                <?php if ($arItem["CHILD"] && $bShowChilds): ?>
                                    <span class="tail"></span>
                                    <ul class="dropdown-menu">
                                        <?php foreach ($arItem["CHILD"] as $arSubItem): ?>
                                            <?php if(!strpos($arSubItem["LINK"],OUT_OF_PRODUCTION_IBLOCK_SECTION_CODE)): ?>
                                                <?php $bShowChilds = $arParams["MAX_LEVEL"] > 2; ?>
                                                <?php $bHasPicture = (isset($arSubItem['PARAMS']['PICTURE']) && $arSubItem['PARAMS']['PICTURE'] && $arTheme['SHOW_CATALOG_SECTIONS_ICONS']['VALUE'] == 'Y'); ?>
                                                <li class="<?= ($arSubItem["CHILD"] && $bShowChilds ? "dropdown-submenu" : "") ?> <?= ($arSubItem["SELECTED"] ? "active" : "") ?> <?= ($bHasPicture ? "has_img" : "") ?>">
                                                    <?php if ($bHasPicture && $bWideMenu):
                                                        $arImg = CFile::ResizeImageGet($arSubItem['PARAMS']['PICTURE'], array('width' => 60, 'height' => 60), BX_RESIZE_PROPORTIONAL_ALT);
                                                        if (is_array($arImg)):?>
                                                            <div class="menu_img"><img src="<?= $arImg["src"] ?>"
                                                                                       alt="<?= $arSubItem["TEXT"] ?>"
                                                                                       title="<?= $arSubItem["TEXT"] ?>"/>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                    <a href="<?= $arSubItem["LINK"] ?>" title="<?= $arSubItem["TEXT"] ?>">
                                                        <span class="name"><?= $arSubItem["TEXT"] ?></span>
                                                        <?= ($arSubItem["CHILD"] && $bShowChilds ? '<span class="arrow"><i></i></span>' : '') ?>
                                                    </a>
                                                    <?php if ($arSubItem["CHILD"] && $bShowChilds): ?>
                                                        <?php $iCountChilds = count($arSubItem["CHILD"]); ?>
                                                        <ul class="dropdown-menu toggle_menu">
                                                            <?php foreach ($arSubItem["CHILD"] as $key => $arSubSubItem): ?>
                                                                <?php $bShowChilds = $arParams["MAX_LEVEL"] > 3; ?>
                                                                <li class="menu-item <?= (++$key > $iVisibleItemsMenu ? 'collapsed' : ''); ?> <?= ($arSubSubItem["CHILD"] && $bShowChilds ? "dropdown-submenu" : "") ?> <?= ($arSubSubItem["SELECTED"] ? "active" : "") ?>">
                                                                    <a href="<?= $arSubSubItem["LINK"] ?>"
                                                                       title="<?= $arSubSubItem["TEXT"] ?>"><span
                                                                                class="name"><?= $arSubSubItem["TEXT"] ?></span></a>
                                                                    <?php if ($arSubSubItem["CHILD"] && $bShowChilds): ?>
                                                                        <ul class="dropdown-menu">
                                                                            <?php foreach ($arSubSubItem["CHILD"] as $arSubSubSubItem): ?>
                                                                                <li class="menu-item <?= ($arSubSubSubItem["SELECTED"] ? "active" : "") ?>">
                                                                                    <a href="<?= $arSubSubSubItem["LINK"] ?>"
                                                                                       title="<?= $arSubSubSubItem["TEXT"] ?>"><span
                                                                                                class="name"><?= $arSubSubSubItem["TEXT"] ?></span></a>
                                                                                </li>
                                                                            <?php endforeach; ?>
                                                                        </ul>
                                                                    <?php endif; ?>
                                                                </li>
                                                            <?php endforeach; ?>
                                                            <?php if ($iCountChilds > $iVisibleItemsMenu && $bWideMenu): ?>
                                                                <li>
                                                                    <span class="colored more_items with_dropdown">
                                                                        <?= \Bitrix\Main\Localization\Loc::getMessage("S_MORE_ITEMS"); ?>
                                                                    </span>
                                                                </li>
                                                            <?php endif; ?>
                                                        </ul>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endif;?>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </td>
                <?php endforeach; ?>
            </tr>
        </table>
    </div>
<?php endif; ?>