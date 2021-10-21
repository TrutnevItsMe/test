<?namespace Intervolga\Common\Iblock;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

/**
 * Class VideoProperty свойство инфоблока для хранения видео
 *
 * @author Анатолий Ерофеев
 */
class VideoProperty
{
	/**
	 * @var array типы видео - массив хранящий названия классов видео
	 */
	protected static $arVideoClasses = array(
		"Intervolga\\Common\\Iblock\\YoutubeVideo",
		"Intervolga\\Common\\Iblock\\LocalVideo",
	);

	/**
	 * Собственный метод для вывода плеера.
	 *
	 * Собственный метод необходим для решения проблемы с кешированием:
	 * файлы script.js и style.css в шаблоне intervolga:video, подключенного внутри
	 * news.list, news.detail... подключаются только когда не работает кеш.
	 *
	 * @param array $arProperty свойство
	 * @param bool|\CBitrixComponent $obComponent родительский компонент
	 * @param array $arParams параметры компонента
	 *
	 * @return string
	 */
	public static function GetPlayerHTML($arProperty, $obComponent = false, $arParams = array())
	{
		$arHTML = array();
		if ($arProperty["VALUE"])
		{
			if ($arProperty["VALUE"]["TYPE"])
			{
				$arProperty["VALUE"] = array($arProperty["VALUE"]);
			}
			$arComponentParams = array();
			if ($arParams)
			{
				foreach ($arParams as $paramKey => $paramValue)
				{
					if (strpos($paramKey, "~") === 0)
					{
						$arComponentParams["PARENT_PARAM_" . substr($paramKey, 1)] = $paramValue;
					}
				}
			}
			foreach ($arProperty["VALUE"] as $arValue)
			{
				$obPlayer = static::getVideoPlayerObject($arValue["TYPE"]);
				if ($obPlayer)
				{
					$arHTML[] = $obPlayer->GetPlayer($arProperty, $arValue, $obComponent, $arComponentParams);
				}
			}
		}

		return implode("/", $arHTML);
	}

	/**
	 * Получить типы видео, которые можно загрузить в свойство.
	 *
	 * @return array
	 */
	public static function GetVideoClasses()
	{
		return static::$arVideoClasses;
	}

