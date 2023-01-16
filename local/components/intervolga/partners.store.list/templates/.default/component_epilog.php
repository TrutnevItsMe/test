<?php

/**
 * @global $templateFolder
 *
 */

$asset = Bitrix\Main\Page\Asset::getInstance();
$asset->addCss($templateFolder."/css/style.css");
$asset->addJs($templateFolder."/js/urlUtils.js");
$asset->addJs($templateFolder."/js/arrayUtils.js");
$asset->addJs($templateFolder."/js/yandexMap.js");
$asset->addJs($templateFolder."/js/customCheckbox.js");
$asset->addJs($templateFolder."/js/filterComponent.js");
$asset->addJs($templateFolder."/js/elementComponent.js");
$asset->addJs($templateFolder."/js/paginationComponent.js");
$asset->addJs($templateFolder."/js/script.js");
