<?php
namespace Intervolga\Common\Agents;

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\IO\File;
use Bitrix\Main\Localization\Loc;
use Intervolga\Common\Tools\Log1c;

Loc::loadMessages(__FILE__);

class Log1CAgent
{
	const RESERVE_TIME = 2;
	protected static $maxFinishTime = 0;
	protected static $isTimeout = false;

	/**
	 * @return int
	 */
	public static function getLogRootSize()
	{
		static::$isTimeout = false;
		$maxLifeTime = intval(ini_get("max_execution_time"));
		static::$maxFinishTime = microtime(true) + $maxLifeTime - static::RESERVE_TIME;
		$size = static::getSize(static::getLogRoot(), true);
		static::$maxFinishTime = 0;
		return $size;
	}

	/**
	 * @return bool
	 */
	public static function isTimeoutRootSize()
	{
		return static::$isTimeout;
	}

	/**
	 * @return string
	 */
	public static function run()
	{
		if ($maxSize = static::getMaxSize())
		{
			$originalSize = static::getSize(static::getLogRoot());
			$currentSize = $originalSize;
			while ($currentSize > $maxSize)
			{
				$oldestDir = static::getOldestTypeDir();
				if ($oldestDir)
				{
					$currentSize -= static::getSize($oldestDir);
					$oldestDir->delete();
				}
				else
				{
					break;
				}
			}
			static::logAgent($maxSize, $originalSize, $currentSize);
		}

		return __METHOD__ . '();';
	}

	/**
	 * @param int $maxSize
	 * @param int $originalSize
	 * @param int $currentSize
	 */
	protected static function logAgent($maxSize, $originalSize, $currentSize)
	{
		$params = array(
			'#ORIGINAL_SIZE#' => \CFile::formatSize($originalSize),
			'#CURRENT_SIZE#' => \CFile::formatSize($currentSize),
			'#DIFF_SIZE#' => \CFile::formatSize($originalSize - $currentSize),
			'#MAX_SIZE#' => \CFile::formatSize($maxSize),
		);
		if ($currentSize < $originalSize)
		{
			$message = 'INTERVOLGA_COMMON.AGENT_RUN';
		}
		else
		{
			$message = 'INTERVOLGA_COMMON.USELESS_AGENT_RUN';
		}
		\CEventLog::Log(
			"INFO",
			"INTERVOLGA_COMMON.LOG_1C_AGENT",
			"intervolga.common",
			__METHOD__ . '()',
			Loc::getMessage($message, $params)
		);
	}

	/**
	 * @return int
	 * @throws \Bitrix\Main\ArgumentNullException
	 */
	protected static function getMaxSize()
	{
		return intval(Option::get('intervolga.common', 'max_size_folder_1c_exchange'))*1024*1024;
	}

	/**
	 * @return \Bitrix\Main\IO\Directory
	 */
	protected static function getLogRoot()
	{
		static $result = null;
		if (!$result)
		{
			$root = Application::getDocumentRoot();
			$result = new Directory($root . Log1c::LOGS_PATH);
		}

		return $result;
	}

	/**
	 * @param Directory $directory
	 * @param bool $hasTimeOutCheck
	 * @return int
	 * @throws \Bitrix\Main\IO\FileNotFoundException
	 */
	protected static function getSize(Directory $directory, $hasTimeOutCheck = false)
	{
		$size = 0;
		if ($directory->isExists())
		{
			foreach ($directory->getChildren() as $child)
			{
				if (!$hasTimeOutCheck || (microtime(true) < static::$maxFinishTime))
				{
					if ($child instanceof Directory)
					{
						$size += static::getSize($child, $hasTimeOutCheck);
					}
					if ($child instanceof File)
					{
						$size += $child->getSize();
					}
				}
				else
				{
					static::$isTimeout = true;
					break;
				}
			}
		}

		return $size;
	}

	/**
	 * @return \Bitrix\Main\IO\Directory
	 * @throws \Bitrix\Main\IO\FileNotFoundException
	 */
	protected static function getOldestTypeDir()
	{
		/**
		 * @var \Bitrix\Main\IO\Directory $theOldest
		 */
		$theOldest = null;
		foreach (static::getLogRoot()->getChildren() as $typeDirectory)
		{
			if ($typeDirectory instanceof Directory)
			{
				if ($oldestForType = static::getOldestDirRecursive($typeDirectory))
				{
					if (!$theOldest)
					{
						$theOldest = $oldestForType;
					}
					elseif ($oldestForType->getCreationTime() < $theOldest->getCreationTime())
					{
						$theOldest = $oldestForType;
					}
				}
			}
		}

		return $theOldest;
	}

	/**
	 * @param \Bitrix\Main\IO\Directory $directory
	 *
	 * @return \Bitrix\Main\IO\Directory
	 * @throws \Bitrix\Main\IO\FileNotFoundException
	 */
	protected static function getOldestDir(Directory $directory)
	{
		/**
		 * @var \Bitrix\Main\IO\Directory $oldestDir
		 */
		$oldestDir = null;
		if ($directory->isExists())
		{
			foreach ($directory->getChildren() as $child)
			{
				if ($child instanceof Directory)
				{
					if (!$oldestDir)
					{
						$oldestDir = $child;
					}
					elseif ($child->getCreationTime() < $oldestDir->getCreationTime())
					{
						$oldestDir = $child;
					}
				}
			}
		}
		return $oldestDir;
	}

	/**
	 * @param \Bitrix\Main\IO\Directory $directory
	 *
	 * @return \Bitrix\Main\IO\Directory
	 */
	protected static function getOldestDirRecursive(Directory $directory)
	{
		$oldest = null;
		$result = static::getOldestDir($directory);
		if ($result)
		{
			while ($oldest = static::getOldestDir($result))
			{
				$result = $oldest;
			}
		}

		return $result;
	}
}