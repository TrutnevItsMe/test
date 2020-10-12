<?namespace Intervolga\Custom\Import;

use CDataXML;
use Error;

class Sets {
	const TAG_ID = "Ид";
	const TAG_NAME = "Наименование";
	const TAG_PARENT_ID = "ИдОсновнойНоменклатуры";
	const TAG_MAIN_CHOICE = "ОсновнойВариант";
	const TAG_AMOUNT = "Количество";
	const TAG_COMPOSITION = "Состав";
	const TAG_OPTIONAL = "Опция";
	const TAG_DEFAULT = "ОпцияУмолч";

	/**
	 * https://youtrack.ivsupport.ru/issue/iberisweb-8
	 * Загружает комплекты из xml файла импорта 1С
	 * @param $xmlFileName string абсолютный путь к xml файлу обмена
	 */
	public static function import($xmlFileName) {
		$xml = new CDataXML;
		$xml->Load($xmlFileName);
		$sets = $xml->SelectNodes("КоммерческаяИнформация/КоммерческаяИнформация/Каталог/Комплекты");
		$arrSets = [];
		foreach ($sets->children as $set) {
			$arrSets[] = [
				'id' => self::getValue($set, self::TAG_ID),
				'name' => self::getValue($set, self::TAG_NAME),
				'parentId' => self::getValue($set, self::TAG_PARENT_ID),
				'mainChoice' => self::getValueBoolean($set, self::TAG_MAIN_CHOICE),
				'amount' => self::getValueInteger($set, self::TAG_AMOUNT),
				'composition' => self::getComposition($set),
			];
		}
		self::processSets($arrSets);
	}
	
	/**
	 * Обработать комплекты
	 * @param $sets array данные о комплектах
	 */
	protected static function processSets($sets) {
		var_dump($sets);
	}
	
	/**
	 * Получает состав комплекта
	 * @param $node object узел xml, содержащий комплект
	 * @return array данные о составе комплекта
	 */
	protected static function getComposition($node) {
		$items = reset($node->elementsByName(self::TAG_COMPOSITION));
		$arrItems = [];
		foreach ($items->children as $item) {
			$arrItems[] = [
				"id" => self::getValue($item, self::TAG_ID),
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
	protected static function getValue($node, $name) {
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
	protected static function getValueInteger($node, $name) {
		return intval(self::getValue($node, $name));
	}
	
	/**
	 * Получает логическое значение парамета
	 * @param $node object узел xml
	 * @param $name string название параметра
	 * @return bool true если параметр "истина", иначе false
	 */
	protected static function getValueBoolean($node, $name) {
		return self::getValue($node, $name) === "истина";
	}
}