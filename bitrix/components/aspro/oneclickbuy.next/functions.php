<?
if(!function_exists('json_encode')){
    function json_encode($value){
        if(is_int($value)){
            return (string)$value;
        }
        elseif(is_string($value)){
            $value = str_replace(array('\\', '/', '"', "\r", "\n", "\b", "\f", "\t"),  array('\\\\', '\/', '\"', '\r', '\n', '\b', '\f', '\t'), $value);
            $convmap = array(0x80, 0xFFFF, 0, 0xFFFF);
            $result = "";
            for ($i = mb_strlen($value) - 1; $i >= 0; $i--){
                $mb_char = mb_substr($value, $i, 1);
                if (mb_ereg("&#(\\d+);", mb_encode_numericentity($mb_char, $convmap, "UTF-8"), $match)) { $result = sprintf("\\u%04x", $match[1]) . $result;  }
                else { $result = $mb_char . $result;  }
            }
            return '"' . $result . '"';
        }
        elseif(is_float($value)) { return str_replace(",", ".", $value); }
        elseif(is_null($value)) {  return 'null';}
        elseif(is_bool($value)) { return $value ? 'true' : 'false';   }
        elseif(is_array($value)){
            $with_keys = false;
            $n = count($value);
            for ($i = 0, reset($value); $i < $n; $i++, next($value))  { if (key($value) !== $i) {  $with_keys = true; break;  }  }
        }
        elseif (is_object($value)) { $with_keys = true; }
        else { return ''; }
        $result = array();
        if ($with_keys)  {  foreach ($value as $key => $v) {  $result[] = json_encode((string)$key) . ':' . json_encode($v); }  return '{' . implode(',', $result) . '}'; }
        else {  foreach ($value as $key => $v) { $result[] = json_encode($v); } return '[' . implode(',', $result) . ']';  }
    }
}

if(!function_exists('getJson')) {
    function getJson($message, $res = 'N', $error = '', $ext = false){
        $result = array(
            'result' => $res === 'Y' ? 'Y' : 'N',
            'message' => $GLOBALS['APPLICATION']->ConvertCharset($message, SITE_CHARSET, 'utf-8'),
        );

        if(\Bitrix\Main\Config\Option::get('aspro.next', 'ONE_CLICK_BUY_CAPTCHA', 'N') == 'Y'){
            if(!is_array($ext)){
                $ext = array();
            }

            include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/classes/general/captcha.php');
            $cpt = new CCaptcha();
            $code = htmlspecialcharsbx($GLOBALS['APPLICATION']->CaptchaGetCode());

            $ext['captcha_html'] = '<div class="form-control captcha-row clearfix"><label><span>'.$GLOBALS['APPLICATION']->ConvertCharset(GetMessage('CAPTCHA_LABEL'), SITE_CHARSET, 'utf-8').'<span class="star">*</span></span></label><div class="captcha_image"><img src="/bitrix/tools/captcha.php?captcha_sid='.$code.'" border="0" data-src="" /><input type="hidden" name="captcha_sid" value="'.$code.'"><div class="captcha_reload"></div></div><div class="captcha_input"><input type="text" class="inputtext captcha" name="captcha_word" size="30" maxlength="50" value="" required="" aria-required="true"></div></div>';
        }

        if($ext){
            $result['ext'] = $ext;
        }

        if($error){
            $result['err'] = $GLOBALS['APPLICATION']->ConvertCharset(is_array($error) ? implode('<br />', $error) : $error, SITE_CHARSET, 'utf-8');
        }

        return json_encode($result);
    }
}

if(!function_exists('genUserEmail')){
    function genUserEmail($login){
        if(strlen(SITE_SERVER_NAME)){
            $server_name = SITE_SERVER_NAME;
        }
        else{
            $server_name = $_SERVER['SERVER_NAME'];
        }

        $server_name = Cutil::translit($server_name, 'ru', ['replace_other' => '-']);

        if($dotPos = strrpos($server_name, '-')){
            $server_name = substr($server_name, 0, $dotPos).str_replace('-', '.', substr($server_name, $dotPos));
        }
        else{
            $server_name .= '.ru';
        }

        $email = $login.'@'.$server_name;

        if(!check_email($email, true)){
            $email = $login.'@'.str_replace('_', '-', $server_name);
        }

        return $email;
    }
}

if(!function_exists('getPropertyByCode')){
    function getPropertyByCode($propertyCollection, $code){
    	foreach ($propertyCollection as $property){
    		if($property->getField('CODE') == $code){
    			return $property;
    		}
    	}
    }
}

if(!function_exists('checkNewVersionExt')){
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
}

if(!function_exists('AddProducts2Basket')){
    function AddProducts2Basket($arBasketItemsAll) {
        if($arBasketItemsAll){
            foreach($arBasketItemsAll as $arItem){
                // get props
                $arProps = array();
                if($arItem['BASKET_PROPS']){
                    foreach($arItem['BASKET_PROPS'] as $keyProp => $arBasketProp)
                    {
                        if(isset($arBasketProp['BASKET_ID']))
                            unset($arItem['BASKET_PROPS'][$keyProp]['BASKET_ID']);
                    }
                    $arProps=$arItem['BASKET_PROPS'];
                }
                Add2BasketByProductID($arItem['PRODUCT_ID'], $arItem['QUANTITY'], array(), $arProps);
            }
        }
    }
}

if(!function_exists('getFiledPropsFasView')){
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
}

if(!function_exists('initAffiliate')){
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
}

if(!function_exists('placeOrder')){
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
}
