<?
use \Intervolga\Custom\Search\SearchSubString;

do {
    ob_start();
    $arSearchPageParams = array(
        "RESTART" => $arParams["RESTART"],
        "NO_WORD_LOGIC" => $arParams["NO_WORD_LOGIC"],
        "USE_LANGUAGE_GUESS" => $arParams["USE_LANGUAGE_GUESS"],
        "CHECK_DATES" => $arParams["CHECK_DATES"],
        "USE_TITLE_RANK" => "N",
        "DEFAULT_SORT" => "rank",
        "FILTER_NAME" => "",
        "SHOW_WHERE" => "N",
        "arrWHERE" => array(),
        "SHOW_WHEN" => "N",
        "PAGE_RESULT_COUNT" => 200,
        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "FROM_AJAX" => $isAjaxFilter,
        "PAGER_TITLE" => "",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => "N",
    );

    $arSearchPageParams = array_merge($arSearchPageParams, $arSearchPageFilter);

    $arElements = $APPLICATION->IncludeComponent("bitrix:search.page", "", $arSearchPageParams, $component);
    $searchBySubString = SearchSubString::getInstance($_REQUEST['q']);
    $needReturn = count($arElements) > 0;
    if (!$needReturn) {
        $needReturn = $searchBySubString->isSearchBySubString();
        if (!$needReturn) {
            ob_end_clean();
            if ($q = $searchBySubString->getQueryForRequestBySubString()) {
                $_REQUEST['q']= $q;
            }
            continue;
        }
    }
    echo ob_get_clean();
} while (!$needReturn);