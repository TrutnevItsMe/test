<?
function getPropertyByCode($propertyCollection, $code){
	foreach ($propertyCollection as $property){
		if($property->getField('CODE') == $code){
			return $property;
		}
	}
}
function checkNewVersionExt($module="main"){
	if($info = CModule::CreateModuleObject($module)){
		$testVersion = '16.0.30';

		if(CheckVersion($info->MODULE_VERSION, $testVersion)){
			return true;
		}else{
			return false;
		}
	}
	return false;
}

function getFiledPropsFasView($iblockID, $offerID, $propCodes){
	$sortIndex = 1;
    $rsProps = CIBlockElement::GetProperty(
        $iblockID,
        $offerID,
        array("sort"=>"asc", "enum_sort" => "asc", "value_id"=>"asc"),
        array("EMPTY"=>"N")
    );
    $propCodes = array_fill_keys($propCodes, 1);
    
    while ($oneProp = $rsProps->Fetch())
    {
        if (!isset($propCodes[$oneProp['CODE']]))
            continue;
        $propID = (isset($propCodes[$oneProp['CODE']]) ? $oneProp['CODE'] : $oneProp['ID']);

        $userTypeProp = false;
        $userType = null;
        if (isset($oneProp['USER_TYPE']) && !empty($oneProp['USER_TYPE']))
        {
            $userTypeDescr = CIBlockProperty::GetUserType($oneProp['USER_TYPE']);
            if (isset($userTypeDescr['GetPublicViewHTML']))
            {
                $userTypeProp = true;
                $userType = $userTypeDescr['GetPublicViewHTML'];
            }
        }

        if ($userTypeProp)
        {
            $displayValue = (string)call_user_func_array($userType,
                array(
                    $oneProp,
                    array('VALUE' => $oneProp['VALUE']),
                    array('MODE' => 'SIMPLE_TEXT')
                ));
            $result[] = array(
                "NAME" => $oneProp["NAME"],
                "CODE" => $propID,
                "VALUE" => $displayValue,
                "SORT" => $sortIndex++,
            );
        }
        else
        {
            switch ($oneProp["PROPERTY_TYPE"])
            {
            case "S":
            case "N":
                $result[] = array(
                    "NAME" => $oneProp["NAME"],
                    "CODE" => $propID,
                    "VALUE" => $oneProp["VALUE"],
                    "SORT" => $sortIndex++,
                );
                break;
            case "G":
                $rsSection = CIBlockSection::GetList(
                    array(),
                    array("=ID"=>$oneProp["VALUE"]),
                    false,
                    array('ID', 'NAME')
                );
                if ($arSection = $rsSection->Fetch())
                {
                    $result[] = array(
                        "NAME" => $oneProp["NAME"],
                        "CODE" => $propID,
                        "VALUE" => $arSection["NAME"],
                        "SORT" => $sortIndex++,
                    );
                }
                break;
            case "E":
                $rsElement = CIBlockElement::GetList(
                    array(),
                    array("=ID"=>$oneProp["VALUE"]),
                    false,
                    false,
                    array("ID", "NAME")
                );
                if ($arElement = $rsElement->Fetch())
                {
                    $result[] = array(
                        "NAME" => $oneProp["NAME"],
                        "CODE" => $propID,
                        "VALUE" => $arElement["NAME"],
                        "SORT" => $sortIndex++,
                    );
                }
                break;
            case "L":
                $result[] = array(
                    "NAME" => $oneProp["NAME"],
                    "CODE" => $propID,
                    "VALUE" => $oneProp["VALUE_ENUM"],
                    "SORT" => $sortIndex++,
                );
                break;
            }
        }
    }
    return $result;
}

function initAffiliate($order, $siteId = 's1') {
    $affiliateID = \CSaleAffiliate::GetAffiliate();
    if ($affiliateID > 0)
    {
        $dbAffiliate = \CSaleAffiliate::GetList([], ["SITE_ID" => $siteId, "ID" => $affiliateID]);
        $arAffiliates = $dbAffiliate->Fetch();
        if (count($arAffiliates) > 1)
            $order->setField('AFFILIATE_ID', $affiliateID);
    }
}

