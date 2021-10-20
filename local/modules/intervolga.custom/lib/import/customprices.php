<? namespace Intervolga\Custom\Import;
use CDataXML;
use CSaleBasket;
use Bitrix\Main\Web\HttpClient;

class CustomPrices {
	const URL = "https://CalcDiscountBitrix:bLK8mGtXQI0C@api.iberis-pro.ru/iberis-ut11-TestForBitrix/hs/AutoDiscountCalc/GetAutoDiscount";
	public static function get($clientId, $counterpartyId, $agreementId, $products) {
		$xml = self::getRequestXml($clientId, $counterpartyId, $agreementId, $products);
		$httpClient = new HttpClient();
		$httpClient->setHeader('Content-Type', 'application/xml; charset=UTF-8', true);
		$httpClient->query("PUT", self::URL, $xml);
		$xml = new CDataXML();
		$xml->LoadString($httpClient->getResult());
		$items = $xml->SelectNodes("/CalcDiscountResponse")->elementsByName('Products');
		$products = [];
		foreach ($items as $item) {
			$products[] = [
				'xml_id' => self::getValue($item, "ProductID"),
				'quantity' => self::getValueInteger($item, "Count"),
				'price' => self::getValueFloat($item, "Price"),
				'discount' => self::getValueFloat($item, "Discount"),
			];
		}
		return $products;
	}
	public static function set($prices, $basket) {
		foreach ($prices as $price) {
			if ($price['price'] > 0 && $price['quantity'] > 0) {
				$basketRow = $basket[$price['xml_id']];
				$customPrice = $price['price'] - ($price['discount'] / $price['quantity']);
				CSaleBasket::Update(
					$basketRow['dbId'],
					['PRICE' => $customPrice, 'QUANTITY' => $price['quantity'], 'CUSTOM_PRICE' => 'Y']
				);
			}
		}
	}
	/**
	 * Получает значение текстовое параметра
	 * @param $node object узел xml
	 * @param $name string название параметра
	 * @return string текстовое значение параметра, если не найден -- пустая строка
	 */
	protected static function getValue($node, $name)
	{
		try {
			return trim(reset($node->elementsByName($name))->textContent());
		} catch (Error $e) {
			return "";
		}
	}
	
	/**
	 * Получает целочисленное значение парамета
	 * @param $node object узел xml
	 * @param $name string название параметра
	 * @return int целочисленное значение параметра, 0 - если не найден
	 */
	protected static function getValueInteger($node, $name)
	{
		return intval(self::getValue($node, $name));
	}
	
	/**
	 * Получает значение парамета c плавающей точкой
	 * @param $node object узел xml
	 * @param $name string название параметра
	 * @return float значение параметра
	 */
	protected static function getValueFloat($node, $name)
	{
		return floatval(self::getValue($node, $name));
	}
	protected static function getRequestXml($clientId, $counterpartyId, $agreementId, $products) {
		$xml = '<OrderDataRequest xmlns="http://CalculateDiscountBitrix" xmlns:xs="http://www.w3.org/2001/XMLSchema"'
			. ' xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
		$xml .= "<ClientID>$clientId</ClientID>";
		$xml .= "<CounterpartyID>$counterpartyId</CounterpartyID>";
		$xml .= "<AgreementID>$agreementId</AgreementID>";
		foreach ($products as $product) {
			$xml .= "<Products>";
			$xml .= "<ProductID>${product['xmlId']}</ProductID>";
			$xml .= "<Count>${product['quantity']}</Count>";
			$xml .= "</Products>";
		}
		$xml .= "</OrderDataRequest>";
		return $xml;
	}
}