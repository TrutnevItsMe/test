<? namespace Intervolga\Common\Tools;

use Bitrix\Main\Diag\ExceptionHandlerFormatter;
use Bitrix\Main\Diag\ExceptionHandlerLog;

class ExceptionHandler extends ExceptionHandlerLog
{
	/**
	 * @param \Error|\Exception $exception
	 * @param $logType
	 */
	public function write($exception, $logType)
	{
		static::logException($exception);
	}

	/**
	 * @param \Error|\Exception $exception
	 */
	public static function logException($exception)
	{
		$text = ExceptionHandlerFormatter::format($exception, false);
		\CEventLog::Log(
			"ERROR",
			"INTERVOLGA_COMMON.EXCEPTION",
			"intervolga.common",
			$exception->getFile() . ":" . $exception->getLine(),
			$text
		);
	}

	public function initialize(array $options)
	{
	}
}