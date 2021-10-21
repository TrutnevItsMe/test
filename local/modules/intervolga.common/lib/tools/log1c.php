<? namespace Intervolga\Common\Tools;

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Context;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\IO\File;
use Intervolga\Common\Tools\Orm\Log1cTable;

class Log1c
{
	const LOGS_PATH = '/log/1c';

	protected static $sectionsAdded = 0;
	protected static $sectionsUpdated = 0;
	protected static $sectionsDeleted = 0;

	public static function logResponse()
	{
		$request = Context::getCurrent()->getRequest();
		$type = $request->get('type');
		$mode = $request->get('mode');
		if (static::isUserHasRights())
		{
			if ($content = static::getSiteOutput())
			{
				try
				{
					$responseFile = static::getResponseFile($type, $mode);
					$responseFile->putContents($content);
					$log = $responseFile->getPath();
				}
				catch (\Exception $exception)
				{
					$log = $exception->getMessage();
				}
				catch (\Error $error)
				{
					$log = $error->getMessage();
				}
				Log1cTable::logUpdate(array(
					'RESPONSE' => $content,
					'RESPONSE_FILE' => $log,
				));
			}

			Log1cTable::logUpdate(array(
				'SECTIONS_ADDED' => static::$sectionsAdded,
				'SECTIONS_UPDATED' => static::$sectionsUpdated,
				'SECTIONS_DELETED' => static::$sectionsDeleted,
			));
		}
	}

	/**
	 * @return bool
	 */
	protected static function isUserHasRights()
	{
		global $USER;
		$result = false;
		$groups = static::getPermittedGroups();
		if (!!$USER && is_object($USER) && ($USER instanceof \CUser))
		{
			$userGroups = $USER->getUserGroupArray();
			if (array_intersect($groups, $userGroups))
			{
				$result = true;
			}
		}

		return $result;
	}

	/**
	 * @return int[]
	 * @throws \Bitrix\Main\ArgumentNullException
	 */
	protected static function getPermittedGroups()
	{
		$groups = array(1);
		$groups = array_merge($groups, explode(",", Option::get('sale', '1C_SALE_GROUP_PERMISSIONS')));
		$groups = array_merge($groups, explode(",", Option::get('catalog', '1C_GROUP_PERMISSIONS')));
		$groups = array_merge($groups, explode(",", Option::get('catalog', '1CE_GROUP_PERMISSIONS')));
		$groups = array_diff($groups, array(''));
		$groups = array_unique($groups);

		return $groups;
	}

	/**
	 * @return string
	 */
	protected static function getSiteOutput()
	{
		$content = ob_get_contents();
		if (defined('BX_UTF') && BX_UTF && $content)
		{
			$content = iconv('windows-1251', 'utf-8', $content);
		}

		return $content;
	}

	/**
	 * @param string $type
	 * @param string $mode
	 *
	 * @return \Bitrix\Main\IO\File
	 */
	protected static function getResponseFile($type, $mode)
	{
		$copyDir = static::makeCopyDir($type);
		$responsePath = $copyDir->getPath() . '/' . static::getResponseFileName($mode);

		return new File($responsePath);
	}

	/**
	 * @param string $mode
	 *
	 * @return string
	 */
	protected static function getResponseFileName($mode)
	{
		$milliSec = static::getMicroseconds();
		$ext = 'log';
		if (in_array($mode, array('info', 'query')))
		{
			$ext = 'xml';
		}
		return $mode . '_' . $milliSec . '.' . $ext;
	}

	/**
	 * @return int
	 */
	public static function getMicroseconds()
	{
		$microtime = microtime(true);
		return str_pad(round(fmod($microtime, 1)*1000), 3, '0', STR_PAD_LEFT);
	}

	public static function copyFile()
	{
		$mode = (isset($_GET['mode']) ? $_GET['mode'] : $_POST['mode']);
		$type = (isset($_GET['type']) ? $_GET['type'] : $_POST['type']);
		$filename = (isset($_GET['filename']) ? $_GET['filename'] : $_POST['filename']);

		if ($mode == 'import' && !empty($filename) && static::isUserHasRights()) {
			$importFile = static::getImportingFile($type, $filename);
			if ($importFile && $importFile->isExists())
			{
				$copyFile = static::getCopyFile($type, $filename);
				if (!$copyFile->isExists())
				{
					static::createFilesMap($importFile->getDirectory()->getPath(), $copyFile->getDirectory()->getPath() . '/map.log');
					copy($importFile->getPath(), $copyFile->getPath());
				}
				Log1cTable::logUpdate(array(
					'COPY_FILE' => $copyFile->getPath(),
					'FILES_MAP' => $copyFile->getDirectory()->getPath() . '/map.log',
				));
			}
		}
	}