	/**
	 * Действия перед удалением элемента инфоблока.
	 * @param $ID
	 *
	 * @return bool
	 */
	public static function OnIBlockElementDelete($ID)
	{
		\CModule::IncludeModule("iblock");

		// Получить ID инфоблока
		$iBlockID = \CIBlockElement::GetIBlockByID($ID);

		// Получить список свойств инфоблока с типом Видео
		$arProperties = static::GetPropertyList($iBlockID);

		// Узнать, есть ли у элемента инфоблока значения по свойству Видео
		if ($arProperties)
		{
			$arValues = array();

			$arOrder = array("ID" => "ASC");
			$arFilter = array("ID" => $ID, "IBLOCK_ID" => $iBlockID);
			$arSelectFields = array("ID", "ACTIVE", "NAME");
			foreach ($arProperties as $arProperty)
			{
				$arSelectFields[] = "PROPERTY_" . $arProperty["ID"];
			}
			/** @noinspection PhpDynamicAsStaticMethodCallInspection */
			$rsElements = \CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelectFields);
			while ($arElement = $rsElements->Fetch())
			{
				if ($arElement["PROPERTY_" . $arProperty["ID"] . "_VALUE"])
				{
					$arValues[$arElement["PROPERTY_" . $arProperty["ID"] . "_VALUE_ID"]] = $arElement["PROPERTY_" . $arProperty["ID"] . "_VALUE"];
				}
			}
			if ($arValues)
			{
				foreach ($arValues as $arValue)
				{
					static::Delete($arProperties, array("VALUE" => $arValue));
				}
			}
		}
	}

	/**
	 * Получить список свойств типа Видео для инфоблока.
	 *
	 * @param int $iBlockID
	 * @return array ассоциативный массив свойств [ID свойства] => [массив описания свойства]
	 */
	public static function GetPropertyList($iBlockID)
	{
		$arProperties = array();

		$arDescription = static::GetUserTypeDescription();

		$arOrder = array("ID" => "ASC");
		$arFilter = array("IBLOCK_ID" => $iBlockID, "PROPERTY_TYPE" => $arDescription["PROPERTY_TYPE"], "USER_TYPE" => $arDescription["USER_TYPE"]);
		$dbProperties = \CIBlockProperty::GetList($arOrder, $arFilter);
		while ($arProperty = $dbProperties->Fetch())
		{
			$arProperties[$arProperty["ID"]] = $arProperty;
		}

		return $arProperties;
	}

	/**
	 * Возвращает массив, описывающий поведение пользовательского свойства.
	 * Вызывается по событию OnIBlockPropertyBuildList.
	 *
	 * @return array массив, описывающий поведение пользовательского свойства
	 */
	public static function GetUserTypeDescription()
	{
		return array(
			"PROPERTY_TYPE" => "S",
			"USER_TYPE" => "IntervolgaVideo",
			"DESCRIPTION" => Loc::getMessage("INTERVOLGA_COMMON.VIDEO_PROPERTY_NAME"),
			"GetPropertyFieldHtml" => Array(__CLASS__, "getPropertyFieldHtml"),
			"ConvertToDB" => Array(__CLASS__, "convertToDB"),
			"ConvertFromDB" => Array(__CLASS__, "convertFromDB"),
			"GetAdminListViewHTML" => array(__CLASS__, "getAdminListViewHTML"),
			"GetPublicViewHTML" => Array(__CLASS__, "getPublicViewHTML"),
			"GetLength" => Array(__CLASS__, "getLength"),
			"CheckFields" => Array(__CLASS__, "checkFields"),
			"GetSettingsHTML" => Array(__CLASS__, "getSettingsHTML"),
			"PrepareSettings" => Array(__CLASS__, "prepareSettings"),
		);
	}

	/**
	 * Вернуть HTML отображения элемента управления для редактирования значений свойства в административной части.
	 *
	 * @param array $arProperty Метаданные свойства
	 * @param array $value Значение свойства
	 * @param array $strHTMLControlName Имена элементов управления для заполнения значения свойства и его описания
	 * @return string Строка
	 */
	public static function getPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
	{
		ob_start();
		?>
		<?= static::GetPropertyFieldEditStyles() ?>
		<?= static::GetPropertyFieldEditScript() ?>
		<div class="invervolga-video">
			<fieldset class="common-settings video-settings">
				<legend class="main"><?=Loc::getMessage("INTERVOLGA_COMMON.VIDEO_CHOOSE_TYPE")?></legend>
				<label><?=Loc::getMessage("INTERVOLGA_COMMON.VIDEO_TYPE")?></label>

				<label class="checkbox">
					<input type="radio" name="<?= $strHTMLControlName["VALUE"] ?>[TYPE]" value=""
					       class="video-type" <?= !$value["VALUE"]["TYPE"] ? "checked" : "" ?>
					       onclick="ivVideoTypeChanged(this)"/>
					<? if ($value["VALUE"]["TYPE"]): ?>
						<?=Loc::getMessage("INTERVOLGA_COMMON.VIDEO_DELETE")?><? else: ?>
						<?=Loc::getMessage("INTERVOLGA_COMMON.VIDEO_NOT_SET")?><?endif ?>
				</label>
				<? foreach (static::$arVideoClasses as $sClassName): ?>
					<label class="checkbox">
						<input type="radio" name="<?= $strHTMLControlName["VALUE"] ?>[TYPE]" value="<?= $sClassName ?>"
						       class="video-type" <?= $value["VALUE"]["TYPE"] == $sClassName ? "checked" : "" ?>
						       onclick="ivVideoTypeChanged(this)"/>
						<? if ($obVideoPlayer = static::getVideoPlayerObject($sClassName)): ?>
							<?= $obVideoPlayer::getName() ?>
						<? endif ?>
					</label>
				<? endforeach ?>
			</fieldset>
			<? foreach (static::$arVideoClasses as $sClassName): ?>
				<? if ($obVideoPlayer = static::getVideoPlayerObject($sClassName)): ?>
					<? $obVideoPlayer->showPropertyFieldHtml($arProperty, $value, $strHTMLControlName) ?>
				<? endif ?>
			<? endforeach ?>
			<? if ($arProperty["MULTIPLE"] == "Y"): ?>
				<hr>
			<? endif ?>
		</div>
		<?
		$html = ob_get_clean();

		return $html;
	}

	/**
	 * Получить стили для формы вывода видео.
	 *
	 * @return string HTML-код для вставки на страницу
	 */
	protected static function GetPropertyFieldEditStyles()
	{
		ob_start();
		?>
		<style type="text/css">
			.video-settings {
				border: 0 none;
			}

			.video-settings legend {
				margin-left: 154px;
			}

			.video-settings legend.main {
				font-weight: bold;
			}

			.video-settings input[type=text] {
				width: 300px;
			}

			.video-settings input.size {
				width: 50px;
			}

			.video-settings label {
				width: 150px;
				display: inline-block;
				text-align: right;
				vertical-align: top;
			}

			.video-settings label.checkbox {
				width: auto;
				display: inline;
			}

			.video-settings span.required {
				color: #ff0000;
			}
		</style>
		<?
		return ob_get_clean();
	}

	/**
	 * Получить скрипты для формы вывода видео.
	 *
	 * @return string HTML-код для вставки на страницу
	 */
	protected static function GetPropertyFieldEditScript()
	{
		ob_start();
		?>
		<script type="text/javascript">
			BX.ready(function () {
				var ivTypeRadio = BX.findChildren(
					document,
					{
						"tag": "input",
						"class": "video-type"
					},
					true
				);
				for (var i in ivTypeRadio) {
					if (ivTypeRadio.hasOwnProperty(i)) {
						if (ivTypeRadio[i].checked) {
							var ivContainter = BX.findParent(
								ivTypeRadio[i], {
									"class": "invervolga-video"
								},
								true
							);
							ivShowFieldsets(ivContainter, ivTypeRadio[i].value);
						}
					}
				}
			});

			/**
			 * Отобразить настройки видео.
			 *
			 * @param ivContainter
			 * @param selectedType
			 * @returns {boolean}
			 */
			function ivShowFieldsets(ivContainter, selectedType) {
				var allFieldsets = BX.findChildren(
					ivContainter,
					{
						"tag": "fieldset"
					},
					true
				);
				for (var j in allFieldsets) {
					if (allFieldsets.hasOwnProperty(j)) {
						BX.style(allFieldsets[j], "display", "none");
					}
				}
				var fieldsetsToShow = BX.findChildren(
					ivContainter,
					{
						"tag": "fieldset",
						"class": selectedType
					},
					true
				);
				fieldsetsToShow = fieldsetsToShow.concat(BX.findChildren(
					ivContainter,
					{
						"tag": "fieldset",
						"class": "common-settings"
					},
					true
				));
				for (j in fieldsetsToShow) {
					if (fieldsetsToShow.hasOwnProperty(j)) {
						BX.style(fieldsetsToShow[j], "display", "block");
					}
				}
				return false;
			}

			function ivVideoTypeChanged(input) {
				var videoPropertyContainter = BX.findParent(
					input, {
						"class": "invervolga-video"
					},
					true
				);
				ivShowFieldsets(videoPropertyContainter, input.value);
			}
		</script>
		<?
		return ob_get_clean();
	}

	/**
	 * Получает объект заданного класса видеоплеера.
	 *
	 * @param string $className название класса видеоплеера
	 *
	 * @return bool|VideoPlayer
	 */
	protected static function getVideoPlayerObject($className)
	{
		if (in_array($className, static::$arVideoClasses) && class_exists($className))
		{
			/**
			 * @var $obVideoPlayer VideoPlayer
			 */
			$obVideoPlayer = new $className();

			return $obVideoPlayer;
		}

		return false;
	}

	/**
	 * Преобразование значения в пригодный для БД формат.
	 *
	 * @param array $arProperty Метаданные свойства
	 * @param array $value Значение свойства
	 * @return string Строка представление для БД
	 */
	public static function convertToDB($arProperty, $value)
	{
		if ($obVideoPlayer = static::getVideoPlayerObject($value["VALUE"]["TYPE"]))
		{
			$value = $obVideoPlayer->onBeforeSave($arProperty, $value);

			return serialize($value["VALUE"]);
		}
		else
		{
			static::Delete($arProperty, $value);

			return array("VALUE" => false, "DESCRIPTION" => false);
		}
	}

	/**
	 * Удалить все данные, связанные с видео.
	 *
	 * @param array $arProperty
	 * @param array $arValue
	 */
	public static function Delete($arProperty, $arValue)
	{
		foreach (static::$arVideoClasses as $sClassName)
		{
			$obVideoPlayer = static::getVideoPlayerObject($sClassName);
			$obVideoPlayer::delete($arProperty, $arValue);
		}
	}

	/**
	 * Преобразование значения из пригодного для БД формата.
	 *
	 * @param array $arProperty Метаданные свойства
	 * @param string $value Значение свойства прочитанное из базы данных
	 * @return array Внешнее представление значения свойства
	 */
	public static function convertFromDB($arProperty, $value)
	{
		return array("VALUE" => unserialize($value["VALUE"]), "DESCRIPTION" => $value["DESCRIPTION"]);
	}

	/**
	 * Возвращает длину свойства.
	 *
	 * @param array $property
	 * @param array $value
	 *
	 * @return int
	 */
	public static function getLength($property, $value)
	{
		if ($value["VALUE"]["TYPE"])
		{
			if ($videoPlayer = static::getVideoPlayerObject($value["VALUE"]["TYPE"]))
			{
				$errors = $videoPlayer->checkFields($property, $value);
				if (!$errors)
				{
					return 1;
				}
			}
		}
		return 0;
	}
	/**
	 * Функция должна проверить корректность значения свойства и вернуть массив. Пустой если ошибок нет и с сообщениями об ошибках если есть.
	 *
	 * Вызывается перед добавлением или изменением элемента.
	 *
	 * @param array $arProperty
	 * @param array $value
	 *
	 * @return array
	 */
	public static function checkFields($arProperty, $value)
	{
		$errors = array();
		if ($value["VALUE"]["TYPE"])
		{
			if ($obVideoPlayer = static::getVideoPlayerObject($value["VALUE"]["TYPE"]))
			{
				$errors = array_merge($errors, $obVideoPlayer->checkFields($arProperty, $value));
			}
		}

		if ($arProperty["ID"] && $arProperty["IS_REQUIRED"] == "Y")
		{
			if (!$obVideoPlayer = static::getVideoPlayerObject($value["VALUE"]["TYPE"]))
			{
				$errors[] = $arProperty["NAME"] . " [" . ($arProperty["CODE"] ? $arProperty["CODE"] : $arProperty["ID"]) . "]: ".Loc::getMessage("INTERVOLGA_COMMON.VIDEO_TYPE_NOT_SET");
			}
		}

		return $errors;
	}

	/**
	 * Вернуть безопасный HTML отображения значения свойства в списке элементов административной части.
	 *
	 * @param array $arProperty Метаданные свойства
	 * @param array $value Значение свойства
	 * @param array $strHTMLControlName Имена элементов управления для заполнения значения свойства и его описания
	 * @return string Строка
	 */
	public static function getAdminListViewHTML($arProperty, $value, $strHTMLControlName)
	{
		return $value["VALUE"]["TYPE"];
	}

	/**
	 * Функция должна вернуть безопасный HTML отображения значения свойства в публичной части сайта. Если она вернет пустое значение, то значение отображаться не будет.
	 *
	 * @deprecated используйте GetPlayerHTML
	 *
	 * @param array $arProperty
	 * @param string $value
	 * @param array $strHTMLControlName
	 *
	 * @return string
	 */
	public static function getPublicViewHTML($arProperty, $value, $strHTMLControlName)
	{
		return Loc::getMessage("INTERVOLGA_COMMON.VIDEO_USE_COMPONENT");
	}

	/**
	 * Получить дополнительные настройки свойства (при редактировании самого свойства, а не значений).
	 *
	 * @param array $arProperty
	 * @param array $strHTMLControlName
	 * @param array $arPropertyFields
	 * @return string
	 */
	public static function getSettingsHTML($arProperty, $strHTMLControlName, &$arPropertyFields)
	{
		$arPropertyFields = array(
			"HIDE" => array("FILTRABLE", "ROW_COUNT", "COL_COUNT", "DEFAULT_VALUE"), //will hide the field
			"SET" => array("FILTRABLE" => "N"), //if set then hidden field will get this value
		);
		ob_start();
		?>

		<tr>
			<td><?=Loc::getMessage("INTERVOLGA_COMMON.VIDEO_SIZE")?></td>
			<td>
				<input type="text" name="<?= $strHTMLControlName["NAME"] ?>[WIDTH]" class="size"
				       value="<?= $arProperty["USER_TYPE_SETTINGS"]["WIDTH"] ?>"/>
				x
				<input type="text" name="<?= $strHTMLControlName["NAME"] ?>[HEIGHT]" class="size"
				       value="<?= $arProperty["USER_TYPE_SETTINGS"]["HEIGHT"] ?>"/>
			</td>
		</tr>

		<?
		return ob_get_clean();
	}

	/**
	 * Подготовить дополнительные настройки свойства.
	 *
	 * @param array $arProperty
	 * @return array
	 */
	public static function prepareSettings($arProperty)
	{
		if (!$arProperty["USER_TYPE_SETTINGS"])
		{
			$arProperty["USER_TYPE_SETTINGS"] = array();
		}

		if (!$arProperty["USER_TYPE_SETTINGS"]["WIDTH"])
		{
			$arProperty["USER_TYPE_SETTINGS"]["WIDTH"] = 560;
		}
		if (!$arProperty["USER_TYPE_SETTINGS"]["HEIGHT"])
		{
			$arProperty["USER_TYPE_SETTINGS"]["HEIGHT"] = 315;
		}

		return $arProperty["USER_TYPE_SETTINGS"];
	}
}
