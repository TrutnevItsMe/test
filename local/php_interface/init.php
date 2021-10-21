<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
\Bitrix\Main\Loader::includeModule("intervolga.custom");

/**
 * Do not use addEventHandler here, use autoload handlers (see /local/modules/intervolga.custom/index.php)!
 */
include_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/intervolga/handlers.php';

# бренды
AddEventHandler( "iblock", "OnAfterIBlockElementAdd", array( "aspro_import", "FillTheBrands" ) );
AddEventHandler( "iblock", "OnAfterIBlockElementUpdate", array( "aspro_import", "FillTheBrands" ) );
class aspro_import {
	function FillTheBrands( $arFields ){
		$arCatalogID=array(17);
		if( in_array($arFields['IBLOCK_ID'], $arCatalogID) ){
			$arItem = CIBlockElement::GetList( false, array( 'IBLOCK_ID' => $arFields['IBLOCK_ID'], 'ID' => $arFields['ID'] ), false, false, array( 'ID', 'PROPERTY_BREND' ) )->fetch();
			if( $arItem['PROPERTY_BREND_VALUE'] ){
				$arBrand = CIBlockElement::GetList( false, array( 'IBLOCK_ID' => 12, 'NAME' => $arItem['PROPERTY_BREND_VALUE'] ) )->fetch();
				if( $arBrand ){
					CIBlockElement::SetPropertyValuesEx( $arFields['ID'], false, array( 'BRAND' => $arBrand['ID'] ) );
				}else{
					$el = new CIBlockElement;
					$arParams = array( "replace_space" => "-", "replace_other" => "-" );
					$id = $el->Add( array(
						'ACTIVE' => 'Y',
						'NAME' => $arItem['PROPERTY_BREND_VALUE'],
						'IBLOCK_ID' => 12,
						'CODE' => Cutil::translit( $arItem['PROPERTY_BREND_VALUE'], "ru", $arParams )
					) );
					if( $id ){
						CIBlockElement::SetPropertyValuesEx( $arFields['ID'], false, array( 'BRAND' => $id ) );
					}else{
						echo $el->LAST_ERROR;
					}
				}
			}
		}
	}
}