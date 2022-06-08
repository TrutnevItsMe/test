<?php

namespace Intervolga\Custom\Tools;

use \Bitrix\Main\Data\Cache;
use \Bitrix\Main\Application;
use \Bitrix\Main\Loader;

class Iblock
{
    const IV_CACHE_PATH = "s1/iv/";
    const CACHE_TIME = 3600000;

    public static function getRootSectionBySubsection($iblockID, $subsectId)
    {
        $iblockID = intval($iblockID);
        $subsectId = intval($subsectId);
        if(!Loader::includeModule('iblock') || $iblockID <= 0 || $subsectId <= 0)
        {
            return false;
        }

        $cacheId = __METHOD__.'ib'.$iblockID.'sect'.$subsectId;
        $cacheDir = static::IV_CACHE_PATH.__FUNCTION__;
        $cache = Cache::createInstance();
        $taggedCache = Application::getInstance()->getTaggedCache();

        $result = [];
        if ($cache->initCache(static::CACHE_TIME, $cacheId, $cacheDir))
        {
            $result = $cache->getVars();
        }
        elseif ($cache->startDataCache())
        {
            $taggedCache->startTagCache($cacheDir);
            $taggedCache->registerTag('iblock_id_'.$iblockID);
            $dbResult = \CIBlockSection::GetNavChain($iblockID, $subsectId, ["ID", "DEPTH_LEVEL"]);
            while($arGroup = $dbResult->Fetch())
            {
                if ($arGroup['DEPTH_LEVEL'] == 1)
                {
                    $result[] += $arGroup['ID'];
                }
            }

            if (empty($result))
            {
                $taggedCache->abortTagCache();
                $cache->abortDataCache();
            }
            $taggedCache->endTagCache();
            $cache->endDataCache($result);
        }

        return $result;
    }
}