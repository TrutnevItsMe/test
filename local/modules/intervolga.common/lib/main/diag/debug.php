<? namespace Intervolga\Common\Main\Diag;

class Debug extends \Bitrix\Main\Diag\Debug
{
	const LOG_FILE_NAME = "/log/__bx_log.log";

	/**
	 * @param mixed $var
	 * @param string $varName
	 * @param string $fileName
	 */
	public static function dumpToFile($var, $varName = "", $fileName = "")
	{
		if (empty($fileName))
		{
			$fileName = static::LOG_FILE_NAME;
		}
		static::logDate($fileName);
		parent::dumpToFile($var, $varName, $fileName);
	}

	/**
	 * @param mixed $var
	 * @param string $varName
	 * @param string $fileName
	 */
	public static function writeToFile($var, $varName = "", $fileName = "")
	{
		if (empty($fileName))
		{
			$fileName = static::LOG_FILE_NAME;
		}
		static::logDate($fileName);
		parent::writeToFile($var, $varName, $fileName);
	}

	/**
	 * @param string $fileName
	 */
	protected static function logDate($fileName)
	{
		if (function_exists("debug_backtrace"))
		{
			$debugBacktrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
			if ($debugBacktrace && is_array($debugBacktrace) && $debugBacktrace[1])
			{
				$date = date("Y-m-d H:i:s");
				$file = $debugBacktrace[1]["file"];
				$line = $debugBacktrace[1]["line"];
				parent::writeToFile("Log at [$date] on <$file:$line>", "", $fileName);
			}
		}
	}
}