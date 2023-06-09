<?php

namespace Intervolga\Custom\Tools;

class RestsUtil
{
	/**
	 * Возвращает массив с описанием остатков из настроек модуля
	 *
	 * @param $count
	 * @return array
	 */
	public static function getQuantityArray($count)
	{
		global $USER;

		$conditions = unserialize(\Bitrix\Main\Config\Option::get("intervolga.custom", "RESTS_CONDITION"));

		if ($USER->IsAuthorized())
		{
			$restText = static::parseRestsConditions($count, $conditions["AUTH"]);
		}
		else
		{
			$restText = static::parseRestsConditions($count, $conditions["NO_AUTH"]);
		}

		if ($count)
		{
			$classIcon = "stock stock_range_3";
		}
		else
		{
			$classIcon = "order";
		}

		return [
			"QUANTITY" => $count,
			"TEXT" => $restText,
			"HTML" => <<<HTML
				<div class="item-stock">
				<span class="icon $classIcon"></span>
				<span class="value">$restText</span>
				</div>
			HTML
		];
	}

	/**
	 * Возвращает словесное описание для остатков
	 *
	 * @param $count
	 * @param $conditions
	 * @return false|mixed|string
	 */
	protected static function parseRestsConditions($count, $conditions)
	{
		$mapConditions = static::getConditionDescription($count, $conditions);
		$conds = array_keys($mapConditions);

		if (in_array("=", $conds))
		{
			return current($mapConditions["="]);
		}
		elseif (in_array(">=", $conds))
		{
			$mergedArray = $mapConditions[">="];

			if (in_array(">", $conds))
			{
				$mergedArray +=  $mapConditions[">"];
			}

			$i = max(array_keys($mergedArray));
			return $mergedArray[$i];
		}
		elseif (in_array(">", $conds))
		{
			$i = max(array_keys($mapConditions[">"]));
			return $mapConditions[">"][$i];
		}
		elseif (in_array("<=", $conds))
		{
			$mergedArray = $mapConditions["<="];

			if (in_array("<", $conds))
			{
				$mergedArray +=  $mapConditions["<"];
			}

			$i = max(array_keys($mergedArray));
			return $mergedArray[$i];
		}
		elseif (in_array("<", $conds))
		{
			$i = max(array_keys($mapConditions["<"]));
			return $mapConditions["<"][$i];
		}

		return "";
	}

	/**
	 * Возвращает все условия, удовлетворяющие данным остаткам
	 *
	 * @param $count
	 * @param $conditions
	 * @return array
	 */
	protected static function getConditionDescription($count, $conditions)
	{
		$arResult = [];
		$re = "/^(?<condition>[<>=]*)(?<value>[\w\d\s]*)$/"; // >=123

		foreach ($conditions as $condition => $description)
		{
			preg_match($re, $condition, $reResult);

			$cond = $reResult["condition"];

			if (!$cond)
			{
				$cond = ">";
			}

			if (static::compare($count, $cond, $reResult["value"]))
			{
				$arResult[$cond][$reResult["value"]] = $description;
			}
		}

		return $arResult;
	}

	/**
	 * Сравнивает 2 значения по условию $condition
	 *
	 * @param $value1
	 * @param string $condition
	 * @param $value2
	 * @return bool
	 */
	public static function compare($value1, string $condition, $value2)
	{
		switch ($condition)
		{
			case ">":
				return $value1 > $value2;
			case ">=":
				return $value1 >= $value2;
			case "<":
				return $value1 < $value2;
			case "<=":
				return $value1 <= $value2;
			case "=":
			case "==":
				return $value1 == $value2;
		}

		throw new \Exception("No valid condition: $condition");
	}

	/**
	 * Получить наличие комплекта на складе по нижней границе количества среди всех товаров комплекта
	 * @param array $storesIds - массив INT
	 * @param array $items - $arResult['SET']
	 * @return array
	 */
	public static function getMinStockAvail(array $storesIds, array $items): array
	{
		$mainItemsIds = array_column($items['SET'], "ID");
		$optionalItemsIds = [];
		$amount = [];
		
		foreach ($items['OPTIONAL'] as $item) {
			if ($item['DEFAULT']) {
				$optionalItemsIds[] = $item['ID'];
			}
		}
		$itemsIds = array_merge($mainItemsIds, $optionalItemsIds);
		
		$storesProductsAmount = \Bitrix\Catalog\StoreProductTable::getList(
			[
				'filter' => [
					'=PRODUCT_ID' => $itemsIds,
					'=STORE.ACTIVE' => 'Y',
					'=STORE_ID' => $storesIds
				],
				'select' => [
					'AMOUNT',
					'STORE_ID',
				],
			]
		)->fetchAll();

		foreach ($storesProductsAmount as $store) {
			if (!isset($amount[$store['STORE_ID']])) {
				$amount[$store['STORE_ID']] = $store['AMOUNT'];
			}
			if ($store['AMOUNT'] < $amount[$store['STORE_ID']]) {
				$amount[$store['STORE_ID']] = $store['AMOUNT'];
			}
		}

		return $amount;
	}
}
