<?php
\Bitrix\Main\EventManager::getInstance()->addEventHandler( "iblock", "OnAfterIBlockElementAdd", array( "AsproImport", "FillProperties" ) );
\Bitrix\Main\EventManager::getInstance()->addEventHandler( "iblock", "OnAfterIBlockElementUpdate", array( "AsproImport", "FillProperties" ) );
class AsproImport {
    static $propHit = null;
    public static function fillProperties( $arFields )
    {
        $iBlockID = \CNextCache::$arIBlocks['s1']['aspro_next_catalog']['aspro_next_catalog'][0];
        $newValue = [];
        $oldValue = [];
        if($arFields['IBLOCK_ID'] == $iBlockID)
        {
            $property = CIBlockProperty::GetByID('HIT', $iBlockID)->GetNext();
            $propHitID = $property['ID'];

            if (self::$propHit == null) self::$propHit = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$iBlockID, "CODE"=>"HIT"));
            $prop = self::$propHit;

            $arItem = \CNextCache::CIBlockElement_GetList( false, array( 'IBLOCK_ID' => $arFields['IBLOCK_ID'], 'ID' => $arFields['ID'] ), false, false, array( 'ID', 'PROPERTY_KATEGORIYA_TOVARA_A_B_C_D', 'PROPERTY_SKIDKA', 'PROPERTY_HIT'));
            $arItem = $arItem[0];
            foreach ($arItem["PROPERTY_HIT_ENUM_ID"] as $el)
            {
                $oldValue[] = $el;
            }
            while ($el = $prop->fetch())
            {
                if($arItem['PROPERTY_SKIDKA_VALUE'] == $el['VALUE'])
                {
                    $newPropHitListElId = $el['ID'];
                }
                if($el["XML_ID"] == 'HIT') $propHitValueID['hit'] = $el['ID'];
                if($el["XML_ID"] == 'NEW') $propHitValueID['new'] = $el['ID'];
                if($el["XML_ID"] == 'STOCK') $propHitValueID['action'] = $el['ID'];
            }

            if($arItem['PROPERTY_KATEGORIYA_TOVARA_A_B_C_D_VALUE'] == 'Хит продаж')
            {
                $newValue[] = $propHitValueID['hit'];
            }

            $prop = CIBlockElement::GetProperty( $arFields['IBLOCK_ID'], $arFields['ID'],"sort", "asc", array('CODE' => 'CML2_TRAITS'));
            while ($el = $prop->fetch())
            {
                if($el['DESCRIPTION'] == 'Акция' && $el['VALUE'] == 'true')
                {
                    $newValue[] = $propHitValueID['action'];
                }
                if($el['DESCRIPTION'] == 'Новинка' && $el['VALUE'] == 'true')
                {
                    $newValue[] = $propHitValueID['new'];
                }
            }
            if($arItem['PROPERTY_SKIDKA_VALUE'])
            {
                if(!isset($newPropHitListElId))
                {
                    $newPropHitListElId = CIBlockPropertyEnum::Add(Array('PROPERTY_ID'=>$propHitID, 'VALUE'=>$arItem['PROPERTY_SKIDKA_VALUE']));
                }
                $newValue[] = $newPropHitListElId;
            }
            if(count(array_diff($newValue, $oldValue)) > 0)  CIBlockElement::SetPropertyValuesEx($arFields['ID'], false, array('HIT' => $newValue));
        }
    }
}