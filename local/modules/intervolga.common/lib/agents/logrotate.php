<?php
namespace Intervolga\Common\Agents;

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\IO\File;
use Bitrix\Main\Type\DateTime;
use Intervolga\Common\Main\Diag\CsvLog;

class LogRotate
{
	const LOGS_PATH = '/log/';

	/**
	 * @return string
	 */
	public static function run()
	{
		static::checkLogFiles();
		return __METHOD__ . '();';
	}

	public static function checkLogFiles()
	{
		if (static::getLogRotateDays())
		{
			foreach (static::getLogFiles() as $logFile)
			{
				if ($dateTime = static::getHistoryLogFileDate($logFile))
				{
					if (static::isTimeToDelete($dateTime))
					{
						$logFile->delete();
					}
				}
				else
				{
					static::renameLogFile($logFile, new DateTime());
				}
			}
		}
	}

	/**
	 * @return \Bitrix\Main\IO\File[]
	 * @throws \Bitrix\Main\IO\FileNotFoundException
	 */
	protected static function getLogFiles()
	{
		$result = array();
		$logRoots = array(
			new Directory(Application::getDocumentRoot() . static::LOGS_PATH),
			new Directory(Application::getDocumentRoot() . CsvLog::LOGS_PATH),
		);
		/**
		 * @var \Bitrix\Main\IO\Directory $logRoot
		 */
		foreach ($logRoots as $logRoot)
		{
			if ($logRoot->isExists())
			{
				foreach ($logRoot->getChildren() as $fsEntry)
				{
					if ($fsEntry instanceof File)
					{
						if (static::isLogFile($fsEntry))
						{
							$result[] = $fsEntry;
						}
					}
				}
			}
		}

		return $result;
	}

	/**
	 * @param \Bitrix\Main\IO\File $file
	 * @return bool
	 */
	protected static function isLogFile(File $file)
	{
		$hasLogExtension = $file->getExtension() == 'log';
		$hasCsvExtension = $file->getExtension() == 'csv';
		$hasLogInName = substr_count($file->getName(), 'log') > 0;
		return $hasLogExtension || $hasCsvExtension || $hasLogInName;
	}

	/**
	 * @param \Bitrix\Main\IO\File $file
	 * @return null|\Bitrix\Main\Type\DateTime
	 */
	protected static function getHistoryLogFileDate(File $file)
	{
		$matches = array();
		$regexp = '/-(?<Y>\d{4})-(?<m>\d{2})-(?<d>\d{2})--(?<H>\d{2})-(?<i>\d{2})-(?<s>\d{2})\./';
		if (preg_match($regexp, $file->getName(), $matches))
		{
			return DateTime::createFromTimestamp(mktime($matches['H'], $matches['i'], $matches['s'], $matches['m'], $matches['d'], $matches['Y']));
		}
		else
		{
			return null;
		}
	}

	/**
	 * @param \Bitrix\Main\Type\DateTime $dateTime
	 * @return bool
	 */
	protected static function isTimeToDelete(DateTime $dateTime)
	{
		$logRotateDays = static::getLogRotateDays();
		if ($logRotateDays)
		{
			return (time() - $dateTime->getTimestamp() > $logRotateDays * 60 * 60 * 24);
		}
		else
		{
			return false;
		}
	}

	/**
	 * @return int
	 * @throws \Bitrix\Main\ArgumentNullException
	 */
	protected static function getLogRotateDays()
	{
		return intval(Option::get('intervolga.common', 'log_rotate_days'));
	}

	/**
	 * @param \Bitrix\Main\IO\File $file
	 * @param \Bitrix\Main\Type\DateTime $dateTime
	 */
	protected static function renameLogFile(File $file, DateTime $dateTime)
	{
		$dateStr = $dateTime->format('-Y-m-d--H-i-s');
		$extension = $file->getExtension();
		$pathNoExtension = str_replace('.' . $extension, '', $file->getPath());

		$file->rename($pathNoExtension . $dateStr . '.' . $extension);
	}
}