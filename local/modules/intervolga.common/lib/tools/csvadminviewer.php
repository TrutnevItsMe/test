<?php namespace Intervolga\Common\Tools;

use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Web\Uri;
use CBXVirtualIo;
use CCSVData;
use CDBResult;
use CFileMan;
use CSite;
use CUtil;

Loc::loadMessages(__FILE__);

/**
 * Class CsvAdminViewer - класс предназначен для формирования административной таблицы
 * по содержимому csv файла.
 *
 * @package Intervolga\Common\Tools
 */
class CsvAdminViewer
{
	/**
	 * Разделитель CSV файла по умолчанию.
	 */
	const DEFAULT_CSV_DELIMITER = ',';

	/**
	 * Префикс для полей id и sort колонки административной таблицы.
	 */
	const COLUMN_ID_PREFIX = 'COLUMN_';

	/**
	 * Префикс id строки административной таблицы.
	 */
	const ROW_ID_PREFIX = 'ROW_';

	/**
	 * Префикс поля фильтра.
	 */
	const FILTER_PREFIX = 'find_';

	/**
	 * Постфикс для названия таблицы в фильтре.
	 */
	const FILTER_TABLE_POSTFIX = '_filter';

	/**
	 * Текст первой колонки в заголовке административной таблицы.
	 */
	const HEADER_FIRST_COLUMN_NAME = '№';

	/**
	 * ID административной таблицы.
	 */
	const TABLE_ID = 'intervolga_csv';

	/**
	 * Название страницы стандартного просмотрщика файлов bitrix.
	 */
	const FILEMAN_FILE_VIEW_PAGE_NAME = 'fileman_file_view.php';

	/**
	 * Название стандартной страницы загрузки файлов bitrix.
	 */
	const FILEMAN_FILE_DOWNLOAD_PAGE_NAME = 'fileman_file_download.php';

	/**
	 * Название страницы просмотрщика csv файлов.
	 */
	const SHOW_CSV_PAGE_NAME = 'intervolga.common_showscv.php';

	/**
	 * @var CAdminList
	 */
	private $adminList;

	/**
	 * @var CAdminResult
	 */
	private $adminResult;

	/**
	 * @var CAdminFilter
	 */
	private $adminFilter;

	/**
	 * @var array массив полей фильтра.
	 */
	private $adminFilterData;

	/**
	 * @var CsvDataHelper объект данных csv файла.
	 */
	private $csvData;

	/**
	 * @var CDBResult данные csv файла в виде объекта
	 */
	private $csvDataObject;

	/**
	 * @var boolean флаг - является ли первая строка файла заголовком
	 */
	private $isFirstStringHeader;

	/**
	 * @var array данные о csv файле.
	 */
	private $fileInfo = array();

	/**
	 * @var array массив фильтра.
	 */
	private $filter = array();

	/**
	 * @param string $filePath путь к файлу от корня сайта.
	 * @param boolean $isFirstStringHeader флаг - является ли первая строка заголовком.
	 * @throws \Bitrix\Main\IO\IoException в случае, если тип файла - не csv.
	 */
	public function __construct($filePath, $isFirstStringHeader)
	{
		$this->isFirstStringHeader = $isFirstStringHeader;
		$this->setFileInfo($filePath);

		if ($this->fileInfo['FILE_TYPE'] != 'csv')
		{
			throw new \Bitrix\Main\IO\IoException('Incorrect file type');
		}

		$this->adminList = new \CAdminList(static::TABLE_ID);
	}

