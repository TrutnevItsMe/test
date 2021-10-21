<?php
namespace Intervolga\Common\Main\Diag;

require_once ($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/csv_data.php");

use Bitrix\Main\Application;
use Bitrix\Main\DB\Exception;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\IO\File;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Type\DateTime;

class CsvLog
{
    const LOGS_PATH = '/log/csv/';

    protected static $hitId = '';
	protected static $csvFile;

    /**
     * @param string $message
     * @param string $filename
     */
    public static function addMessage($message, $filename = '__bx_message_log.csv')
    {
        if(static::getLogOptions($filename))
            static::addLine(array('message' => $message), $filename);
    }

    /**
     * @param $filename
     * @return bool
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    protected static function getLogOptions($filename)
    {
        $inclMode=Option::get('intervolga.common', 'log_inclusion_type');
        switch ($inclMode)
        {
            case 'enable':
                return true;
            case 'disable':
                return false;
            case 'enable_without':
                $exclFiles=Option::get('intervolga.common', 'log_files_exclude');
                if(in_array($filename, explode(htmlspecialchars("\r\n"), $exclFiles)))
                    return false;
                else
                    return true;
            case'enable_until':
                $logUntilTime=Option::get('intervolga.common', 'log_until_time');
                $currentTime=date('d.m.y H:i:s');
                return $logUntilTime>=$currentTime;
        }
    }
    /**
     * @param array $record
     * @param string $filename
     * @throws \Bitrix\Main\IO\FileNotFoundException
     */
    public static function addLine(array $record, $filename = '__bx_log.csv')
    {
        if(static::getLogOptions($filename))
        {
            $record = static::addDefaultColumns($record);
            $filePath = static::getFilePath($filename);
            $file = new File($filePath);
            $fileSize = $file->isExists() ? $file->getSize() : 0;
            if (!$fileSize) {
                static::putCsvRow($filePath, array_keys($record));
            }
            static::putCsvRow($filePath, $record);
        }
    }

    /**
     * @param array $record
     * @return array
     */
    protected static function addDefaultColumns(array $record)
    {
        global $USER;
        static::$csvFile = new \CCSVData();
		static::$csvFile->SetFieldsType('R');
		static::$csvFile->SetDelimiter(',');if (!static::$hitId)
        {
            static::$hitId = uniqid();
        }
        $microTime = microtime();
        $explode = explode(' ', $microTime);
        $record['session'] = bitrix_sessid();
        $record['day'] = date('Y-m-d');
        $record['date'] = date('Y-m-d H:i:s\'') . str_pad(round($explode[0]*1000), 4, '0', STR_PAD_LEFT);
        $record['hit_id'] = static::$hitId;
        $record['ip'] = $_SERVER['REMOTE_ADDR'];
        $record['user'] = ($USER && is_object($USER) && $USER instanceof \CUser) ? $USER->getId() : 'unknown';
        $record['referrer'] = $_SERVER['HTTP_REFERER'];
        $record['url'] = $_SERVER['REQUEST_URI'];
        $record['host'] = $_SERVER['SERVER_NAME'];
        $backTrace = static::getCallerBacktrace();
        $record['file'] = $backTrace['file'];
        $record['line'] = $backTrace['line'];

        return $record;
    }

    /**
     * @return array
     */
    protected static function getCallerBacktrace()
    {
        $lastClassBacktrace = array();
        if (function_exists("debug_backtrace"))
        {
            $debugBacktrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            foreach ($debugBacktrace as $backTrace)
            {
                if ($backTrace['class'] == __CLASS__)
                {
                    $lastClassBacktrace = $backTrace;
                }
                else
                {
                    break;
                }
            }
        }
        return $lastClassBacktrace;
    }

    /**
     * @param string $filename
     * @return string
     */
    protected static function getFilePath($filename)
    {
        Directory::createDirectory(Application::getDocumentRoot() . '/log/csv/');
        return Application::getDocumentRoot() . static::LOGS_PATH . strtolower($filename);
    }

    /**
     * @param string $filePath
     */
    protected static function putCsvRow($filePath, array $row)
    {
        $row = array_values($row);
		static::$csvFile->SaveFile($filePath,  $row);

    }
}