<? namespace Intervolga\Common\Tools;

use Bitrix\Main\EventManager;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\IO\File;
use Bitrix\Main\Config\Option;

/**
 * Class EventAutoLoader
 *
 * Registers event handlers using D7-like autoloader.
 * Event handlers must be created as methods named by event and class must be named by module.
 *
 * For example, handler for OnAfterIBlockElementAdd event of module iblock
 * must be defined as Iblock::onAfterIBlockElementAdd (case-insensitive for method name).
 * According to D7 autoloader rules, class must be placed in iblock.php file.
 *
 * @package Intervolga\Common\Tools
 */
class EventAutoLoader
{
	const DEFAULT_PATH = '/local/modules/intervolga.custom/lib/eventhandlers';

	/**
	 * Add event handlers for all files in directory.
	 *
	 * @param string $namespace classes namespace
	 * @param Directory $directory class php files directory
	 */
	public static function addDirectoryEventHandlers($namespace, Directory $directory)
	{
		if (Option::get('intervolga.common', 'autoload_custom_events') == 'Y')
		{
			$files = self::getPhpFiles($directory);
			foreach ($files as $file)
			{
				self::addFileEventHandlers($namespace, $file);
			}
		}
	}

	/**
	 * @param \Bitrix\Main\IO\Directory $directory
	 *
	 * @return array|File[]
	 * @throws \Bitrix\Main\IO\FileNotFoundException
	 */
	private static function getPhpFiles(Directory $directory)
	{
		$result = array();
		if ($directory->isExists())
		{
			foreach ($directory->getChildren() as $fileSystemEntry)
			{
				if ($fileSystemEntry instanceof File)
				{
					if ($fileSystemEntry->getExtension() == "php")
					{
						$result[] = $fileSystemEntry;
					}
				}
			}
		}
		return $result;
	}

	/**
	 * Add event handlers for file.
	 *
	 * @param string $namespace class namespace
	 * @param File $file class php file
	 */
	public static function addFileEventHandlers($namespace, File $file)
	{
		$module = str_replace(".php", "", $file->getName());
		/**
		 * @var $className \Intervolga\Common\Tools\EventHandler
		 */
		// Получаем правильное имя файла со всеми CamelCase
		$shortClassName = self::getClassName($file);
		$className = $namespace . $shortClassName;
		if (class_exists($className) && is_subclass_of($className, '\Intervolga\Common\Tools\EventHandler'))
		{
			$module = $className::getModuleName();
		}
		foreach (self::getClassMethods($className) as $method)
		{
			if ($method == 'getModuleName')
			{
				continue;
			}
			// Отдельно добавляем события для HL блоков
			if (substr($shortClassName, 0, 3) === 'HLB') {
				$hlbEvent = substr($shortClassName, 3) . $method;
				EventManager::getInstance()->addEventHandler('', $hlbEvent, array($className, $method));
			} else {
				EventManager::getInstance()->addEventHandler($module, $method, array($className, $method));
			}
		}
	}

	/**
	 * @param string $className
	 *
	 * @return array
	 */
	private static function getClassMethods($className)
	{
		if (class_exists($className))
		{
			return get_class_methods($className);
		}
		else
		{
			return array();
		}
	}
	
	/**
	 * https://stackoverflow.com/questions/7153000/get-class-name-from-file
	 * @param File $file файл с классом
	 * @return string правильное имя класса
	 */
	private static function getClassName(File $file) {
		$fp = $file->open('r');
		$class = $buffer = '';
		$i = 0;
		while (!$class) {
			if (feof($fp)) break;
			
			$buffer .= fread($fp, 512);
			$tokens = token_get_all($buffer);
			
			if (strpos($buffer, '{') === false) continue;
			
			for (;$i<count($tokens);$i++) {
				if ($tokens[$i][0] === T_CLASS) {
					for ($j=$i+1;$j<count($tokens);$j++) {
						if ($tokens[$j] === '{') {
							$class = $tokens[$i+2][1];
						}
					}
				}
			}
		}
		return $class;
	}
}