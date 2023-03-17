<?php
namespace Intervolga\Custom\Exchange;

class Cml extends \CIBlockCMLImport
{
	function ImportElementPrices($arXMLElement, &$counter, $arParent = false)
	{
		/** @global CMain $APPLICATION */
		global $APPLICATION;
		static $catalogs = array();

		$arElement = array(
			"ID" => 0,
			"XML_ID" => $arXMLElement[$this->mess["IBLOCK_XML2_ID"]],
		);

		$hashPosition = strrpos($arElement["XML_ID"], "#");
		if (
			$this->use_offers
			&& $hashPosition === false && !$this->force_offers
			&& isset($this->PROPERTY_MAP["CML2_LINK"])
			&& isset($this->arProperties[$this->PROPERTY_MAP["CML2_LINK"]])
		)
		{
			$IBLOCK_ID = $this->arProperties[$this->PROPERTY_MAP["CML2_LINK"]]["IBLOCK_ID"];

			if (!isset($catalogs[$IBLOCK_ID]))
			{
				$catalogs[$IBLOCK_ID] = true;

				$rs = \CCatalog::GetList(array(),array("IBLOCK_ID" => $IBLOCK_ID));
				if (!$rs->Fetch())
				{
					$obCatalog = new \CCatalog();
					$boolFlag = $obCatalog->Add(array(
						"IBLOCK_ID" => $IBLOCK_ID,
						"YANDEX_EXPORT" => "N",
						"SUBSCRIPTION" => "N",
					));
					if (!$boolFlag)
					{
						if ($ex = $APPLICATION->GetException())
							$this->LAST_ERROR = $ex->GetString();
						return 0;
					}
				}
			}
		}
		else
		{
			$IBLOCK_ID = $this->next_step["IBLOCK_ID"];
		}

		$obElement = new \CIBlockElement;
		$rsElement = $obElement->GetList(
			Array("ID"=>"asc"),
			Array("XML_ID" => "%#".$arElement["XML_ID"], "IBLOCK_ID" => $IBLOCK_ID),
			false, false,
			Array("ID", "TMP_ID", "ACTIVE")
		);

		$arDBElement = $rsElement->Fetch();

		if($arDBElement)
		{
			$arElement["ID"] = $arDBElement["ID"];
		}
		else
		{
			$rsElement = $obElement->GetList(
				Array("ID"=>"asc"),
				Array("=XML_ID" => $arElement["XML_ID"], "IBLOCK_ID" => $IBLOCK_ID),
				false, false,
				Array("ID", "TMP_ID", "ACTIVE")
			);

			$arDBElement = $rsElement->Fetch();

			if($arDBElement)
			{
				$arElement["ID"] = $arDBElement["ID"];
			}
		}

		if(isset($arXMLElement[$this->mess["IBLOCK_XML2_STORE_AMOUNT_LIST"]]))
		{
			$arElement["STORE_AMOUNT"] = array();
			foreach($arXMLElement[$this->mess["IBLOCK_XML2_STORE_AMOUNT_LIST"]] as $storeAmount)
			{
				if(isset($storeAmount[$this->mess["IBLOCK_XML2_STORE_ID"]]))
				{
					$storeXMLID = $storeAmount[$this->mess["IBLOCK_XML2_STORE_ID"]];
					$amount = $this->ToFloat($storeAmount[$this->mess["IBLOCK_XML2_AMOUNT"]]);
					$arElement["STORE_AMOUNT"][$storeXMLID] = $amount;
				}
			}
		}
		elseif(isset($arXMLElement[$this->mess["IBLOCK_XML2_RESTS"]]))
		{
			$arElement["STORE_AMOUNT"] = array();
			foreach($arXMLElement[$this->mess["IBLOCK_XML2_RESTS"]] as $xmlRest)
			{
				foreach($xmlRest as $storeAmount)
				{
					if(is_array($storeAmount))
					{
						if (isset($storeAmount[$this->mess["IBLOCK_XML2_ID"]]))
						{
							$storeXMLID = $storeAmount[$this->mess["IBLOCK_XML2_ID"]];
							$amount = $this->ToFloat($storeAmount[$this->mess["IBLOCK_XML2_AMOUNT"]]);
							$arElement["STORE_AMOUNT"][$storeXMLID] = $amount;
						}
					}
					else
					{
						if ($storeAmount <> '')
						{
							$amount = $this->ToFloat($storeAmount);
							$arElement["QUANTITY"] = $amount;
						}
					}
				}
			}
		}
		elseif(
			$arParent
			&& (
				array_key_exists($this->mess["IBLOCK_XML2_STORES"], $arXMLElement)
				|| array_key_exists($this->mess["IBLOCK_XML2_STORE"], $arXMLElement)
			)
		)
		{
			$arElement["STORE_AMOUNT"] = array();
			$rsStores = $this->_xml_file->GetList(
				array("ID" => "asc"),
				array(
					"><LEFT_MARGIN" => array($arParent["LEFT_MARGIN"], $arParent["RIGHT_MARGIN"]),
					"NAME" => $this->mess["IBLOCK_XML2_STORE"],
				),
				array("ID", "ATTRIBUTES")
			);
			while ($arStore = $rsStores->Fetch())
			{
				if($arStore["ATTRIBUTES"] <> '')
				{
					$info = unserialize($arStore["ATTRIBUTES"], ['allowed_classes' => false]);
					if(
						is_array($info)
						&& array_key_exists($this->mess["IBLOCK_XML2_STORE_ID"], $info)
						&& array_key_exists($this->mess["IBLOCK_XML2_STORE_AMOUNT"], $info)
					)
					{
						$arElement["STORE_AMOUNT"][$info[$this->mess["IBLOCK_XML2_STORE_ID"]]] = $this->ToFloat($info[$this->mess["IBLOCK_XML2_STORE_AMOUNT"]]);
					}
				}
			}
		}

		if(isset($arElement["STORE_AMOUNT"]))
			$this->ImportStoresAmount($arElement["STORE_AMOUNT"], $arElement["ID"], $counter);

		if($arDBElement)
		{
			$arProduct = array(
				"ID" => $arElement["ID"],
			);

			if(isset($arXMLElement[$this->mess["IBLOCK_XML2_PRICES"]]))
			{
				$arElement["PRICES"] = array();
				foreach($arXMLElement[$this->mess["IBLOCK_XML2_PRICES"]] as $price)
				{
					if(
						isset($price[$this->mess["IBLOCK_XML2_PRICE_TYPE_ID"]])
						&& array_key_exists($price[$this->mess["IBLOCK_XML2_PRICE_TYPE_ID"]], $this->PRICES_MAP)
					)
					{
						$price["PRICE"] = $this->PRICES_MAP[$price[$this->mess["IBLOCK_XML2_PRICE_TYPE_ID"]]];
						$arElement["PRICES"][] = $price;

						if(
							array_key_exists($this->mess["IBLOCK_XML2_MEASURE"], $price)
							&& !isset($arProduct["MEASURE"])
						)
						{
							$tmp = $this->convertBaseUnitFromXmlToPropertyValue($price[$this->mess["IBLOCK_XML2_MEASURE"]]);
							if ($tmp["DESCRIPTION"] > 0)
								$arProduct["MEASURE"] = $tmp["DESCRIPTION"];
						}
					}
				}

				$arElement["DISCOUNTS"] = array();
				if(isset($arXMLElement[$this->mess["IBLOCK_XML2_DISCOUNTS"]]))
				{
					foreach($arXMLElement[$this->mess["IBLOCK_XML2_DISCOUNTS"]] as $discount)
					{
						if(
							isset($discount[$this->mess["IBLOCK_XML2_DISCOUNT_CONDITION"]])
							&& $discount[$this->mess["IBLOCK_XML2_DISCOUNT_CONDITION"]] === $this->mess["IBLOCK_XML2_DISCOUNT_COND_VOLUME"]
						)
						{
							$discount_value = $this->ToInt($discount[$this->mess["IBLOCK_XML2_DISCOUNT_COND_VALUE"]]);
							$discount_percent = $this->ToFloat($discount[$this->mess["IBLOCK_XML2_DISCOUNT_COND_PERCENT"]]);
							if($discount_value > 0 && $discount_percent > 0)
								$arElement["DISCOUNTS"][$discount_value] = $discount_percent;
						}
					}
				}
			}

			if($this->bCatalog && array_key_exists($this->mess["IBLOCK_XML2_AMOUNT"], $arXMLElement))
			{
				$arElement["QUANTITY_RESERVED"] = 0;
				if($arElement["ID"])
				{
					$iterator = \Bitrix\Catalog\Model\Product::getList([
						'select' => ['ID', 'QUANTITY_RESERVED'],
						'filter' => ['=ID' => $arDBElement['ID']]
					]);
					$arElementTmp = $iterator->fetch();
					if (!empty($arElementTmp) && is_array($arElementTmp) && isset($arElementTmp["QUANTITY_RESERVED"]))
						$arElement["QUANTITY_RESERVED"] = (float)$arElementTmp["QUANTITY_RESERVED"];
					unset($arElementTmp);
					unset($iterator);
				}
				$arElement["QUANTITY"] = $this->ToFloat($arXMLElement[$this->mess["IBLOCK_XML2_AMOUNT"]]) - $arElement["QUANTITY_RESERVED"];
			}

			if(isset($arElement["PRICES"]) && $this->bCatalog)
			{
				if(isset($arElement["QUANTITY"]))
					$arProduct["QUANTITY"] = (float)$arElement["QUANTITY"];
				elseif(isset($arElement["STORE_AMOUNT"]) && !empty($arElement["STORE_AMOUNT"]))
					$arProduct["QUANTITY"] = $this->countTotalQuantity($arElement["STORE_AMOUNT"]);

				$rsWeight = \CIBlockElement::GetProperty($IBLOCK_ID, $arElement["ID"], array(), array("CODE" => "CML2_TRAITS"));
				while($arWeight = $rsWeight->Fetch())
				{
					if($arWeight["DESCRIPTION"] == $this->mess["IBLOCK_XML2_WEIGHT"])
						$arProduct["WEIGHT"] = $this->ToFloat($arWeight["VALUE"])*1000;
				}

				$rsUnit = \CIBlockElement::GetProperty($IBLOCK_ID, $arElement["ID"], array(), array("CODE" => "CML2_BASE_UNIT"));
				while($arUnit = $rsUnit->Fetch())
				{
					if($arUnit["DESCRIPTION"] > 0)
						$arProduct["MEASURE"] = $arUnit["DESCRIPTION"];
				}

				//Here start VAT handling

				//Check if all the taxes exists in BSM catalog
				$arTaxMap = array();
				$rsTaxProperty = \CIBlockElement::GetProperty($IBLOCK_ID, $arElement["ID"], array("sort" => "asc"),
					array("CODE" => "CML2_TAXES"));
				while($arTaxProperty = $rsTaxProperty->Fetch())
				{
					if(
						$arTaxProperty["VALUE"] <> ''
						&& $arTaxProperty["DESCRIPTION"] <> ''
						&& !array_key_exists($arTaxProperty["DESCRIPTION"], $arTaxMap)
					)
					{
						$arTaxMap[$arTaxProperty["DESCRIPTION"]] = array(
							"RATE" => $this->ToFloat($arTaxProperty["VALUE"]),
							"ID" => $this->CheckTax($arTaxProperty["DESCRIPTION"], $this->ToFloat($arTaxProperty["VALUE"])),
						);
					}
				}

				//Try to search in main element
				if (
					!$arTaxMap
					&& $this->use_offers
					&& $hashPosition !== false
					&& $this->arProperties[$this->PROPERTY_MAP["CML2_LINK"]]["LINK_IBLOCK_ID"] > 0
				)
				{
					$rsLinkProperty = \CIBlockElement::GetProperty($IBLOCK_ID, $arElement["ID"], array("sort" => "asc"), array("CODE" => "CML2_LINK"));
					if( ($arLinkProperty = $rsLinkProperty->Fetch()) && ($arLinkProperty["VALUE"] > 0))
					{
						$rsTaxProperty = \CIBlockElement::GetProperty($this->arProperties[$this->PROPERTY_MAP["CML2_LINK"]]["LINK_IBLOCK_ID"], $arLinkProperty["VALUE"], array("sort" => "asc"), array("CODE" => "CML2_TAXES"));
						while($arTaxProperty = $rsTaxProperty->Fetch())
						{
							if(
								$arTaxProperty["VALUE"] <> ''
								&& $arTaxProperty["DESCRIPTION"] <> ''
								&& !array_key_exists($arTaxProperty["DESCRIPTION"], $arTaxMap)
							)
							{
								$arTaxMap[$arTaxProperty["DESCRIPTION"]] = array(
									"RATE" => $this->ToFloat($arTaxProperty["VALUE"]),
									"ID" => $this->CheckTax($arTaxProperty["DESCRIPTION"], $this->ToFloat($arTaxProperty["VALUE"])),
								);
							}
						}
					}
				}

				//First find out if all the prices have TAX_IN_SUM true
				$TAX_IN_SUM = "Y";
				foreach($arElement["PRICES"] as $price)
				{
					if($price["PRICE"]["TAX_IN_SUM"] !== "true")
					{
						$TAX_IN_SUM = "N";
						break;
					}
				}
				//If there was found not included tax we'll make sure
				//that all prices has the same flag
				if($TAX_IN_SUM === "N")
				{
					foreach($arElement["PRICES"] as $price)
					{
						if($price["PRICE"]["TAX_IN_SUM"] !== "false")
						{
							$TAX_IN_SUM = "Y";
							break;
						}
					}
					//Check if there is a mix of tax in sum
					//and correct it by recalculating all the prices
					if($TAX_IN_SUM === "Y")
					{
						foreach($arElement["PRICES"] as $key=>$price)
						{
							if($price["PRICE"]["TAX_IN_SUM"] !== "true")
							{
								$TAX_NAME = $price["PRICE"]["TAX_NAME"];
								if(array_key_exists($TAX_NAME, $arTaxMap))
								{
									$PRICE_WO_TAX = $this->ToFloat($price[$this->mess["IBLOCK_XML2_PRICE_FOR_ONE"]]);
									$PRICE = $PRICE_WO_TAX + ($PRICE_WO_TAX / 100.0 * $arTaxMap[$TAX_NAME]["RATE"]);
									$arElement["PRICES"][$key][$this->mess["IBLOCK_XML2_PRICE_FOR_ONE"]] = $PRICE;
								}
							}
						}
					}
				}

				if ($TAX_IN_SUM == "Y" && $arTaxMap)
				{
					$vat = current($arTaxMap);
					$arProduct["VAT_ID"] = $vat["ID"];
				}
				else
				{
					foreach($arElement["PRICES"] as $price)
					{
						$TAX_NAME = $price["PRICE"]["TAX_NAME"];
						if(array_key_exists($TAX_NAME, $arTaxMap))
						{
							$arProduct["VAT_ID"] = $arTaxMap[$TAX_NAME]["ID"];
							break;
						}
					}
				}

				$arProduct["VAT_INCLUDED"] = $TAX_IN_SUM;

				$productCache = \Bitrix\Catalog\Model\Product::getCacheItem($arProduct['ID'], true);
				if (!empty($productCache))
				{
					$productResult = \Bitrix\Catalog\Model\Product::update(
						$arProduct['ID'],
						array(
							'fields' => $arProduct,
							'external_fields' => array(
								'IBLOCK_ID' => $IBLOCK_ID
							)
						)
					);
				}
				else
				{
					$productResult = \Bitrix\Catalog\Model\Product::add(
						array(
							'fields' => $arProduct,
							'external_fields' => array(
								'IBLOCK_ID' => $IBLOCK_ID
							)
						)
					);
				}
				if ($productResult->isSuccess())
				{
					//TODO: replace this code after upload measure ratio from 1C
					$iterator = \Bitrix\Catalog\MeasureRatioTable::getList(array(
						'select' => array('ID'),
						'filter' => array('=PRODUCT_ID' => $arElement['ID'])
					));
					$ratioRow = $iterator->fetch();
					if (empty($ratioRow))
					{
						$ratioResult = \Bitrix\Catalog\MeasureRatioTable::add(array(
							'PRODUCT_ID' => $arElement['ID'],
							'RATIO' => 1,
							'IS_DEFAULT' => 'Y'
						));
						unset($ratioResult);
					}
					unset($ratioRow, $iterator);
				}

				$this->SetProductPrice($arElement["ID"], $arElement["PRICES"], $arElement["DISCOUNTS"]);
				\Bitrix\Iblock\PropertyIndex\Manager::updateElementIndex($IBLOCK_ID, $arElement["ID"]);
			}
			elseif(
				$this->bCatalog
				&& (
					(isset($arElement["STORE_AMOUNT"]) && !empty($arElement["STORE_AMOUNT"]))
					|| isset($arElement["QUANTITY"])
				)
			)
			{
				$iterator = \Bitrix\Catalog\Model\Product::getList([
					'select' => ['ID', 'QUANTITY_RESERVED'],
					'filter' => ['=ID' => $arElement['ID']]
				]);
				$arElementTmp = $iterator->fetch();
				if (!empty($arElementTmp) && is_array($arElementTmp))
				{
					$quantityReserved = 0;
					if (isset($arElementTmp["QUANTITY_RESERVED"]))
						$quantityReserved = (float)$arElementTmp["QUANTITY_RESERVED"];
					$internalFields = [];
					if (isset($arElement["STORE_AMOUNT"]) && !empty($arElement["STORE_AMOUNT"]))
					{
						$internalFields['QUANTITY'] = $this->countTotalQuantity($arElement["STORE_AMOUNT"]);
					}
					elseif (isset($arElement["QUANTITY"]))
					{
						$internalFields['QUANTITY'] = $arElement["QUANTITY"];
					}
					if (!empty($internalFields))
					{
						$internalFields['QUANTITY'] -= $quantityReserved;
						$internalResult = \Bitrix\Catalog\Model\Product::update(
							$arElement['ID'],
							array(
								'fields' => $internalFields,
								'external_fields' => array(
									'IBLOCK_ID' => $IBLOCK_ID
								)
							)
						);
						if (!$internalResult->isSuccess())
						{

						}
						unset($internalResult);
					}
					unset($internalFields);
					unset($quantityReserved);
				}
				unset($arElementTmp);
				unset($iterator);
			}
		}

		$counter["UPD"]++;
		return $arElement["ID"];
	}

}