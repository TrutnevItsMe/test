<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

$APPLICATION->AddChainItem(Loc::getMessage("SPS_CHAIN_MAIN"), $arResult['SEF_FOLDER']);
$APPLICATION->AddChainItem(Loc::getMessage("SPS_CHAIN_SUBSCRIBE_NEW"));?>
<div class="personal_wrapper">
    <?$APPLICATION->IncludeComponent(
        "bitrix:main.profile",
        "profile",
        Array(
            "SET_TITLE" => "Y",
            "AJAX_MODE" => $arParams['AJAX_MODE_PRIVATE'],
            "SEND_INFO" => $arParams["SEND_INFO_PRIVATE"],
            "MANAGERS_ONLY" => "Y",
            "CHECK_RIGHTS" => $arParams['CHECK_RIGHTS_PRIVATE']
        ),
        $component
    );?>
	<?$APPLICATION->IncludeComponent(
		'bitrix:catalog.product.subscribe.list',
		'',
		array('SET_TITLE' => $arParams['SET_TITLE_SUBSCRIBE'])
		,
		$component
	);?>
</div>