function placeOrder($registeredUserID, $basketUserID, $newOrder, $arOrderDat, $POST){
	\Bitrix\Sale\DiscountCouponsManager::init();
	$deliveryName = $paymentName = "";
	if(class_exists('\Bitrix\Sale\Delivery\Services\Manager'))
	{
		$service = \Bitrix\Sale\Delivery\Services\Manager::getObjectById($newOrder["DELIVERY_ID"]);
		if(is_object($service))
		{
			if ($service->isProfile())
				$arDelivery['DELIVERY_NAME'] = $service->getNameWithParent();
			else
				$arDelivery['DELIVERY_NAME'] = $service->getName();
			$deliveryName = $arDelivery["DELIVERY_NAME"];
		}
		else
		{
			$deliveryName = "QUICK_ORDER";
		}
	}
	else
	{
		$deliveryName = "QUICK_ORDER";
	}

	if(class_exists('\Bitrix\Sale\PaySystem\Manager'))
	{
		$service = \Bitrix\Sale\PaySystem\Manager::getObjectById($newOrder["PAY_SYSTEM_ID"]);
		if(is_object($service))
			$paymentName=$service->getField('NAME');
		else
			$paymentName = "QUICK_ORDER";
	}
	else
	{
		$paymentName = "QUICK_ORDER";
	}

	//$siteId = \Bitrix\Main\Context::getCurrent()->getSite();
	$siteId = $_POST['SITE_ID'];

	if (class_exists("\Bitrix\Sale\Registry") && method_exists('\Bitrix\Sale\Registry', 'getOrderClassName')) {
        $registry = \Bitrix\Sale\Registry::getInstance(\Bitrix\Sale\Registry::REGISTRY_TYPE_ORDER);
        /** @var Order $orderClassName */
        $orderClassName = $registry->getOrderClassName();

        $order = $orderClassName::create($siteId, $basketUserID);
    } else {
        $order = Bitrix\Sale\Order::create($siteId, $basketUserID);
    }

	$order->setPersonTypeId($newOrder['PERSON_TYPE_ID']);
	$order->setFieldNoDemand('USER_ID', $registeredUserID);

	/*Basket start*/
	$basket = Bitrix\Sale\Basket::loadItemsForFUser($basketUserID, $siteId)->getOrderableItems();

	// action for basket items
	/*$basketItems = $basket->getBasketItems();
	foreach ($basketItems as $basketItem){
		$basketItem->setField('PRODUCT_PROVIDER_CLASS', '\CCatalogProductProvider');
	}*/

	CSaleBasket::UpdateBasketPrices($basketUserID, $siteId);
	Bitrix\Sale\Compatible\DiscountCompatibility::stopUsageCompatible();
	$order->setBasket($basket);
	/*Basket end*/

	/*Shipment start*/
	$shipmentCollection = $order->getShipmentCollection();
	$shipment = $shipmentCollection->createItem();
	$shipment->setField('CURRENCY', $arOrderDat["CURRENCY"]);
	$shipmentItemCollection = $shipment->getShipmentItemCollection();
	foreach ($order->getBasket() as $item)
	{
		$shipmentItem = $shipmentItemCollection->createItem($item);
		$shipmentItem->setQuantity($item->getQuantity());
	}

	$shipment->setFields(
		array(
			'DELIVERY_ID' => $newOrder["DELIVERY_ID"],
			'DELIVERY_NAME' => $deliveryName
		)
	);

	$shipmentCollection->calculateDelivery();
	/*Shipment end*/

	/*Payment start*/
	$paymentCollection = $order->getPaymentCollection();
	$extPayment = $paymentCollection->createItem();
	$extPayment->setFields(
		array(
			'PAY_SYSTEM_ID' => $newOrder['PAY_SYSTEM_ID'],
			'PAY_SYSTEM_NAME' => $paymentName,
		)
	);
	/*Payment end*/

	/*affilitate*/
	initAffiliate($order, $siteId);

	$order->getDiscount()->calculate();

	$order->doFinalAction(true);

	/*Order fields start*/
	$order->setField('CURRENCY', $arOrderDat["CURRENCY"]);
	$order->setFields(
		array(
			'USER_DESCRIPTION' => $POST['ONE_CLICK_BUY']['COMMENT'],
			'COMMENTS' => GetMessage('FAST_ORDER_COMMENT'),
		)
	);
	/*Order fields end*/


	/*Props start*/
	$propertyCollection = $order->getPropertyCollection();
	if($POST['ONE_CLICK_BUY']['EMAIL']){
		$obProperty = getPropertyByCode($propertyCollection, 'EMAIL');
		if($obProperty)
			$obProperty->setValue($POST['ONE_CLICK_BUY']['EMAIL']);
	}
    if ($POST['ONE_CLICK_BUY']['PHONE']) {
        $obProperty = getPropertyByCode($propertyCollection, 'PHONE');
        if ($obProperty) {
            $obProperty->setValue($POST['ONE_CLICK_BUY']['PHONE']);
        }
    }
    if ($POST['ONE_CLICK_BUY']['FIO']) {
        $obProperty = getPropertyByCode($propertyCollection, 'FIO');
        if ($obProperty) {
            $obProperty->setValue($POST['ONE_CLICK_BUY']['FIO']);
        }
    }
	/*Props end*/

	$r=$order->save();
	if (!$r->isSuccess()){
		die(getJson(GetMessage('ORDER_CREATE_FAIL'), 'N', implode('<br />', (array)$r->getErrors())));
	}

	return $r;
}
?>