	/**
	 * @return bool
	 */
	public static function is1cPage()
	{
		global $APPLICATION;
		$pages = array(
			'/bitrix/admin/1c_exchange.php',
			'/bitrix/admin/1c_exchange_custom.php',
			'/bitrix/admin/1c_custom_exchange.php',
			'/bitrix/admin/custom_1c_exchange.php',
			'/bitrix/admin/custom_exchange_1c.php',
		);
		$mode = (isset($_GET['mode']) ? $_GET['mode'] : $_POST['mode']);
		$type = (isset($_GET['type']) ? $_GET['type'] : $_POST['type']);
		return ($mode && $type && in_array($APPLICATION->GetCurPage(), $pages));
	}

	/**
	 * @return bool
	 * @throws \Bitrix\Main\ArgumentNullException
	 */
	public static function isDebugMode()
	{
		return Option::get('intervolga.common', 'debug_1c') == 'Y';
	}

	/**
	 * @param string $type
	 *
	 * @return \Bitrix\Main\IO\Directory
	 */
	protected static function makeCopyDir($type)
	{
		$path = array(
			Application::getDocumentRoot() . static::LOGS_PATH,
			static::getTypeDir($type),
			date('Y.m.d/H.00-H.59/H.i.s'),
		);
		$copyDir = implode('/', $path) . '/';

		return Directory::createDirectory($copyDir);
	}

	public static function onAfterIBlockSectionAdd($fields)
	{
		static::$sectionsAdded++;
	}

	public static function onAfterIBlockSectionUpdate($fields)
	{
		static::$sectionsUpdated++;
	}

	public static function onAfterIBlockSectionDelete($fields)
	{
		static::$sectionsDeleted++;
	}

	/**
	 * @param \Bitrix\Main\IO\Directory $dir
	 * @param string $rootPath
	 *
	 * @return array
	 * @throws \Bitrix\Main\IO\FileNotFoundException
	 */
	protected static function getDirectotyMap(Directory $dir, $rootPath)
	{
		$map = array();
		foreach ($dir->getChildren() as $fileSystemEntry)
		{
			if ($fileSystemEntry instanceof File)
			{
				$path = $fileSystemEntry->getPath();
				$path = str_replace($rootPath, '', $path);
				$map[$path] = $fileSystemEntry->getSize();
			}
			if ($fileSystemEntry instanceof Directory)
			{
				$map = array_merge($map, static::getDirectotyMap($fileSystemEntry, $rootPath));
			}
		}
		return $map;
	}

	/**
	 * @param string $dirPath
	 * @param string $mapPath
	 */
	protected static function createFilesMap($dirPath, $mapPath)
	{
		$dir = new Directory($dirPath);
		$map = static::getDirectotyMap($dir, $dirPath);
		$content = '';
		foreach ($map as $file => $size)
		{
			$content .= $file .' -- ' . \CFile::formatSize($size) . "\n";
		}

		if (File::putFileContents($mapPath, $content))
		{
			$log = $mapPath;
		}
		else
		{
			$log = 'File::putFileContents fail';
		}
		Log1cTable::logUpdate(array(
			'FILES_MAP' => $log,
		));
	}

	/**
	 * @param string $type
	 *
	 * @return string
	 */
	protected static function getTypeDir($type)
	{
		switch ($type)
		{
			case 'sale':
				return '1c_exchange';
			case 'catalog':
				return '1c_catalog';
			case 'reference':
				return '1c_highloadblock';
			default:
				return $type;
		}
	}

	/**
	 * @param string $type
	 * @param string $filename
	 *
	 * @return \Bitrix\Main\IO\File|null
	 * @throws \Bitrix\Main\ArgumentNullException
	 */
	public static function getImportingFile($type, $filename)
	{
		$result = null;
		if ($filename && $type)
		{
			$path = array(
				Application::getDocumentRoot(),
				Option::get('main', 'upload_dir'),
				static::getTypeDir($type),
				$filename
			);
			$file = new File(implode('/', $path));
			$result = $file;
		}

		return $result;
	}

	/**
	 * @param string $type
	 * @param string $filename
	 *
	 * @return \Bitrix\Main\IO\File
	 */
	protected static function getCopyFile($type, $filename)
	{
		$copyPathExist = Log1cTable::getCopiedFile($filename);
		if ($copyPathExist)
		{
			$path = $copyPathExist;
		}
		else
		{
			$dir = static::makeCopyDir($type);
			$path = $dir->getPath() . '/' . $filename;
		}

		return new File($path);
	}
}