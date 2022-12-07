<?php
B_PROLOG_INCLUDED === true || die();

$articleBD = CIBlockElement::getList([], ["ID" => array_column($arResult["ITEMS"], "ID")], false,
	false, [
		"ID",
		"IBLOCK_ID",
		"PROPERTY_ARTIKUL_ANALOG",
		"PROPERTY_CML2_ARTICLE",
		"PROPERTY_ARTIKUL_ANALOG_VALUE",
		"PROPERTY_CML2_ARTICLE_VALUE"
	]);
$arArticle = [];
while ($article = $articleBD->GetNext()) {
	$arArticle[] = $article;
}
$ids = array_column($arResult["ITEMS"], "ID");
$cml2 = array_column($arArticle, "PROPERTY_CML2_ARTICLE_VALUE", "ID");
$analog = array_column($arArticle, "PROPERTY_ARTIKUL_ANALOG_VALUE", "ID");
$name = array_column($arResult["ITEMS"], "NAME" , "ID");
$price = array_column($arResult["ITEMS"], "MIN_PRICE" , "ID");
$product = array_column($arResult["ITEMS"], "PRODUCT" , "ID");

$csv = [];
foreach ($ids as $id){
	$csv[$id]["ARTICLE"] = $cml2[$id] ?: $analog[$id] ?: "Не задано";
	$csv[$id]["NAME"] = $name[$id];
	$csv[$id]["PRICE"] = $price[$id]["VALUE"];
	$csv[$id]["QUANTITY"] = $product[$id]["QUANTITY"];
}
array_unshift($csv, ["Артикуль", "Название", "Цена", "Количество"]);
$cp = $this->__component; // объект компонента

if (is_object($cp)) {
	$cp->arResult["CSV"] = $csv;

	$cp->SetResultCacheKeys([
		"CSV"
	]);
}