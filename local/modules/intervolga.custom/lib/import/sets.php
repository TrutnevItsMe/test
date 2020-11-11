<? namespace Intervolga\Custom\Import;

use CDataXML;
use Error;
use Bitrix\Main\Loader;
use Bitrix\Main\Data\Cache;
use CIBlockElement;
use CFile;
use CCatalogProduct;
use Bitrix\Main\Web\Json;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Application;
use Bitrix\Iblock\ElementTable;
use Bitrix\Catalog\Model\Price;
use Bitrix\Catalog\GroupTable;
use CPrice;
use CCatalogProductSet;

class Sets
{
	const TAG_ID = "Ид";
	const TAG_NAME = "Наименование";
	const TAG_PARENT_ID = "ИдОсновнойНоменклатуры";
	const TAG_MAIN_CHOICE = "ОсновнойВариант";
	const TAG_AMOUNT = "Количество";
	const TAG_COMPOSITION = "Состав";
	const TAG_OPTIONAL = "Опция";
	const TAG_DEFAULT = "ОпцияУмолч";
	const TAG_DELETE = "ПометкаУдаления";
	
	/**
	 * https://youtrack.ivsupport.ru/issue/iberisweb-8
	 * Загружает комплекты из xml файла импорта 1С
	 * @param $xmlFileName string абсолютный путь к xml файлу обмена
	 */
	public static function import($xmlFileName)
	{
		$xml = new CDataXML;
		$xml->Load($xmlFileName);
		$sets = $xml->SelectNodes("КоммерческаяИнформация/КоммерческаяИнформация/Каталог/Комплекты");
		$arrSets = [];
		foreach ($sets->children as $set) {
			$parentId = self::getValue($set, self::TAG_PARENT_ID);
			$arrSets[$parentId] = [
				'id' => self::getValue($set, self::TAG_ID),
				'name' => self::getValue($set, self::TAG_NAME),
				'parentId' => $parentId,
				'mainChoice' => self::getValueBoolean($set, self::TAG_MAIN_CHOICE),
				'amount' => self::getValueInteger($set, self::TAG_AMOUNT),
				'delete' => self::getValueBoolean($set, self::TAG_DELETE),
				'composition' => self::getComposition($set),
			];
		}
		// Intervolga Akentyev Logs
		file_put_contents(
			$_SERVER['DOCUMENT_ROOT'] . '/upload/logs/1c_catalog' . DATE('_Y_m_d') . '.log',
			print_r($arrSets, true) . PHP_EOL,
			FILE_APPEND
		);
		self::processSets($arrSets);
	}
	
	/**
	 * Возвращает данные комплекта
	 * @param $json string сериализированый состав комплекта
	 * @return array данные товаров, составляющих комплект
	 */
	public static function getSet($composition)
	{
		$cache = Cache::createInstance();
		if ($cache->initCache(3600, $composition)) {
			$set = $cache->getVars();
		} else {
			$set = [];
			try {
				$composition = Json::decode($composition);
				if (count($composition) > 0) {
					$rsItems = CIBlockElement::GetList(
						['SORT' => 'ASC'],
						['=XML_ID' => array_keys($composition)],
						false,
						false,
						[
							'ID',
							'IBLOCK_ID',
							'XML_ID',
							'NAME',
							'PREVIEW_PICTURE',
							'DETAIL_PAGE_URL',
							'PROPERTY_CML2_ARTICLE',
							'SORT',
						]
					);
					$first = true;
					while ($rsItem = $rsItems->GetNext()) {
						if ($first) {
							$taggedCache = Application::getInstance()->getTaggedCache();
							$taggedCache->registerTag('iblock_id_' . $rsItem['IBLOCK_ID']);
							$first = false;
						}
						$item = [
							'ID' => $rsItem['ID'],
							'XML_ID' => $rsItem['XML_ID'],
							'NAME' => $rsItem['NAME'],
							'SORT' => $rsItem['SORT'],
							'DETAIL_PAGE_URL' => $rsItem['DETAIL_PAGE_URL'],
							'ARTICLE' => trim($rsItem['PROPERTY_CML2_ARTICLE_VALUE']),
						];
						$compItem = $composition[$item['XML_ID']];
						$category = $compItem['optional'] ? 'OPTIONAL' : 'SET';
						$item['AMOUNT'] = $compItem['amount'];
						if ($compItem['optional']) {
							$item['DEFAULT'] = $compItem['default'];
						}
						if ($rsItem['PREVIEW_PICTURE']) {
							$item['PREVIEW_PICTURE'] = CFile::GetPath($rsItem['PREVIEW_PICTURE']);
						}
						$price = CCatalogProduct::GetOptimalPrice(
							$item['ID'],
							1,
							[],
							'N',
							[CPrice::GetBasePrice($item['ID'])],
							's1'
						);
						$price = $price['RESULT_PRICE'];
						$item['PRICE'] = floatval($price['DISCOUNT_PRICE']);
						if ($price['DISCOUNT'] > 0) {
							$item['OLD_PRICE'] = floatval($price['BASE_PRICE']);
							$item['PERCENT'] = intval($price['PERCENT']);
						}
						if (!isset($set[$category])) {
							$set[$category] = [];
						}
						$set[$category][] = $item;
					}
				}
				$cache->endDataCache($set);
			} catch (ArgumentException $err) {
				$cache->abortDataCache();
			}
		}
		return $set;
	}
	
