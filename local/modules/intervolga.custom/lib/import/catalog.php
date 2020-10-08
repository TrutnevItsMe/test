<?namespace Intervolga\Custom\Import;

use CDataXML;

class Catalog {
	/**
	 * https://youtrack.ivsupport.ru/issue/iberisweb-8
	 * Загружает комплекты из xml файла импорта 1С
	 * @param $xmlFileName string абсолютный путь к xml файлу обмена
	 */
	public static function importSets($xmlFileName) {
		$xml = new CDataXML;
		$xml->Load($xmlFileName);
	}
}