	/**
	 * Подготавливает административную таблицу.
	 *
	 * Читает csv файл и инициализирует административную таблицу.
	 * Должен быть вызван до метода show().
	 *
	 * @throws \Bitrix\Main\IO\IoException в случае, если данные не были получены из csv файла.
	 */
	public function init()
	{
		$this->csvData = new CsvDataHelper(
			$this->fileInfo['FILE_PATH'],
			static::DEFAULT_CSV_DELIMITER,
			$this->isFirstStringHeader
		);
		$this->csvData->readCsvFile();
		$csvDataArray = $this->csvData->getCsvData();

		if (!empty($csvDataArray))
		{
			$this->initAdminResult();
			$this->initNav();
			$this->initFilter();

			$this->addTableHeader();
			$this->addTableData();
			$this->addAdminMenu();
			$this->addAlternativeOutputMethods();
		}
		else
		{
			throw new \Bitrix\Main\IO\IoException('Error getting csv data');
		}
	}

	/**
	 * @return array служебные данные о csv файле.
	 */
	public function getFileInfo()
	{
		return $this->fileInfo;
	}

	/**
	 * @return string название административной страницы просмотрщика csv файлов.
	 */
	public static function getAdminPageName()
	{
		return static::SHOW_CSV_PAGE_NAME;
	}

	/**
	 * Показывает административную таблицу.
	 */
	public function show()
	{
		if ($this->adminList)
		{
			$this->showFilter();
			$this->adminList->DisplayList();
		}
	}

	/**
	 * Выводит фильтр таблицы.
	 */
	public function showFilter()
	{
		global $APPLICATION;
		$isFirstStringHeader = ($this->isFirstStringHeader) ? 'Y' : 'N';

		// Подготовка названий полей фильтра
		$filterFieldNames = $this->csvData->getCsvHeader();
		if (!$this->isFirstStringHeader)
		{
			$counter = 1;
			$filterFieldsCount = count($filterFieldNames);
			$filterFieldNames = array();
			for ($i = 0; $i < $filterFieldsCount; $i++)
			{
				$filterFieldNames[] = Loc::getMessage('INTERVOLGA_COMMON.CSV_ADMIN_VIEWER.DEFAULT_COLUMN_NAME')
					. ' '
					. $counter++;
			}
		}

		// Инициализация фильтра
		$this->adminFilter = new \CAdminFilter(
			static::TABLE_ID . static::FILTER_TABLE_POSTFIX,
			$filterFieldNames
		);

		// Вывод фильтра
		echo '<form name="find_form" method="get" action="' . $APPLICATION->GetCurPage() . '">';
		$this->adminFilter->Begin();
		$this->showFilterDescription();
		echo '<input type="hidden" name="path" value="' . $this->fileInfo['FILE_PATH'] . '">';
		echo '<input type="hidden" name="is_first_string_header" value="' . $isFirstStringHeader . '">';
		for($i = 0; $i < count($filterFieldNames); $i++)
		{
			$fieldName = $filterFieldNames[$i];
			$fieldId = static::FILTER_PREFIX . $this->csvData->getCsvHeader()[$i];
			$fieldValue = htmlspecialchars($GLOBALS[static::FILTER_PREFIX . $fieldId]);

			echo '<tr>';
			echo '<td>';
			echo $fieldName;
			echo '</td>';
			echo '<td>';
			echo '<input type="text" name="' . $fieldId . '" value="' . $fieldValue . '">';
			echo '</td>';
			echo '</tr>';
		}

		$this->adminFilter->Buttons(array(
			"table_id" => static::TABLE_ID,
			"url" => $APPLICATION->GetCurPage(),
			"form" => 'find_form',
		));
		$this->adminFilter->End();
		echo '</form>';
	}

	/**
	 * Выводит описание фильтра.
	 */
	public function showFilterDescription()
	{
		echo BeginNote();
		echo Loc::getMessage('INTERVOLGA_COMMON.CSV_ADMIN_VIEWER.FILTER_HELP');
		echo EndNote();
	}

	/**
	 * Инициализирует объект CAdminResult.
	 */
	protected function initAdminResult()
	{
		// Получить данные всего csv файла.
		$csvDataArray = $this->csvData->getCsvData();

		// Подготовить фильтр и, если он задан, получить данные, соответствующие фильтру
		$this->prepareDataFilter();
		if (!empty($this->filter))
		{
			$csvDataArray = $this->csvData->getFilteredCsvData($this->filter);
		}

		$this->csvDataObject = new CDBResult;
		$this->csvDataObject->InitFromArray($csvDataArray);

		$this->adminResult = new \CAdminResult($this->csvDataObject, static::TABLE_ID);
	}

