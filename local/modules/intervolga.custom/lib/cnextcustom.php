<?php
namespace Intervolga\Custom\Import;

CModule::IncludeModule("aspro.next");

class CNextCustom extends \CNext
{
    public static function GetTotalCount($arItem, $arParams = array()){
        $totalCount = 0;

        if(
            ($arParams['USE_REGION'] == 'Y' || intval($arParams['USE_REGION']) > 0) &&
            $arParams['STORES']
        ){
            $arSelect = array('ID', 'PRODUCT_AMOUNT');
            $arFilter = array('ACTIVE' => 'Y', 'ID' => $arParams['STORES']);

            if($arItem['OFFERS']){
                $arOffers = array_column($arItem['OFFERS'], 'ID');

                if($arOffers){
                    $quantity = 0;

                    $rsStore = CCatalogStore::GetList(array(), array_merge($arFilter, array('PRODUCT_ID' => $arOffers)), false, false, $arSelect);
                    while($arStore = $rsStore->Fetch()){
                        $quantity += $arStore['PRODUCT_AMOUNT'];
                    }

                    $totalCount = $quantity;
                }
            }
            elseif(
                isset($arItem['PRODUCT']['TYPE']) &&
                $arItem['PRODUCT']['TYPE'] == 2
            ){
                if(!$arItem['SET_ITEMS']){
                    $arItem['SET_ITEMS'] = array();

                    if($arSets = CCatalogProductSet::getAllSetsByProduct($arItem['ID'], 1)){
                        $arSets = reset($arSets);

                        foreach($arSets['ITEMS'] as $v){
                            $v['ID'] = $v['ITEM_ID'];
                            unset($v['ITEM_ID']);
                            $arItem['SET_ITEMS'][] = $v;
                        }
                    }
                }

                $arProductSet = $arItem['SET_ITEMS'] ? array_column($arItem['SET_ITEMS'], 'ID') : array();

                if($arProductSet){
                    $arSelect[] = 'ELEMENT_ID';
                    $quantity = array();

                    $rsStore = CCatalogStore::GetList(array(), array_merge($arFilter, array('PRODUCT_ID' => $arProductSet)), false, false, $arSelect);
                    while($arStore = $rsStore->Fetch()){
                        $quantity[$arStore['ELEMENT_ID']] += $arStore['PRODUCT_AMOUNT'];
                    }

                    if($quantity){
                        foreach($arItem['SET_ITEMS'] as $v) {
                            $quantity[$v['ID']] /= $v['QUANTITY'];
                            $quantity[$v['ID']] = floor($quantity[$v['ID']]);
                        }
                    }
                    $totalCount = min($quantity);
                }
            }
            else{
                $rsStore = CCatalogStore::GetList(array(), array_merge($arFilter, array('PRODUCT_ID' => $arItem['ID'])), false, false, $arSelect);
                while($arStore = $rsStore->Fetch()){
                    $quantity += $arStore['PRODUCT_AMOUNT'];
                }

                $totalCount = $quantity;
            }
        }
        else{
            if($arItem['OFFERS']){
                foreach($arItem['OFFERS'] as $arOffer)
                    $totalCount += $arOffer['CATALOG_QUANTITY'];
            }
            else
                $totalCount += ($arItem['~CATALOG_QUANTITY'] != $arItem['CATALOG_QUANTITY'] ? $arItem['~CATALOG_QUANTITY'] : $arItem['CATALOG_QUANTITY']);
        }

        foreach(GetModuleEvents(ASPRO_NEXT_MODULE_ID, 'OnAsproGetTotalQuantity', true) as $arEvent) // event for manipulation total quantity
            ExecuteModuleEventEx($arEvent, array($arItem, $arParams, &$totalCount));

        return self::CheckTypeCount($totalCount);
    }
}