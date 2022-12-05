<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arParams["POPUP_POSITION"] = (isset($arParams["POPUP_POSITION"]) && in_array($arParams["POPUP_POSITION"], array("left", "right"))) ? $arParams["POPUP_POSITION"] : "left";

foreach ($arResult["ITEMS"] as $key => &$arItem)
{
	/*unset empty values*/
	if (
		(
			($arItem["DISPLAY_TYPE"] == "A" || isset($arItem["PRICE"]))
			&& ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)
		)
		|| !$arItem["VALUES"]
	)
		unset($arResult["ITEMS"][$key]);

	if ($arItem["CODE"] == "HIT")
	{
		foreach ($arItem["VALUES"] as &$arValue)
		{
			if ($arValue["VALUE"] == "Акция")
			{
				$arValue["CHECKED"] = true;
				continue;
			}

			if ($arValue["VALUE"] == "Распродажа")
			{
				$arValue["DISABLED"] = true;
				continue;
			}
		}
	}

	if ($arItem["CODE"] == "IN_STOCK")
	{
		if ($arResult["ITEMS"][$key]["VALUES"])
			$arResult["ITEMS"][$key]["VALUES"][0]["VALUE"] = $arItem["NAME"];
	}
}

\Bitrix\Main\Localization\Loc::loadLanguageFile(__FILE__);

include 'sort.php';

global $sotbitFilterResult;
$sotbitFilterResult = $arResult;