	/**
	 * Инициализирует навигацию таблицы.
	 */
	protected function initNav()
	{
		if ($this->adminResult && $this->adminList)
		{
			$this->adminResult->NavStart();
			$navPrint = $this->adminResult->GetNavPrint(
				Loc::getMessage('INTERVOLGA_COMMON.CSV_ADMIN_VIEWER.NAV_TEXT')
			);
			$this->adminList->NavText($navPrint);
		}
	}

	/**
	 * Добавляет заголовок административной таблицы.
	 */
	protected function addTableHeader()
	{
		$counter = 0;
		$tableHeaders = array();

		$tableHeaders[] = array(
			'id' => static::COLUMN_ID_PREFIX . $counter,
			'content' => static::HEADER_FIRST_COLUMN_NAME,
			'sort' => static::COLUMN_ID_PREFIX . $counter,
			'default' => true,
		);
		$counter++;


		foreach ($this->csvData->getCsvHeader() as $headerColumn)
		{
			if (!$this->isFirstStringHeader)
			{
				$headerColumn = Loc::getMessage('INTERVOLGA_COMMON.CSV_ADMIN_VIEWER.DEFAULT_COLUMN_NAME')
					. ' '
					. $counter;
			}

			$tableHeaders[] = array(
				'id' => static::COLUMN_ID_PREFIX . $counter,
				'content' => $headerColumn,
				'sort' => static::COLUMN_ID_PREFIX . $counter,
				'default' => true,
			);
			$counter++;
		}
		$this->adminList->AddHeaders($tableHeaders);
	}

	/**
	 * Добавляет данные административной таблицы.
	 */
	protected function addTableData()
	{
		$rowCounter = 0;
		while ($dataRow = $this->adminResult->NavNext())
		{
			$columnCounter = 0;
			$row =& $this->adminList->AddRow(static::ROW_ID_PREFIX . $rowCounter++, $dataRow);

			// Колонка с номером записи
			$columnKey = static::COLUMN_ID_PREFIX . $columnCounter++;
			$pageNumber = $this->adminResult->NavPageNomer;
			$pageSize = $this->adminResult->NavPageSize;
			$rowNumber = ($pageNumber*$pageSize) - $pageSize + $rowCounter;
			$row->AddViewField($columnKey, $rowNumber);

			// Колонки с данными
			foreach ($dataRow as $key => $dataColumn)
			{
				$columnKey = static::COLUMN_ID_PREFIX . $columnCounter++;
				$row->AddViewField($columnKey, $dataColumn);
			}
		}
	}

	/**
	 * Добавляет меню административной таблицы.
	 */
	protected function addAdminMenu()
	{
		global $USER;

		$aContext = array(
			array(
				'TEXT' => Loc::getMessage('INTERVOLGA_COMMON.CSV_ADMIN_VIEWER.SHOW_AS_TEXT_BTN_TEXT'),
				'LINK' => static::FILEMAN_FILE_VIEW_PAGE_NAME . '?path=' . urlencode($this->fileInfo['FILE_PATH']),
				'TITLE' => Loc::getMessage('INTERVOLGA_COMMON.CSV_ADMIN_VIEWER.SHOW_AS_TEXT_BTN_TITLE'),
			),
		);

		if ($USER->CanDoFileOperation('fm_download_file', $this->fileInfo['AR_PATH']))
		{
			$downloadFilePath = static::FILEMAN_FILE_DOWNLOAD_PAGE_NAME
				. '?path='
				. urlencode($this->fileInfo['FILE_PATH']);

			$aContext[] = array(
				'TEXT' => Loc::getMessage('INTERVOLGA_COMMON.CSV_ADMIN_VIEWER.DOWNLOAD_FILE_BTN_TEXT'),
				'LINK' => $downloadFilePath,
				'TITLE' => Loc::getMessage('INTERVOLGA_COMMON.CSV_ADMIN_VIEWER.DOWNLOAD_FILE_BTN_TITLE'),
			);
		}

		$this->addFirstStringHeaderToggleButton($aContext);

		$this->adminList->AddAdminContextMenu($aContext);
	}