	/**
	 * Обработать комплекты
	 * @param $sets array данные о комплектах
	 */
	protected static function processSets($sets)
	{
		if (count($sets) > 0 && Loader::includeModule('iblock')) {
			$items = ElementTable::getList([
				'select' => ['ID', 'XML_ID', 'NAME'],
				'filter' => ['=XML_ID' => array_keys($sets)],
			]);
			while ($item = $items->fetch()) {
				$delete = $sets[$item['XML_ID']]['delete'];
				$composition = $sets[$item['XML_ID']]['composition'];
				if ($delete || is_array($composition)) {
					$value = null;
					if (!$delete) {
						$value = [["TEXT" => Json::encode($composition), "TYPE" => "TEXT"]];
					} else {
						self::deleteSets($item['ID'], CCatalogProductSet::TYPE_SET);
						self::deleteSets($item['ID'], CCatalogProductSet::TYPE_GROUP);
						// Intervolga Akentyev Logs
						file_put_contents(
							$_SERVER['DOCUMENT_ROOT'] . '/upload/logs/1c_catalog' . DATE('_Y_m_d') . '.log',
							"Delete:" . PHP_EOL . print_r($item, true) . PHP_EOL,
							FILE_APPEND
						);
					}
					CIBlockElement::SetPropertyValueCode(
						$item['ID'],
						"COMPOSITION",
						$value
					);
					$set = self::getSet(Json::encode($composition));
					// Зададим цену комплекта
					$price = 0;
					foreach ($set['SET'] as $setItem) {
						$price += $setItem['PRICE'];
					}
					$basePrice = CPrice::GetBasePrice($item['ID']);
					if (is_array($basePrice)) {
						Price::update(
							$basePrice['ID'],
							[
								'ID' => $basePrice['ID'],
								'PRODUCT_ID' => $basePrice['PRODUCT_ID'],
								'CATALOG_GROUP_ID' => $basePrice['CATALOG_GROUP_ID'],
								'CURRENCY' => $basePrice['CURRENCY'],
								'PRICE' => $price,
							]
						);
					} else {
						// Если у товара нет базовой цены, создадим запись
						Price::add([
							'PRODUCT_ID' => $item['ID'],
							'CATALOG_GROUP_ID' => self::getBasePriceId(),
							'CURRENCY' => 'RUB',
							'PRICE' => $price
						]);
					}
					// Заполним стандартные данные о комплекте / наборе
					self::addSet($item['ID'], CCatalogProductSet::TYPE_SET, $set['SET']);
					self::addSet($item['ID'], CCatalogProductSet::TYPE_GROUP, $set['OPTIONAL']);
				}
			}
		}
	}
	
	/**
	 * Добавляет или обновляет комплект/набор у товара
	 * @param $productId integer идентификатор товара
	 * @param $setType CCatalogProductSet::TYPE_SET|CCatalogProductSet::TYPE_GROUP комлект или набор
	 * @param $set array комплект/набор товаров
	 * @return integer идентификатор комплект/набор
	 */
	protected static function addSet($productId, $setType, $set) {
		$items = [];
		foreach ($set as $item) {
			$items[] = [
				'ITEM_ID' => $item['ID'],
				'SORT' => $item['SORT'],
				'QUANTITY' => $item['AMOUNT'],
			];
		}
		$sets = CCatalogProductSet::getAllSetsByProduct($productId, $setType);
		if (empty($sets)) {
			$setId = CCatalogProductSet::add([
				'ITEM_ID' => $productId,
				'TYPE' => $setType,
				'ITEMS' => $items,
			]);
		} else {
			$set = reset($sets);
			$setId = $set['SET_ID'];
			CCatalogProductSet::update(
				$setId,
				[
				'ITEM_ID' => $productId,
				'TYPE' => $setType,
				'ITEMS' => $items,
			]);
		}
		return $setId;
	}
	
	/**
	 * Удаляет комплект/набор у товара
	 * @param $productId integer идентификатор товара
	 * @param $setType CCatalogProductSet::TYPE_SET|CCatalogProductSet::TYPE_GROUP комлект или набор
	 */
	protected static function deleteSets($productId, $setType) {
		$sets = CCatalogProductSet::getAllSetsByProduct($productId, $setType);
		foreach ($sets as $set) {
			CCatalogProductSet::delete($set['SET_ID']);
		}
	}
	
	protected function getBasePriceId() {
		$price = GroupTable::getRow([
			'filter' => ['=BASE' => 'Y'],
			'select' => ['ID']
		]);
		return $price['ID'];
	}
	
	/**
	 * Получает состав комплекта
	 * @param $node object узел xml, содержащий комплект
	 * @return array данные о составе комплекта
	 */
	protected static function getComposition($node)
	{
		$items = reset($node->elementsByName(self::TAG_COMPOSITION));
		$arrItems = [];
		foreach ($items->children as $item) {
			$id = self::getValue($item, self::TAG_ID);
			$arrItems[$id] = [
				"id" => $id,
				"amount" => self::getValueInteger($item, self::TAG_AMOUNT),
				"optional" => self::getValueBoolean($item, self::TAG_OPTIONAL),
				"default" => self::getValueBoolean($item, self::TAG_DEFAULT),
			];
		}
		return $arrItems;
	}
	
	/**
	 * Получает значение текстовое параметра
	 * @param $node object узел xml
	 * @param $name string название параметра
	 * @return string текстовое знаечение параметра, если не найден -- пустая строка
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
	 * @return int текстовое знаечение параметра, 0 - если не найден
	 */
	protected static function getValueInteger($node, $name)
	{
		return intval(self::getValue($node, $name));
	}
	
	/**
	 * Получает логическое значение парамета
	 * @param $node object узел xml
	 * @param $name string название параметра
	 * @return bool true если параметр "истина", иначе false
	 */
	protected static function getValueBoolean($node, $name)
	{
		return self::getValue($node, $name) === "true";
	}
}