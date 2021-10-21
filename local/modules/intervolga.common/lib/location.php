<?php namespace Intervolga\Common;

use \Bitrix\Sale\Location\TypeTable;
use \Bitrix\Sale\Location\Search\Finder;

/**
 * Вспомогательный класс для работы с местоположениями Bitrix.
 * @package Intervolga\Common
 */
class Location
{
    /**
     * Получение списка выбранных месоположений для текущего сайта.
     *
     * @return array список выбранных местоположений для текущего сайта.
     */
    public static function getLocationsForCurrentSite()
    {
        return static::getLocationsForSite(SITE_ID, LANGUAGE_ID);
    }

    /**
     * Получение списка выбранных месоположений для указанного сайта ($siteId) и языка ($languageId).
     *
     * @param string $siteId идентификатор сайта.
     * @param string $languageId идентификатор языка.
     * @return array список выбранных местоположений для указанного сайта.
     */
    public static function getLocationsForSite($siteId, $languageId)
    {
        $locations = array();

        if(\Bitrix\Main\Loader::includeModule("sale"))
        {
            $params = array(
                "filter" => array(
                    "=NAME.LANGUAGE_ID" => $languageId,
                    "=TYPE_ID" => array()
                ),
                "select" => array("ID", "LEFT_MARGIN", "RIGHT_MARGIN", "NAME.NAME", "CODE")
            );
            $res = \Bitrix\Sale\Location\TypeTable::getList(array(
                'filter' => array(
                    'CODE' => array('VILLAGE', 'CITY'),
                ),
                'select' => array('ID'),
            ))->fetchAll();
            if ($res) {
                foreach ($res as $arRes)
                {
                    $params['filter']['=TYPE_ID'][] = intval($arRes['ID']);
                }
            }

            $obSiteLocationsIds = \Bitrix\Sale\Location\SiteLocationTable::getList(array(
                'filter' => array('SITE_ID' => $siteId),
                'select' => array('LOCATION_ID')
            ));

            while ($arLocId = $obSiteLocationsIds->fetch()) {
                $params['filter']['=ID'][] = $arLocId['LOCATION_ID'];
            }

            $result = \Bitrix\Sale\Location\Search\Finder::find($params, array());
            while ($item = $result->fetch()) {
                $item['NAME'] = $item['SALE_LOCATION_LOCATION_NAME_NAME'];
                $locations[] = $item;
            }

            usort($locations, function ($a, $b) {
                return strcmp($a['NAME'], $b['NAME']);
            });
        }

        return $locations;
    }

    /**
     * Получение местоположения по id.
     *
     * @param sting $locationId id местоположения.
     * @param mixed|string $siteId id сайта.
     * @param mixed|string $languageId id языка.
     * @return array|false информация о местоположении.
     */
    public static function getLocationById($locationId, $siteId = SITE_ID, $languageId = LANGUAGE_ID)
    {
        $location = array();

        $params = array(
            "filter" => array(
                "=SITE_ID" => $siteId,
                "=NAME.LANGUAGE_ID" => $languageId,
            ),
            "select" => array("ID", "LEFT_MARGIN", "RIGHT_MARGIN", "NAME.NAME")
        );

        $res = TypeTable::getList(array(
            'filter' => array('CODE' => 'CITY'),
            'select' => array('ID'),
        ))->fetch();

        if ($res) {
            $params['filter']['=TYPE_ID'] = intval($res['ID']);
            $params['filter']['=ID'] = intval($locationId);
        }

        $result = Finder::find($params, array());
        if ($item = $result->fetch()) {
            $location = $item;
        }

        return $location;
    }
}