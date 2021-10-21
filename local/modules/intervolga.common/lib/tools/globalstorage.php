<? namespace Intervolga\Common\Tools;

/**
 * Class GarbageStorage
 *
 * Realisation of
 * @link http://dev.1c-bitrix.ru/learning/course/?COURSE_ID=43&LESSON_ID=2806
 *
 * @package Intervolga\Common\Tools
 */
class GlobalStorage
{
	/**
	 * @var array|mixed[]
	 */
	private static $storage = array();

	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public static function set($name, $value)
	{
		self::$storage[$name] = $value;
	}

	/**
	 * @param string $name
	 *
	 * @return mixed
	 */
	public static function get($name)
	{
		return self::$storage[$name];
	}
}