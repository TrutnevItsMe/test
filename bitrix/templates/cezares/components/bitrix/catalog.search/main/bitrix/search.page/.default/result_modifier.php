<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use \Intervolga\Custom\Search\SearchSubstring;
$searchBySubString = SearchSubstring::getInstance($arResult["REQUEST"]["QUERY"]);
if ($searchBySubString->isSearchBySubString()) {
    $arResult["REQUEST"]["QUERY"] = $searchBySubString->getOriginalQuery();
}

