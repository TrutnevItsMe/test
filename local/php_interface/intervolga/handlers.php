<?php
AddEventHandler( "iblock", "OnAfterIBlockElementAdd", array( "asproImport", "FillProperties" ) );
AddEventHandler( "iblock", "OnAfterIBlockElementUpdate", array( "asproImport", "FillProperties" ) );
class asproImport {
    public static function FillProperties( $arFields )
    {
        $iBlockID = \CNextCache::$arIBlocks['s1']['aspro_next_catalog']['aspro_next_catalog'][0];
        $newValue = [];
        $oldValue = [];
        $arCatalogID=array($iBlockID);
        if( in_array($arFields['IBLOCK_ID'], $arCatalogID) )
        {
            $property = CIBlockProperty::GetByID('HIT', $iBlockID)->GetNext();
            $propID = $property['ID'];

            $prop = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$iBlockID, "CODE"=>"HIT"));

            $arItem = CIBlockElement::GetList( false, array( 'IBLOCK_ID' => $arFields['IBLOCK_ID'], 'ID' => $arFields['ID'] ), false, false, array( 'ID', 'PROPERTY_KATEGORIYA_TOVARA_A_B_C_D', 'PROPERTY_SKIDKA', 'PROPERTY_HIT') );
            while ($el = $arItem->fetch())
            {
                $oldValue[] = $el["PROPERTY_HIT_ENUM_ID"];
                $temp = $el;
            }
            $arItem = $temp;
            while ($el = $prop->fetch())
            {
                if($arItem['PROPERTY_SKIDKA_VALUE'] == $el['VALUE'])
                {
                    $propId = $el['ID'];
                }
                if($el["XML_ID"] == 'HIT') $hit = $el['ID'];
                if($el["XML_ID"] == 'NEW') $new = $el['ID'];
                if($el["XML_ID"] == 'STOCK') $action = $el['ID'];
            }

            if($arItem['PROPERTY_KATEGORIYA_TOVARA_A_B_C_D_VALUE'] == 'Хит продаж')
            {
                $newValue[] = $hit;
            }

            $prop = CIBlockElement::GetProperty( $arFields['IBLOCK_ID'], $arFields['ID'],"sort", "asc", array('CODE' => 'CML2_TRAITS'));
            while ($el = $prop->fetch())
            {
                if($el['DESCRIPTION'] == 'Акция' && $el['VALUE'] == 'true')
                {
                    $newValue[] = $action;
                }
                if($el['DESCRIPTION'] == 'Новинка' && $el['VALUE'] == 'true')
                {
                    $newValue[] = $new;
                }
            }
            if($arItem['PROPERTY_SKIDKA_VALUE'])
            {
                if(!isset($propId))
                {
                    $propId = CIBlockPropertyEnum::Add(Array('PROPERTY_ID'=>$propID, 'VALUE'=>$arItem['PROPERTY_SKIDKA_VALUE']));
                }
                $newValue[] = $propId;
            }
            if(array_values($oldValue) != array_values($newValue))  CIBlockElement::SetPropertyValuesEx($arFields['ID'], false, array('HIT' => $newValue));
        }
    }
}