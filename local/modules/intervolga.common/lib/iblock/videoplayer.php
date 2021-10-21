<?namespace Intervolga\Common\Iblock;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);


/**
 * Class VideoPlayer класс-родитель для загрузки, хранения и воспроизведения видео.
 */
abstract class VideoPlayer
{
	/**
	 * Возвращает человекопонятное описание типа хранимого видео.
	 *
	 * @return string
	 */
	public static function getName()
	{
		return __CLASS__;
	}

	/**
	 * Возвращает массив для настроек компонента вывода видео.
	 *
	 * @return array массив настроек или FALSE
	 */
	public static function getComponentParams()
	{
		return array();
	}

	/**
	 * Удалить все данные, связанные с видео.
	 *
	 * @param array $arProperty
	 * @param array $value
	 */
	public static function delete($arProperty, $value)
	{
	}

	/**
	 * Показывает код для редактирования значения свойства.
	 *
	 * @param array $arProperty
	 * @param array $value
	 * @param array $strHTMLControlName
	 *
	 * @return mixed
	 */
	public abstract function showPropertyFieldHtml($arProperty, $value, $strHTMLControlName);

	/**
	 * Проверяет корректность значения свойства. Возвращает массив ошибок.
	 *
	 * @param array $arProperty
	 * @param array $value
	 *
	 * @return array|string[]
	 */
	public abstract function checkFields($arProperty, $value);

	/**
	 * Изменяет значение свойства перед сохранением.
	 *
	 * @param array $arProperty
	 * @param array $value
	 *
	 * @return array
	 */
	public abstract function onBeforeSave($arProperty, $value);

	/**
	 * Отсеивает из массива значения, ключей которых нет в массиве $arAllowedKeys.
	 *
	 * @param array $array искомый массив
	 * @param array $arAllowedKeys массив разрешенных ключей
	 *
	 * @return array просеенный массив
	 */
	public function arraySift($array, $arAllowedKeys)
	{
		if ($array && is_array($array))
		{
			foreach ($array as $i => $val)
			{
				if (!in_array($i, $arAllowedKeys))
				{
					unset($array[$i]);
				}
			}
		}

		return $array;
	}
}
