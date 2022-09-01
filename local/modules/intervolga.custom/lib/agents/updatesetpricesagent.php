<?php

namespace Intervolga\Custom\Agents;

use Intervolga\Custom\Import\Sets;

/**
 * Агент обновляет цену у товара-комплекта
 * Сначала проходит по товарам из комплекта (хранятся в св-ве в JSON типе),
 * забирает из этих товаров цены и затем обновляет цену у товара с этим св-ом
 */

class UpdateSetPricesAgent
{
	const IB_CATALOG_ID = 17;
	// РРЦ 2022
	const PRICE_RRP_2022_ID = 13;
	// РРЦ Константа
	const PRICE_RRP_CONSTANT_ID = 14;

	public static function run()
	{
		\CModule::IncludeModule("iblock");

		$res = \CIBlockElement::GetList([],
			[
				"IBLOCK_ID" => self::IB_CATALOG_ID
			],
			false,
			false,
			["ID"]);

		// Проходимся по всем товарам
		while ($elem = $res->GetNext())
		{
			// Выбираем св-во в котором хранится информация о комплекте
			$rsProperty = \CIBlockElement::GetProperty(
				self::IB_CATALOG_ID,
				$elem["ID"],
				[],
				["CODE" => "COMPOSITION"]);

			// Старая цена
			$price = 0;
			// Новая цена
			$price_discount = 0;

			if ($property = $rsProperty->Fetch())
			{
				if (is_array($value = $property['VALUE']))
				{
					$arItem = Sets::getSet($value['TEXT']);

					foreach ($arItem['SET'] as $item)
					{
						$db_res = \CPrice::GetList(
							array(),
							array(
								"PRODUCT_ID" => $item['ID'],
								"CATALOG_GROUP_ID" => self::PRICE_RRP_2022_ID
							)
						);
						if ($arRrp2022 = $db_res->Fetch())
						{
							$item['PRICE_DISCOUNT'] = $arRrp2022["PRICE"];
						}
						else
						{
							$item['PRICE_DISCOUNT'] = $item['PRICE'];
						}
						$db_res = \CPrice::GetList(
							array(),
							array(
								"PRODUCT_ID" => $item['ID'],
								"CATALOG_GROUP_ID" => self::PRICE_RRP_CONSTANT_ID
							)
						);

						if ($arRrpConstant = $db_res->Fetch())
						{
							$item['PRICE'] = $arRrpConstant["PRICE"];
						}

						$price += floatval($item['PRICE']) * intval($item['AMOUNT']);
						$price_discount += floatval($item['PRICE_DISCOUNT']) * intval($item['AMOUNT']);
					}

					foreach ($arItem['OPTIONAL'] as $item)
					{
						$db_res = \CPrice::GetList(
							array(),
							array(
								"PRODUCT_ID" => $item['ID'],
								"CATALOG_GROUP_ID" => self::PRICE_RRP_2022_ID
							)
						);
						if ($arRrp2022 = $db_res->Fetch())
						{
							$item['PRICE_DISCOUNT'] = $arRrp2022["PRICE"];
						}
						else
						{
							$item['PRICE_DISCOUNT'] = $item['PRICE'];
						}
						$db_res = \CPrice::GetList(
							array(),
							array(
								"PRODUCT_ID" => $item['ID'],
								"CATALOG_GROUP_ID" => self::PRICE_RRP_CONSTANT_ID
							)
						);
						if ($arRrpConstant = $db_res->Fetch())
						{
							$item['PRICE'] = $arRrpConstant["PRICE"];
						}

						if ($item['DEFAULT'])
						{
							$price_discount += floatval($item['PRICE_DISCOUNT']) * intval($item['AMOUNT']);
							$price += floatval($item['PRICE']) * intval($item['AMOUNT']);
						}
					}
				}
			}
			// Св-ва нет -> устанавлиаем текузую цену товара
			else
			{
				$db_res = \CPrice::GetList(
					array(),
					array(
						"PRODUCT_ID" => $elem['ID'],
						"CATALOG_GROUP_ID" => self::PRICE_RRP_2022_ID
					)
				);
				if ($ar_res13 = $db_res->Fetch())
				{
					$item['PRICE_DISCOUNT'] = $ar_res13["PRICE"];
				}
				else
				{
					$item['PRICE_DISCOUNT'] = $item['PRICE'];
				}

				$price_discount += floatval($item['PRICE_DISCOUNT']);

				$db_res = \CPrice::GetList(
					array(),
					array(
						"PRODUCT_ID" => $elem['ID'],
						"CATALOG_GROUP_ID" => self::PRICE_RRP_CONSTANT_ID
					)
				);
				if ($ar_res14 = $db_res->Fetch())
				{
					$item['PRICE'] = $ar_res14["PRICE"];
				}
				$price += $item['PRICE'];
			}

			if ($price != 0 || $price_discount != 0)
			{
				$arFields = Array(
					"PRODUCT_ID" => $elem["ID"],
				);

				$resPrice = \CPrice::GetList(
					array(),
					array(
						"PRODUCT_ID" => $elem["ID"],
						"CATALOG_GROUP_ID" => self::PRICE_RRP_2022_ID
					)
				);

				$obPrice = new \CPrice;

				if ($curPrice = $resPrice->Fetch())
				{
					$arFields["PRICE"] = $price_discount;
					$arFields["CATALOG_GROUP_ID"] = self::PRICE_RRP_2022_ID;
					$obPrice->Update($curPrice["ID"], $arFields);
				}
				else
				{
					\CPrice::Add([
						"PRODUCT_ID" => $elem["ID"],
						"CATALOG_GROUP_ID" => self::PRICE_RRP_2022_ID,
						"PRICE" => $price_discount,
						"CURRENCY" => "RUB"
					]);
				}

				$resPrice = \CPrice::GetList(
					array(),
					array(
						"PRODUCT_ID" => $elem["ID"],
						"CATALOG_GROUP_ID" => self::PRICE_RRP_CONSTANT_ID
					)
				);

				if ($curPrice = $resPrice->Fetch())
				{
					$arFields["PRICE"] = $price;
					$arFields["CATALOG_GROUP_ID"] = self::PRICE_RRP_CONSTANT_ID;
					$obPrice->Update($curPrice["ID"], $arFields);
				}
				else
				{
					\CPrice::Add([
						"PRODUCT_ID" => $elem["ID"],
						"CATALOG_GROUP_ID" => self::PRICE_RRP_CONSTANT_ID,
						"PRICE" => $price,
						"CURRENCY" => "RUB"
					]);
				}
			}

		}

		return __METHOD__."();";
	}
}