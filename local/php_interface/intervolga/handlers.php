<?php
\Bitrix\Main\EventManager::getInstance()->addEventHandler( "iblock", "OnAfterIBlockElementAdd", array( "AsproImport", "FillProperties" ) );
\Bitrix\Main\EventManager::getInstance()->addEventHandler( "iblock", "OnAfterIBlockElementUpdate", array( "AsproImport", "FillProperties" ) );
class AsproImport {
    static $propHit = [];
    static $propHitId = null;
    public static function fillProperties( $arFields )
    {
        $iBlockID = \CNextCache::$arIBlocks['s1']['aspro_next_catalog']['aspro_next_catalog'][0];
        $newValue = [];
        if($arFields['IBLOCK_ID'] == $iBlockID)
        {
            if (self::$propHitId == null) self::$propHitId = CIBlockProperty::GetByID('HIT', $iBlockID)->GetNext();
            $propHitID = self::$propHitId['ID'];

            if (count(self::$propHit) == 0)
            {
                $res = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$iBlockID, "CODE"=>"HIT"));
                while ($el = $res->fetch())
                {
                    self::$propHit[] = $el;
                }
            }

            $item = CIBlockElement::GetList( false, array( 'IBLOCK_ID' => $arFields['IBLOCK_ID'], 'ID' => $arFields['ID'] ), false, false, array( 'ID', 'PROPERTY_KATEGORIYA_TOVARA_A_B_C_D', 'PROPERTY_SKIDKA', 'PROPERTY_HIT') );
            while ($el = $item->fetch())
            {
                $oldValue[] = $el["PROPERTY_HIT_ENUM_ID"];
                $arItem = $el;
            }
            $oldValue = $arItem["PROPERTY_HIT_ENUM_ID"];
            foreach (self::$propHit as $el)
            {
                if($arItem['PROPERTY_SKIDKA_VALUE'] == $el['VALUE'])
                {
                    $newPropHitListElId = $el['ID'];
                }
                if($el["XML_ID"] == 'HIT') $propHitValueID['hit'] = $el['ID'];
                if($el["XML_ID"] == 'NEW') $propHitValueID['new'] = $el['ID'];
                if($el["XML_ID"] == 'STOCK') $propHitValueID['action'] = $el['ID'];
                if($el["XML_ID"] == 'SALE') $propHitValueID['sale'] = $el['ID'];
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
                if($el['DESCRIPTION'] == 'Распродажа' && $el['VALUE'] == 'true')
                {
                    $newValue[] = $propHitValueID['sale'];
                }
            }
            if($arItem['PROPERTY_SKIDKA_VALUE'])
            {
                if(!isset($newPropHitListElId))
                {
                    $newPropHitListElId = CIBlockPropertyEnum::Add(Array('PROPERTY_ID'=>$propHitID, 'VALUE'=>$arItem['PROPERTY_SKIDKA_VALUE']));
                    self::$propHit[] =
                        [
                            'VALUE'=>$arItem['PROPERTY_SKIDKA_VALUE'],
                            'XML_ID'=>'FAKE',
                            'ID' => $newPropHitListElId,
                        ];
                }
                $newValue[] = $newPropHitListElId;
            }
            if(count(array_diff($newValue, $oldValue)) > 0 || count($oldValue) != count($newValue))  CIBlockElement::SetPropertyValuesEx($arFields['ID'], false, array('HIT' => $newValue));
        }
    }
}