	/**
	 * Добавляет альтернативные методы вывода административной таблицы.
	 */
	protected function addAlternativeOutputMethods()
	{
		$this->adminList->CheckListMode();
	}

	/**
	 * Устанавливает служебные данные о csv файле.
	 *
	 * @param string $filePath путь к файлу относительно корня сайта.
	 */
	protected function setFileInfo($filePath)
	{
		$io = CBXVirtualIo::GetInstance();
		$this->fileInfo['DOC_ROOT'] = CSite::GetSiteDocRoot(false);

		if (CUtil::DetectUTF8($filePath))
		{
			CUtil::decodeURIComponent($filePath);
		}

		$this->fileInfo['FILE_PATH'] = $io->CombinePath("/", $filePath);
		$this->fileInfo['PARSED_PATH'] = CFileMan::ParsePath(
			array(
				false,
				$this->fileInfo['FILE_PATH'],
			),
			false,
			false,
			""
		);
		$this->fileInfo['ABS_PATH'] = $this->fileInfo['DOC_ROOT'] . $this->fileInfo['FILE_PATH'];
		$this->fileInfo['AR_PATH'] = array(false, $this->fileInfo['FILE_PATH']);
		$this->fileInfo['FILE_TYPE'] = CFileMan::GetFileExtension($this->fileInfo['FILE_PATH']);
	}

	/**
	 * Инициализирует фильтр данными из заголовка таблицы,
	 * подготовленными методом prepareHeaderData.
	 */
	protected function initFilter()
	{
		$this->adminFilterData = array();
		foreach ($this->csvData->getCsvHeader() as $headerColumn)
		{
			$this->adminFilterData[] = static::FILTER_PREFIX . $headerColumn;
		}
		$this->adminList->InitFilter($this->adminFilterData);
	}

	/**
	 * Формирует массиив фильтра из запроса.
	 */
	protected function prepareDataFilter()
	{
		if (!$GLOBALS['del_filter'] == 'Y')
		{
			$csvHeader = $this->csvData->getCsvHeader();
			foreach ($csvHeader as $csvHeaderColumn)
			{
				$globalsKey = static::FILTER_PREFIX . $csvHeaderColumn;
				if ($GLOBALS[$globalsKey])
				{
					$this->filter[$csvHeaderColumn] = $GLOBALS[$globalsKey];
				}
			}
		}
	}

	/**
	 * Добавлеяет кнопку-переключатель опции "Первая строка-заголовок".
	 *
	 * @param array $admMenu массив элементов административного меню.
	 */
	protected function addFirstStringHeaderToggleButton(array &$admMenu)
	{
		$request = Context::getCurrent()->getRequest();
		$uriString = $request->getRequestUri();
		$uri = new Uri($uriString);
		$paramsToDeleteFromUri = array_merge(
			$this->adminFilterData,
			array(
				'set_filter',
				'adm_filter_applied',
				'mode',
				'table_id',
			));
		$uri->deleteParams($paramsToDeleteFromUri);

		$paramValue = $request->get('is_first_string_header');
		$btnText = Loc::getMessage('INTERVOLGA_COMMON.CSV_ADMIN_VIEWER.FIRST_STRING_NOT_HEADER_BTN_TEXT');
		$btnTitle = Loc::getMessage('INTERVOLGA_COMMON.CSV_ADMIN_VIEWER.FIRST_STRING_NOT_HEADER_BTN_TITLE');
		if (empty($paramValue) || $paramValue == 'Y')
		{
			$paramValue = 'N';
		}
		else
		{
			$paramValue = 'Y';
			$btnText = Loc::getMessage('INTERVOLGA_COMMON.CSV_ADMIN_VIEWER.FIRST_STRING_HEADER_BTN_TEXT');
			$btnTitle = Loc::getMessage('INTERVOLGA_COMMON.CSV_ADMIN_VIEWER.FIRST_STRING_HEADER_BTN_TITLE');
		}
		$uri->addParams(array(
			'is_first_string_header' => $paramValue,
			'del_filter' => 'Y',
		));

		$admMenu[] = array(
			'TEXT' => $btnText,
			'LINK' => $uri->getUri(),
			'TITLE' => $btnTitle,
		);
	}
}

