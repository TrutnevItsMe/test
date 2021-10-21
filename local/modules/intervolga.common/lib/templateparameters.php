<? namespace Intervolga\Common;

use Bitrix\Main\Application;
use Bitrix\Main\IO\File;

/**
 * Class TemplateParameters
 *
 * API to read template parameters from /templates/<template name>/parameters.php
 *
 * @package Intervolga\Common
 */
class TemplateParameters
{
	/**
	 * @param string $filePath
	 * @return array
	 */
	protected static function getFromFile($filePath)
	{
		if (File::isFileExists($filePath))
		{
			include $filePath;
			if (isset($templateParameters) && is_array($templateParameters))
			{
				return $templateParameters;
			}
		}

		return array();
	}
	
	/**
	 * @return array
	 */
	public static function getAll()
	{
		$parameters = array();
		$paths = array();

		$paths[] = Application::getDocumentRoot() . "/bitrix/templates/.default/parameters.php";
		$paths[] = Application::getDocumentRoot() . "/local/templates/.default/parameters.php";
		$paths[] = Application::getDocumentRoot() . SITE_TEMPLATE_PATH . "/parameters.php";

		foreach ($paths as $path){
			$parameters = array_merge($parameters, static::getFromFile($path));
		}
		return $parameters;
	}

	/**
	 * @param string $itemName
	 *
	 * @return array
	 */
	public static function getResizeParam($itemName)
	{
		$params = self::getAll();
		if (isset($params["resize"][$itemName]) && is_array($params["resize"][$itemName]))
		{
			return $params["resize"][$itemName];
		}
		elseif (isset($params["resize"]["*"]) && is_array($params["resize"]["*"]))
		{
			return $params["resize"]["*"];
		}

		return array();
	}

	/**
	 * @param int|array $file
	 * @param string $itemName
	 *
	 * @return array|bool
	 * @throws \Bitrix\Main\ArgumentNullException
	 * @throws \Bitrix\Main\ArgumentTypeException
	 */
	public static function resize($file, $itemName)
	{
		$result = false;
		$params = static::getResizeParam($itemName);
		if ($file)
		{
			return resizeImageIV($file, $params);
		}
		elseif ($params["default"])
		{
			$result = is_array($file) ? $file : array();
			$result["SRC"] = $params["default"];
			$result["DEFAULT"] = "Y";
			if ($params["width"])
			{
				$result["WIDTH"] = $params["width"];
			}
			if ($params["height"])
			{
				$result["HEIGHT"] = $params["height"];
			}
		}

		return $result;
	}
}