<?php

namespace Intervolga\Custom\Tools;


trait Logger {

    /**
     * @var
     */
    protected static $debug_backtrace;

    /**
     * @return string
     */
    public static function getRootLogDir() {
        return "/upload/log/";
    }

    /**
     * @return bool
     */
    public static function getSaveByDate() {
        return false;
    }

    /**
     * @return string
     */
    public static function getSaveByDateFormat() {
        return "Y-m-d";
    }

    /**
     * Возвращает имя файла лога
     * @return string
     */
    protected static function getFileName($method = NULL) {
        if (!$method) {
            if (!static::$debug_backtrace) {
                static::$debug_backtrace = debug_backtrace();
            }
            $method = static::$debug_backtrace[1]['function'];
        }

        return $method . (static::getSaveByDate() ? "_" . date(static::getSaveByDateFormat()) : "") . ".log";
    }

    public static function getLogDir() {

        return static::getRootLogDir() . str_replace("\\", "/", __CLASS__) . "/";
    }

    /**
     * @param $data
     * @param int $debug_backtrace_level
     */
    public static function log($data, $debug_backtrace_level = 0) {
        static::$debug_backtrace = debug_backtrace();
        $file = static::getLogDir() . static::getFileName();
        if ($db = static::getDebugBacktrace($debug_backtrace_level)) {
            $data = ["data" => $data];
            $data["debug_backtrace"] = $db;
        }
        static::logToFile($file, $data);
    }

    public static function getDebugBacktrace($debug_backtrace_level) {
        $res = [];
        if ($debug_backtrace_level) {
            if ($debug_backtrace_level == -1 || $debug_backtrace_level > count(static::$debug_backtrace)) {
                $debug_backtrace_level = count(static::$debug_backtrace) - 1;
            }
            for($i = 0; $i < $debug_backtrace_level; $i++) {
                $buf = static::$debug_backtrace[$debug_backtrace_level - $i];
                $res[$i] = [
                    'file' => $buf['file'],
                    'line' => $buf['line'],
                    'function' => $buf['function'],
                ];
            }
        }
        return $res;
    }

    static $n = [];

    /**
     * @param $file
     * @param $data
     * @param bool $numerate
     * @param int $debug_backtrace_level
     */
    public static function logToFile($file, $data, $numerate = false, $debug_backtrace_level = 0) {
        static::$debug_backtrace = debug_backtrace();
        if ($numerate && !static::$n[$file]) static::$n[$file] = 0;
        if ($numerate) {
            static::$n[$file]++;
        }
        if ($db = static::getDebugBacktrace($debug_backtrace_level)) {
            $data = ["data" => $data];
            $data["debug_backtrace"] = $db;
        }
        static::writeToFile($file, ($numerate ? (static::$n[$file].") ") : ""). date('d.m.Y H:i:s ').': '.print_r($data, true)."\n");
    }

    /**
     * @param $file
     * @param $data
     * @param int $flags
     */
    public static function writeToFile($file, $data, $flags = FILE_APPEND) {
        $file_with_root = $_SERVER["DOCUMENT_ROOT"] . $file;
        if(!file_exists(dirname($file_with_root))) {
            static::createFileDir($file_with_root);
        }
        file_put_contents($file_with_root, $data, $flags);
    }

    /**
     * @param $file
     */
    public static function createFileDir($file) {
        $arr = explode('/', $file);
        $count = count($arr);

        $buf = "";
        for($i = 0; $i < $count - 1; $i++) {
            $buf .= $arr[$i] . "/";
            if(!file_exists($buf)) {
                mkdir($buf, 0777, true);
            }
        }
    }
}