/**
 * Class CsvDataHelper - вспомогательный класс чтения csv файла.
 * @package Intervolga\Common\Tools
 */
class CsvDataHelper
{
	/**
	 * Значение по-умолчанию для параметра "Тип разделителя" = "Символ".
	 */
	const DEFAULT_FIELDS_TYPE = 'R';

	/**
	 * Значение по-умолчанию для параметра "Первая строка содержит имен полей".
	 */
	const DEFAULT_IS_FIRST_STRING_HEADER = false;

	/**
	 * @var string Путь к csv файлу от корня сайта.
	 */
	private $filePath;

	/**
	 * @var string Разделитель csv файла.
	 */
	private $delimiter;

	/**
	 * @var bool Флаг - является ли первая строка заголовком.
	 */
	private $isFirstStringHeader;

	/**
	 * @var array Данные csv файла.
	 */
	private $csvData = array();

	/**
	 * @var array Заголовок csv файла: первая строка, если установлен флаг  $isFirstStringHeader
	 *                                 или
	 *                                 строка вида COLUMN_<N>, где N - номер колонки в строке csv файла (начиная с 0).
	 */
	private $csvHeader = array();

	/**
	 * @param string $filePath путь к csv файлу от корня сайта.
	 * @param string $delimiter разделитель csv файла.
	 * @param bool $isFirstStringHeader является ли первая строка заголовком.
	 */
	public function __construct($filePath, $delimiter = ',', $isFirstStringHeader = true)
	{
		$this->filePath = $filePath;
		$this->delimiter = $delimiter;
		$this->isFirstStringHeader = $isFirstStringHeader;
	}

	/**
	 * Прочитать csv файл и запомнить данные.
	 *
	 * Данные: заголовок и основные данные.
	 * Заголовок: первая строка, если установлен флаг  $isFirstStringHeader
	 *            или
	 *            строка вида COLUMN_<N>, где N - номер колонки в строке csv файла (начиная с 0).
	 */
	public function readCsvFile()
	{
		$filePath = $_SERVER['DOCUMENT_ROOT'] . $this->filePath;
		$csvFile = new CCSVData(static::DEFAULT_FIELDS_TYPE, static::DEFAULT_IS_FIRST_STRING_HEADER);
		$csvFile->SetDelimiter($this->delimiter);

		$csvFile->LoadFile($filePath);

		// Формирование массива ключей csv данных
		// Если первая строка файла - загловок, то ключи данных берутся из первой строки
		// Иначе, ключи данных формируются как COLUMN_<N>,
		// где N - номер колонки в строке csv файла (начиная с 0)
		if ($arRes = $csvFile->fetch())
		{
			if ($this->isFirstStringHeader)
			{
				$this->csvHeader = $arRes;
			}
			else
			{
				for ($i = 0; $i < count($arRes); ++$i)
				{
					$this->csvHeader[] = 'COLUMN_' . $i;
				}
				$this->csvData[] = $this->getCsvDataRow($arRes);
			}
		}

		// Формирование массива csv данных
		while ($arRes = $csvFile->Fetch())
		{
			$this->csvData[] = $this->getCsvDataRow($arRes);;
		}
	}

	/**
	 * Получить данные csv файла.
	 *
	 * @return array данные csv файла.
	 */
	public function getCsvData()
	{
		return $this->csvData;
	}

