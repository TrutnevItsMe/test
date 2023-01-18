<?php

use Intervolga\Custom\ORM\MagazinyPartnerovTable;
use Intervolga\Custom\ORM\PartneryTable;
use Intervolga\Custom\ORM\KontragentyTable;

class PartnersStores extends CBitrixComponent
{
	static $COORDS_DELIMITER;

	public function executeComponent()
	{
		if (strlen($this->arParams["COORDINATES_DELIMITER"])) {
			static::$COORDS_DELIMITER = $this->arParams["COORDINATES_DELIMITER"];
		} else {
			static::$COORDS_DELIMITER = "_";
		}

		$this->arResult["ITEMS"] = static::getPartnersStores();
		$this->arResult["MAP_FIELDS"] = static::getMapFields();

		if ($this->arParams["USE_FILTER"] === "Y") {
			$this->arResult["MAP_FILTER_VALUES"] = static::getMapFilterValues($this->arParams["FILTER_VALUES"]);
		}

		if ($this->arParams["USE_PAGINATION"] === "Y") {
			if (is_numeric($this->arParams["PAGINATION_COUNT_ELEMENTS"])
				&& $this->arParams["PAGINATION_COUNT_ELEMENTS"] > 0) {
				$pageSize = intval($this->arParams["PAGINATION_COUNT_ELEMENTS"]);
			} else {
				$pageSize = 6;
			}

			$nav = new \Bitrix\Main\UI\PageNavigation("page");

			$nav->allowAllRecords(true)
				->setPageSize($pageSize)
				->setRecordCount(count($this->arResult["ITEMS"]))
				->initFromUri();

			// Заполняем массив ID элементов для текущей пагинации
			$this->arResult["PAGINATION_ELEMENT_IDS"] = [];
			$arrayKeys = array_keys($this->arResult["ITEMS"]);
			$index = $nav->getOffset();

			for ($i = 0; $i < $pageSize; ++$i) {
				try {
					$this->arResult["PAGINATION_ELEMENT_IDS"][] = $arrayKeys[$index];
				} catch (Exception $e) {
					break;
				}

				++$index;
			}

			global $APPLICATION;

			ob_start();
			$APPLICATION->IncludeComponent(
				"bitrix:main.pagenavigation",
				$this->arParams["PAGINATION_TEMPLATE"],
				[
					"NAV_OBJECT" => $nav
				]
			);
			$navString = ob_get_clean();

			$this->arResult["NAV_STRING"] = $navString;
		}

		if ($this->arParams["USE_MAP"] === "Y")
		{
			$apiKey =  \Bitrix\Main\Config\Option::get('fileman', 'yandex_map_api_key', '');
			static::includeMapScript($apiKey);
			$this->arResult["MAP_ID"] = "MAP_" . rand();
		}

		$this->includeComponentTemplate();
	}

	public static function getPartnersStores(): array
	{
		$partnersStores = [];

		$rsShops = MagazinyPartnerovTable::getList([
			"order" => ["ID" => "ASC"],
			"select" => ["*"]
		]);

		while ($shop = $rsShops->fetch()) {
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

		if (count($explodedCoords) == 2) {
			$arCoordinates["x"] = $explodedCoords[0];
			$arCoordinates["y"] = $explodedCoords[1];
		}

		return $arCoordinates;
	}

	/**
	 * Возвращает map поля фильтрации со значением
	 *
	 * [
	 *    <FIELD_CODE> => [
	 *        <VALUE> => [          -- Значение поля
	 *            ID => string,     -- ID записи в HLB
	 *            COORDINATES => [  -- Координаты магазина партнера
	 *                x => string,
	 *                y => string
	 *            ]
	 *        ],
	 *        ...
	 *    ],
	 *    ...
	 * ]
	 *
	 * @param array $filterFields
	 * @return array
	 */
	public static function getMapFilterValues(array $filterFields): array
	{
		$mapFilter = [];

		if (!empty($filterFields)) {
			$rsFilterValues = MagazinyPartnerovTable::getList([
				"select" => array_merge(
					["ID", "UF_KOORDINATY"],
					$filterFields
				)
			]);

			while ($arFilterValues = $rsFilterValues->fetch()) {
				foreach ($filterFields as $filterField) {
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
	 *    <FIELD_CODE> => <DISPLAY_FIELD>,
	 *    ...
	 * ]
	 *
	 * @return array
	 */
	public static function getMapFields(): array
	{
		$mapFields = [];
		$mapFieldsShops = MagazinyPartnerovTable::getMap();

		foreach ($mapFieldsShops as $field => $arMapField)
		{
			$mapFields[$field] = $mapFieldsShops[$field]["title"];
		}

		return $mapFields;
	}

	public static function includeMapScript($apiKey = "")
	{
		$locale = "";
		$scheme = (CMain::IsHTTPS() ? "https" : "http");
		$yandexVersion = "2.1";

		switch (LANGUAGE_ID)
		{
			case 'ru':
				$locale = 'ru-RU';
				break;
			case 'ua':
				$locale = 'ru-UA';
				break;
			case 'tk':
				$locale = 'tr-TR';
				break;
			default:
				$locale = 'en-US';
				break;
		}

		if($apiKey == '')
		{
			$host = 'api-maps.yandex.ru';
		}
		else
		{
			$host = 'enterprise.api-maps.yandex.ru';
		}

		$scriptUrl = $scheme.'://'.$host.'/'.$yandexVersion.'/?load=package.full&mode=release&lang='.$locale.'&wizard=bitrix';

		if($apiKey <> '')
		{
			$scriptUrl .= '&apikey='.$apiKey;
		}

		?>
		<script src="<?=$scriptUrl?>"></script>
		<?php

	}
}

