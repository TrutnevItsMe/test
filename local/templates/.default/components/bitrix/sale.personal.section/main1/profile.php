<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

$APPLICATION->SetTitle(Loc::getMessage("SPS_TITLE_PROFILE"));
// $APPLICATION->AddChainItem(Loc::getMessage("SPS_CHAIN_MAIN"), $arResult['SEF_FOLDER']);
$APPLICATION->AddChainItem(Loc::getMessage("SPS_CHAIN_PROFILE"));?>
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
		"iv:sale.personal.profile.list",
		"",
		array(
			"PATH_TO_DETAIL" => $arResult['PATH_TO_PROFILE_DETAIL'],
			"PATH_TO_DELETE" => $arResult['PATH_TO_PROFILE_DELETE'],
			"PER_PAGE" => $arParams["PER_PAGE"],
			"SET_TITLE" =>$arParams["SET_TITLE"],
		),
		$component
	);
	?>
</div>
