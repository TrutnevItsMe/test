<?
$arResult["TITLE"] = htmlspecialchars_decode(trim($arResult["TITLE"]));
$arResult["ADDRESS"] = ((strlen($arResult["TITLE"]) && strlen(trim($arResult["ADDRESS"])) ? ", ". htmlspecialchars(trim($arResult["ADDRESS"])) : ""));
$_SESSION['SHOP_TITLE'] = $arResult['ADDRESS'];
?>