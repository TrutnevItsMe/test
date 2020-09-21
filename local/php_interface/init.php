<?php
AddEventHandler( "iblock", "OnAfterIBlockElementAdd", array( "aspro_import", "FillProperties" ) );
AddEventHandler( "iblock", "OnAfterIBlockElementUpdate", array( "aspro_import", "FillProperties" ) );
class aspro_import {
    const iBlockID = 17;
    const propID = 136;
    function FillProperties( $arFields )
    {
        $newValue = [];
        $arCatalogID=array(self::iBlockID);
        if( in_array($arFields['IBLOCK_ID'], $arCatalogID) )
        {
            $arItem = CIBlockElement::GetList( false, array( 'IBLOCK_ID' => $arFields['IBLOCK_ID'], 'ID' => $arFields['ID'] ), false, false, array( 'ID', 'PROPERTY_KATEGORIYA_TOVARA_A_B_C_D', 'PROPERTY_SKIDKA') )->fetch();
            if($arItem['PROPERTY_KATEGORIYA_TOVARA_A_B_C_D_VALUE'] == 'Хит продаж')
            {
                $newValue[] = 65;
            }
            $prop = CIBlockElement::GetProperty( $arFields['IBLOCK_ID'], $arFields['ID'],"sort", "asc", array('CODE' => 'CML2_TRAITS',) );
            while ($el = $prop->fetch())
            {
                if($el['DESCRIPTION'] == 'Акция' && $el['VALUE'] == 'true')
                {
                    $newValue[] = 68;
                }
                if($el['DESCRIPTION'] == 'Новинка' && $el['VALUE'] == 'true')
                {
                    $newValue[] = 67;
                }
            }
            if($arItem['PROPERTY_SKIDKA_VALUE'])
            {
                $prop = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>self::iBlockID, "CODE"=>"HIT"));
                while ($el = $prop->fetch())
                {
                   if($arItem['PROPERTY_SKIDKA_VALUE'] == $el['VALUE'])
                   {
                       $propId = $el['ID'];
                       break;
                   }
                }
                if(!isset($propId))
                {
                    $propId = $PropID = CIBlockPropertyEnum::Add(Array('PROPERTY_ID'=>self::propID, 'VALUE'=>$arItem['PROPERTY_SKIDKA_VALUE']));
                }
                $newValue[] = $propId;
            }
            CIBlockElement::SetPropertyValuesEx($arFields['ID'], false, array('HIT' => $newValue));
        }
    }
}