	/**
	 * Получить данные csv файла, соответствующие фильтру.
	 *
	 * @param array $filter фильтр.
	 * @return array данные csv файла, соответствующие фильтру.
	 */
	public function getFilteredCsvData(array $filter)
	{
		$filteredCsvData = array();

		if (!empty($filter))
		{
			foreach ($this->csvData as $csvDataRow)
			{
				if ($this->isRowPassFilter($csvDataRow, $filter))
				{
					$filteredCsvData[] = $csvDataRow;
				}
			}
		}

		return $filteredCsvData;
	}

	/**
	 * Получить заголовок csv данных (первая строка).
	 *
	 * @return array
	 */
	public function getCsvHeader()
	{
		return $this->csvHeader;
	}

	/**
	 * Проверить, соответствует ли строка csv файла фильтру.
	 * Используется сравнение LIKE.
	 *
	 * @param array $csvRow строка csv файла в виде массива.
	 * @param array $filter фильтр.
	 * @return bool соответствует ли строка csv файла фильтру.
	 */
	protected function isRowPassFilter(array $csvRow, array $filter)
	{
		foreach ($filter as $filterKey => $filterValue)
		{
			if (!$this->isValuePassFilter($csvRow[$filterKey], $filterValue))
			{
				return false;
			}
		}
		return true;
	}

	/**
	 * Проверить, обернута ли строка $string символами $wrapSymbol.
	 *
	 * @param string $string строка для проверка.
	 * @param string $wrapSymbol символ-обертка.
	 * @return bool флаг - обернута ли строка.
	 */
	protected function isStringWrapped($string, $wrapSymbol)
	{
		$firstChar = substr($string, 0, 1);
		$lastChar = substr($string, -1);

		return ($firstChar == $wrapSymbol && $lastChar == $wrapSymbol);
	}

	/**
	 * Проверить, соответствует ли значения из csv файла ($csvValue) фильтру ($filterValue).
	 * Если строка фильтра ($filterValue) обернута в двойные кавычки, используется строгое сравнение.
	 * Иначе - сравнение по типу LIKE.
	 *
	 * @param string $csvValue значение из csv файла.
	 * @param string $filterValue значение фильтра.
	 * @return bool соответствует ли значение csv файла фильтру.
	 */
	protected function isValuePassFilter($csvValue, $filterValue)
	{
		if ($this->isStringWrapped($filterValue, '"'))
		{
			$tmpFilterValue = trim($filterValue, '"');
			return $this->checkStrictEquality($csvValue, $tmpFilterValue);
		}
		else
		{
			return $this->checkLikeEquality($csvValue, $filterValue);
		}
	}

	/**
	 * Проверка строк на строгое равенство (используется оператор ===).
	 *
	 * @param string $firstString первая строка.
	 * @param string $secondString вторая строка.
	 * @return bool равны ли строки.
	 */
	protected function checkStrictEquality($firstString, $secondString)
	{
		return $firstString === $secondString;
	}

	/**
	 * Проверка строк на равенство по типу LIKE (используется функция strpos).
	 *
	 * @param string $haystackString строка для поиска.
	 * @param string $needleString искомая строка.
	 * @return bool найдена ли строка $needleString в строке $haystackString.
	 */
	protected function checkLikeEquality($haystackString, $needleString)
	{
		return (strpos($haystackString, $needleString) === false) ? false : true;
	}

	/**
	 * Получить подготовленную строку данных csv файла в виде массива.
	 *
	 * @param array $fileCsvDataRow массив сырых данных строки csv файла.
	 * @return array подготовленная строка данных csv файла.
	 */
	protected function getCsvDataRow(array $fileCsvDataRow)
	{
		$csvDataRow = array();

		foreach ($fileCsvDataRow as $key => $csvDataColumn)
		{
			$columnKey = $this->csvHeader[$key];
			$csvDataRow[$columnKey] = $csvDataColumn;
		}

		return $csvDataRow;
	}
}