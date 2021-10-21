<?namespace Intervolga\Common\Iblock;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

/**
 * Class LocalVideo класс для загрузки, хранения и воспроизведения видеофайлов.
 */
class LocalVideo extends VideoPlayer
{
	/**
	 * Возвращает человекопонятное описание типа хранимого видео.
	 *
	 * @return string
	 */
	public static function getName()
	{
		return Loc::getMessage("INTERVOLGA_COMMON.VIDEO_FILE");
	}

	/**
	 * Возвращает массив для настроек компонента вывода видео.
	 *
	 * @return bool|array массив настроек или FALSE
	 */
	public static function getComponentParams()
	{
		return array(
			"LOCAL_VIDEO" => Array(
				"NAME" => Loc::getMessage("INTERVOLGA_COMMON.VIDEO_PATH"),
				"TYPE" => "TEXT",
			),
			"LOCAL_COVER" => Array(
				"NAME" => Loc::getMessage("INTERVOLGA_COMMON.VIDEO_COVER"),
				"TYPE" => "TEXT",
			),
		);
	}

	/**
	 * Удалить все данные, связанные с видео.
	 *
	 * @param array $arProperty
	 * @param array $value
	 */
	public static function delete($arProperty, $value)
	{
		if ($value["VALUE"] && is_array($value["VALUE"]) && $value["VALUE"]["LOCAL"])
		{
			foreach (array("COVER", "COVER_OLD", "VIDEO", "VIDEO_OLD") as $key)
			{
				if ($value["VALUE"]["LOCAL"][$key])
				{
					if (is_numeric($value["VALUE"]["LOCAL"][$key]))
					{
						// Удалить файл
						\CFile::Delete($value["VALUE"]["LOCAL"][$key]);
					}
				}
			}
		}
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
	public function showPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
	{
		$videoFileName = $strHTMLControlName["VALUE"] . "[LOCAL][VIDEO]";
		$oldVideoFileName = $strHTMLControlName["VALUE"] . "[LOCAL][VIDEO_OLD]";
		$coverFileName = $strHTMLControlName["VALUE"] . "[LOCAL][COVER]";
		$oldCoverFileName = $strHTMLControlName["VALUE"] . "[LOCAL][COVER_OLD]";

		$arVideo = array();
		$arCover = array();
		if (is_array($value["VALUE"]) && $value["VALUE"]["LOCAL"])
		{
			if ($value["VALUE"]["LOCAL"]["VIDEO"])
			{
				$arVideo = $value["VALUE"]["LOCAL"]["VIDEO"];
			}

			if ($value["VALUE"]["LOCAL"]["COVER"])
			{
				$arCover = $value["VALUE"]["LOCAL"]["COVER"];
			}
		}
		?>
		<style type="text/css">
			.video-settings .adm-input-file-top-shift {
				display: inline-block;
			}

			.video-settings .adm-input-file-control {
				display: inline-block;
			}
		</style>

		<fieldset class="<?= __CLASS__ ?> video-settings">
			<legend><?=Loc::getMessage("INTERVOLGA_COMMON.SERVER_VIDEO")?></legend>
			<label><?=Loc::getMessage("INTERVOLGA_COMMON.VIDEO_CHOOSE_FILE")?><span class="required">*</span>:</label>
			<?=
			\CFileInput::Show($videoFileName, $arVideo,
				array(
					"PATH" => "Y",
					"IMAGE" => "N",
					"MAX_SIZE" => array(
						"W" => \COption::GetOptionString("iblock", "detail_image_size"),
						"H" => \COption::GetOptionString("iblock", "detail_image_size"),
					),
				), array(
					'upload' => true,
					'medialib' => true,
					'file_dialog' => true,
					'cloud' => true,
					'del' => true,
					'description' => $arProperty["WITH_DESCRIPTION"] == "Y" ? array(
							"VALUE" => $value["DESCRIPTION"],
							"NAME" => $strHTMLControlName["DESCRIPTION"],
						) : false,
				)
			);?>
			<? if ($arVideo && (is_string($arVideo) || is_numeric($arVideo))): ?>
				<input type="hidden" name="<?= $oldVideoFileName ?>" value="<?= $arVideo ?>"/>
			<? endif ?>
		</fieldset>

		<fieldset class="<?= __CLASS__ ?> video-settings">
			<label><?=Loc::getMessage("INTERVOLGA_COMMON.VIDEO_UPLOAD_COVER")?></label>
			<?=
			\CFileInput::Show($coverFileName, $arCover,
				array(
					"PATH" => "Y",
					"IMAGE" => "N",
					"MAX_SIZE" => array(
						"W" => \COption::GetOptionString("iblock", "detail_image_size"),
						"H" => \COption::GetOptionString("iblock", "detail_image_size"),
					),
				), array(
					'upload' => true,
					'medialib' => true,
					'file_dialog' => true,
					'cloud' => true,
					'del' => true,
					'description' => $arProperty["WITH_DESCRIPTION"] == "Y" ? array(
							"VALUE" => $value["DESCRIPTION"],
							"NAME" => $strHTMLControlName["DESCRIPTION"],
						) : false,
				)
			);?>
			<? if ($arCover && (is_string($arCover) || is_numeric($arCover))): ?>
				<input type="hidden" name="<?= $oldCoverFileName ?>" value="<?= $arCover ?>"/>
			<? endif ?>
		</fieldset>
	<?
	}

	/**
	 * Проверяет корректность значения свойства. Возвращает массив ошибок.
	 *
	 * @param array $arProperty
	 * @param array $value
	 *
	 * @return array|string[]
	 */
	public function checkFields($arProperty, $value)
	{
		$arErrors = array();
		if (!$value["VALUE"]["LOCAL"]["VIDEO"])
		{
			$arErrors[] = $arProperty["NAME"] . " [" . ($arProperty["CODE"] ? $arProperty["CODE"] : $arProperty["ID"]) . "]: ".Loc::getMessage("INTERVOLGA_COMMON.VIDEO_FILE_NOT_SET");
		}
		elseif (is_array($value["VALUE"]["LOCAL"]["VIDEO"]) && $value["VALUE"]["LOCAL"]["VIDEO"]["error"] && !$value["VALUE"]["LOCAL"]["VIDEO_OLD"])
		{
			$arErrors[] = $arProperty["NAME"] . " [" . ($arProperty["CODE"] ? $arProperty["CODE"] : $arProperty["ID"]) . "]: ".Loc::getMessage("INTERVOLGA_COMMON.VIDEO_FILE_NOT_UPLOADED");
		}

		return $arErrors;
	}

	/**
	 * Изменяет значение свойства перед сохранением.
	 *
	 * @param array $arProperty
	 * @param array $value
	 *
	 * @return array
	 */
	public function onBeforeSave($arProperty, $value)
	{
		foreach (array("VIDEO", "COVER") as $subCode)
		{
			// Если файл был загружен с компьютера
			if (is_array($value["VALUE"]["LOCAL"][$subCode]))
			{
				// Сохранить файл
				if (!$value["VALUE"]["LOCAL"][$subCode]["error"])
				{
					$dir = ($subCode == "VIDEO") ? "iv_video" : "iv_video_cover";
					$value["VALUE"]["LOCAL"][$subCode] = \CFile::SaveFile($value["VALUE"]["LOCAL"][$subCode], $dir);
				}
				elseif ($value["VALUE"]["LOCAL"][$subCode . "_OLD"])
				{
					$value["VALUE"]["LOCAL"][$subCode] = $value["VALUE"]["LOCAL"][$subCode . "_OLD"];
				}
				else
				{
					$value["VALUE"]["LOCAL"][$subCode] = false;
				}
			}
			// Если указан ID файла
			elseif (is_numeric($value["VALUE"]["LOCAL"][$subCode]))
			{
				// Все как надо
			}
			// Если указан путь к файлу на сервере
			elseif (is_string($value["VALUE"]["LOCAL"][$subCode]))
			{
				// От обложки всегда нужен ID файла, поэтому в этом случае ее нужно зарегистрировать в файловой системе
				if ($subCode == "COVER")
				{
					// Сохранить файл в файловой системе
					$arFile = \CFile::MakeFileArray($value["VALUE"]["LOCAL"][$subCode]);
					// Если файл сохранен
					if ($arFile["size"] && $arFile["tmp_name"])
					{
						// Сохранить файл
						$value["VALUE"]["LOCAL"][$subCode] = \CFile::SaveFile($arFile, "iv_video_cover");
					}
				}
			}
			// Если был старый файл и новый ему не равен
			if (is_numeric($value["VALUE"]["LOCAL"][$subCode . "_OLD"]) && $value["VALUE"]["LOCAL"][$subCode] != $value["VALUE"]["LOCAL"][$subCode . "_OLD"])
			{
				\CFile::Delete($value["VALUE"]["LOCAL"][$subCode . "_OLD"]);
			}
			unset($value["VALUE"]["LOCAL"][$subCode . "_OLD"]);
		}

		$value["VALUE"] = parent::arraySift($value["VALUE"], array("LOCAL", "TYPE"));

		return $value;
	}
}
