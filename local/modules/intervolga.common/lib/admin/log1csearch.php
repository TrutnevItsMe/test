<?namespace Intervolga\Common\Admin;

use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class Log1CSearch
{
	protected static $searchType = array(
		'xml',
		'log',
	);

	public static $targetPath = '/log/1c';

	/**
	 * @param $dir
	 * @param $lineSearch
	 * @return array|void
	 */
	protected static function search($dir, $lineSearch)
	{
		$result = array(
			'ERROR',
			'RESULT',
		);

		// get path
		$path = static::getPathDir($dir);

		// check field
		if ($result['ERROR'] = static::checkField($path, $lineSearch))
		{
			return $result;
		}

		// get all files
		if (!$arFilesInDir = static::detectFiles($path))
		{
			$result['ERROR'][] = Loc::getMessage('INTERVOLGA_COMMON.ERROR_NO_FILES');
			return $result;
		}

		// find line
		$result['RESULT'] = static::findLineInFiles($arFilesInDir, $lineSearch);

		if (!$result['RESULT'])
		{
			$result['ERROR'][] = Loc::getMessage('INTERVOLGA_COMMON.NOT_FOUND');
		}

		return $result;
	}

	/**
	 * @param $dir
	 * @param $lineSearch
	 * @return array
	 */
	protected static function checkField($dir, $lineSearch)
	{
		$arError = array();

		// check dir
		if (!is_dir($dir))
		{
			$arError[] = Loc::getMessage('INTERVOLGA_COMMON.ERROR_DIR');
		}

		// check search line
		if (!static::trimString($lineSearch))
		{
			$arError[] = Loc::getMessage('INTERVOLGA_COMMON.ERROR_SEARCH_LINE');
		}

		return $arError;
	}

	/**
	 * @param $lineSearch
	 * @return string|void
	 */
	protected static function trimString($lineSearch)
	{
		$lineSearch = trim($lineSearch);
		if (strlen($lineSearch) > 3)
		{
			return $lineSearch;
		}

		return;
	}

	/**
	 * @param $dir
	 * @param $lineSearch
	 * @return string
	 */
	public static function getSearchResult($dir, $lineSearch)
	{
		$result = static::search($dir, $lineSearch);

		if ($result['RESULT'])
		{
			$result['INFO']['COUNT'] = count($result['RESULT']);
		}

		return $result;
	}

	/**
	 * @param $arFiles
	 * @param $lineSearch
	 * @return array
	 */
	protected static function findLineInFiles($arFiles, $lineSearch)
	{
		$result = array();

		foreach ($arFiles as $numFile => $nameFile)
		{
			$file = file($nameFile);
			$found = false;

			foreach ($file as $lineNum => $lineText)
			{
				if (strpos($lineText, $lineSearch) !== false)
				{
					$found = true;
				}
			}

			if ($found === true)
			{
				$result[] = $nameFile;
			}
		}

		return $result;
	}

	/**
	 * @param $dir
	 * @return array
	 */
	protected static function detectFiles($dir)
	{
		$filesInDir = array();

		$scanDir = scandir($dir);

		foreach ($scanDir as $numFile => $nameFile)
		{
			if ($nameFile == '.' || $nameFile == '..')
			{
				continue;
			}

			if ($dir == '.')
			{
				$addName = "";
			}
			else
			{
				$addName = "$dir/";
			}

			if (is_file($addName . $nameFile))
			{
				$ext = explode('.', $addName . $nameFile);
				$ext = $ext[count($ext) - 1];

				if (in_array($ext, static::$searchType))
				{
					$filesInDir[] = $addName . $nameFile;
				}
			}
			elseif (is_dir($addName . $nameFile))
			{
				$filesInDir[] = static::detectFiles($addName . $nameFile);
			}
		}

		return static::reformatData($filesInDir);
	}

	/**
	 * @param $filesInDir
	 * @return array
	 */
	protected static function reformatData($filesInDir)
	{
		$result = array();

		foreach ($filesInDir as $item)
		{
			if (!is_array($item))
			{
				$result[] = $item;
			}
			else
			{
				foreach ($item as $it)
				{
					if (!is_array($it))
					{
						$result[] = $it;
					}
				}
			}
		}

		return $result;
	}

	/**
	 * @param $dir
	 * @return string
	 */
	protected static function getPathDir($dir)
	{
		if ($dir = static::trimString($dir))
		{
			return $_SERVER['DOCUMENT_ROOT'] . $dir;
		}

		return;
	}
}