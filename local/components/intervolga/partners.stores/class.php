<?php

use Intervolga\Custom\ORM\MagazinyPartnerovTable;
use Intervolga\Custom\ORM\PartneryTable;
use Intervolga\Custom\ORM\KontragentyTable;

class PartnersStores extends CBitrixComponent
{
	static $COORDS_DELIMITER;
	public function executeComponent()
	{
		if (strlen($this->arParams["COORDINATES_DELIMITER"]))
		{
			static::$COORDS_DELIMITER = $this->arParams["COORDINATES_DELIMITER"];
		}
		else
		{
			static::$COORDS_DELIMITER = "_";
		}

		$this->arResult["ITEMS"] = static::getPartnersStores();

		if ($this->arParams["USE_FILTER"] === "Y")
		{
			$this->arResult["MAP_FILTER_VALUES"] = static::getMapFilterValues($this->arParams["FILTER_VALUES"]);
			$this->arResult["MAP_FILTER_FIELDS"] = static::getMapFilterFields($this->arParams["FILTER_VALUES"]);
		}

		$this->includeComponentTemplate();
	}

	public static function getPartnersStores(): array
	{
		$partnersStores = [];

		$rsShops = MagazinyPartnerovTable::getList([
			"select" => ["*"]
		]);

		while ($shop = $rsShops->fetch())
		{
			$shop["COORDINATES"] = static::parseCoordinates($shop["UF_KOORDINATY"], static::$COORDS_DELIMITER);
			$shop["PARTNER"] = PartneryTable::getByXmlId($shop["UF_VLADELETS"]);
			$shop["KONTRAGENTS"] = KontragentyTable::getByPartner($shop["UF_VLADELETS"]);
			$partnersStores[$shop["ID"]] = $shop;
		}

		return $partnersStores;
	}

	/**
	 * Парсит координаты из строки
	 *
	 * @param string $coordinates
	 * @param string $delimiter
	 * @return array
	 */
	public static function parseCoordinates(string $coordinates, string $delimiter): array
	{
		$arCoordinates = [];

		$explodedCoords = explode($delimiter, $coordinates);

		if (count($explodedCoords) == 2)
		{
			$arCoordinates["x"] = $explodedCoords[0];
			$arCoordinates["y"] = $explodedCoords[1];
		}

		return $arCoordinates;
	}

	/**
	 * Возвращает map поля фильтрации со значением
	 *
	 * [
	 * 	<FIELD_CODE> => [
	 * 		<VALUE> => [          -- Значение поля
	 * 			ID => string,     -- ID записи в HLB
	 * 			COORDINATES => [  -- Координаты магазина партнера
	 * 				x => string,
	 * 				y => string
	 * 			]
	 * 		],
	 * 		...
	 * 	],
	 * 	...
	 * ]
	 *
	 * @param array $filterFields
	 * @return array
	 */
	public static function getMapFilterValues(array $filterFields): array
	{
		$mapFilter = [];

		if (!empty($filterFields))
		{
			$rsFilterValues = MagazinyPartnerovTable::getList([
				"select" => array_merge(
					["ID", "UF_KOORDINATY"],
					$filterFields
				)
			]);

			while ($arFilterValues = $rsFilterValues->fetch())
			{
				foreach ($filterFields as $filterField)
				{
					$mapFilter[$filterField][$arFilterValues[$filterField]][] = [
						"ID" => $arFilterValues["ID"],
						"COORDINATES" => static::parseCoordinates($arFilterValues["UF_KOORDINATY"], static::$COORDS_DELIMITER)
					];
				}
			}
		}

		return $mapFilter;
	}

	/**
	 * Возвращает map кода поля с выводимым названием
	 *
	 * [
	 * 	<FIELD_CODE> => <DISPLAY_FIELD>,
	 * 	...
	 * ]
	 *
	 * @param array $filterFields
	 * @return array
	 */
	public static function getMapFilterFields(array $filterFields): array
	{
		$mapFilter = [];

		if (!empty($filterFields))
		{
			$mapFieldsShops = MagazinyPartnerovTable::getMap();

			foreach ($filterFields as $filterField)
			{
				$mapFilter[$filterField] = $mapFieldsShops[$filterField]["title"];
			}
		}

		return $mapFilter;
	}
}

