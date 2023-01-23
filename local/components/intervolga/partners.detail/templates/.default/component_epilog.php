<?php
/**
 * @global $templateFolder
 *
 */
$asset = \Bitrix\Main\Page\Asset::getInstance();

$asset->addCss($templateFolder."/css/style.css");
$asset->addCss($templateFolder."/css/carousel.css");
$asset->addJs($templateFolder."/js/carousel.js");