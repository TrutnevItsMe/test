<?php
namespace Intervolga\Common\Iblock;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Web\Uri;

Loc::loadMessages(__FILE__);

/**
 * Класс для загрузки, хранения и воспроизведения видео с YouTube.
 */
class YoutubeVideo extends VideoPlayer
{
	/**
	 * Возвращает ссылку для вставки ролика через iframe.
	 *
	 * @param string $code код видеоролика.
	 * @param bool $needAutoPlay флаг - нужно ли начинать проигрывать видео
	 *                                  сразу после загрузки проигрывателя.
	 *
	 * @return string
	 */
	public static function getEmbedUrl($code, $needAutoPlay = true)
	{
		$embedUrl = "//www.youtube.com/embed/" . $code;
		if($needAutoPlay)
		{
			$embedUrl .= '?autoplay=1';
		}
		return $embedUrl;
	}

	/**
	 * Возвращает человекопонятное описание типа хранимого видео.
	 *
	 * @return string
	 */
	public static function getName()
	{
		return Loc::getMessage("INTERVOLGA_COMMON.VIDEO_YOUTUBE");
	}

	/**
	 * Удаляет все данные, связанные с видео.
	 *
	 * @param array $arProperty
	 * @param array $value
	 */
	public static function delete($arProperty, $value)
	{
		if ($value["VALUE"] && is_array($value["VALUE"]) && $value["VALUE"]["YOUTUBE"])
		{
			if ($value["VALUE"]["YOUTUBE"]["COVER"])
			{
				\CFile::Delete($value["VALUE"]["YOUTUBE"]["COVER"]);
			}
		}
	}

	/**
	 * Возвращает массив для настроек компонента вывода видео.
	 *
	 * @return bool|array массив настроек или FALSE
	 */
	public static function getComponentParams()
	{
		return array(
			"YOUTUBE_URL" => Array(
				"NAME" => Loc::getMessage("INTERVOLGA_COMMON.VIDEO_URL"),
				"TYPE" => "TEXT",
			),
			"YOUTUBE_COVER" => Array(
				"NAME" => Loc::getMessage("INTERVOLGA_COMMON.VIDEO_COVER"),
				"TYPE" => "TEXT",
			),
		);
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
		$arYouTubeCover = false;
		$arYouTubeValue = array();
		if (is_array($value["VALUE"]))
		{
			$arYouTubeValue = $value["VALUE"]["YOUTUBE"];
			if ($value["VALUE"]["YOUTUBE"]["COVER"])
			{
				$arYouTubeCover = \CFile::ResizeImageGet($value["VALUE"]["YOUTUBE"]["COVER"], array("width" => 100, "height" => 100), BX_RESIZE_IMAGE_PROPORTIONAL, true);
			}
		}
		?>
		<fieldset class="<?=__CLASS__?> video-settings">
			<legend>YouTube</legend>
			<label
				for="<?=$strHTMLControlName["VALUE"]?>[YOUTUBE][URL]"><?=Loc::getMessage("INTERVOLGA_COMMON.VIDEO_URL_2")?>
				<span
					class="required">*</span>:</label>
			<input type="text" name="<?=$strHTMLControlName["VALUE"]?>[YOUTUBE][URL]"
			       id="<?=$strHTMLControlName["VALUE"]?>[YOUTUBE][URL]" value="<?=$arYouTubeValue["URL"]?>"/>
		</fieldset>
		<? if ($arYouTubeCover): ?>
		<fieldset class="<?=__CLASS__?> video-settings">
			<label><?=Loc::getMessage("INTERVOLGA_COMMON.VIDEO_COVER_2")?></label>
			<input type="hidden" name="<?=$strHTMLControlName["VALUE"]?>[YOUTUBE][COVER]"
			       value="<?=$arYouTubeValue["COVER"]?>"/>
			<img src="<?=$arYouTubeCover["src"]?>" alt="" height="<?=$arYouTubeCover["height"]?>"
			     width="<?=$arYouTubeCover["width"]?>"/>
		</fieldset>
		<fieldset class="<?=__CLASS__?> video-settings">
			<label></label>
			<label class="checkbox"><input type="checkbox"
			                               name="<?=$strHTMLControlName["VALUE"]?>[YOUTUBE][COVER_DEL]" value="Y"/>
				<?=Loc::getMessage("INTERVOLGA_COMMON.VIDEO_RELOAD")?></label>
		</fieldset>
	<? endif ?>
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
		if (!$value["VALUE"]["YOUTUBE"]["URL"])
		{
			$arErrors[] = $arProperty["NAME"] . " [" . ($arProperty["CODE"] ? $arProperty["CODE"] : $arProperty["ID"]) . "]: " . Loc::getMessage("INTERVOLGA_COMMON.VIDEO_URL_NOT_SET");
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
		$code = static::getYouTubeCode($value["VALUE"]["YOUTUBE"]["URL"]);
		if ($value["VALUE"]["YOUTUBE"]["COVER_DEL"] == "Y" && $value["VALUE"]["YOUTUBE"]["COVER"])
		{
			// Удалить обложку
			\CFile::Delete($value["VALUE"]["YOUTUBE"]["COVER"]);
			$value["VALUE"]["YOUTUBE"]["COVER"] = false;
			unset($value["VALUE"]["YOUTUBE"]["COVER_DEL"]);
		}
		if (!$value["VALUE"]["YOUTUBE"]["COVER"])
		{
			$value["VALUE"]["YOUTUBE"]["COVER"] = static::downloadYouTubePreviewImage($code);
		}
		$value["VALUE"] = parent::arraySift($value["VALUE"], array("YOUTUBE", "TYPE"));

		return $value;
	}

	/**
	 * Разбирает ссылку на Youtube и возвращает код видеоролика.
	 *
	 * Ссылка может иметь вид:
	 * http(s)://www.youtube.com/watch?v=XXXXXXXXXXX
	 * http(s)://youtu.be/XXXXXXXXXXX
	 * http(s)://www.youtube.com/embed/XXXXXXXXXXX
	 *
	 * //www.youtube.com/embed/XXXXXXXXXXX
	 * www.youtube.com/embed/XXXXXXXXXXX
	 * youtube.com/embed/XXXXXXXXXXX
	 *
	 * и др.
	 *
	 * @param string $url ссылка на Youtube.
	 *
	 * @return string|bool код видеоролика
	 *                     или
	 *                     false, если не удалось получить код из ссылки.
	 */
	public static function getYouTubeCode($url)
	{
		// Добавляем протокол, если не задан
		if (substr($url, 0, strlen('//')) === '//')
		{
			$url = "https:" . $url;
		}
		elseif (substr($url, 0, strlen('http://')) !== 'http://'
			&& substr($url, 0, strlen('https://')) !== 'https://'
		)
		{
			$url = "https://" . $url;
		}

		if (!filter_var($url, FILTER_VALIDATE_URL) === false)
		{
			$uri = new Uri($url);
			$path = $uri->getPath();
			$host = $uri->getHost();
			$query = $uri->getQuery();

			// Ссылка с кодом в GET-параметре (длинная ссылка)
			if (!empty($query))
			{
				$arQuery = array();
				parse_str($query, $arQuery);
				if ($arQuery['v'])
				{
					return $arQuery['v'];
				}
			}
			// Ссылка с кодом после / (короткая ссылка или ссылка embed)
			elseif (!empty($host)
				&& !empty($path)
				&& (stripos($host, 'youtube.com') !== false || stripos($host, 'youtu.be') !== false)
			)
			{
				$path = str_replace("/embed/", "", $path);
				$path = str_replace("/", "", $path);

				return $path;
			}
		}

		return false;
	}

	/**
	 * Загружает и регистрирует обложку видеоролика с наибольшим разрешением.
	 *
	 * @param string $code код ролика на YouTube
	 *
	 * @return int
	 */
	public static function downloadYouTubePreviewImage($code)
	{
		if ($file = self::getYouTubePreviewImageAsFile($code))
		{
			$file["description"] = $code;
			$fid = \CFile::SaveFile($file, "iv_video_cover");

			return $fid;
		}

		return 0;
	}

	/**
	 * Возвращает массив файла для обложки видеоролика с Youtube.
	 *
	 * @param $code
	 *
	 * @return array|bool|null
	 */
	public static function getYouTubePreviewImageAsFile($code)
	{
		$arPossibleSizes = array("maxres", "sd", "hq", "mq", "");
		foreach ($arPossibleSizes as $possibleSize)
		{
			$arFile = \CFile::MakeFileArray("http://img.youtube.com/vi/" . $code . "/" . $possibleSize . "default.jpg");
			if ($arFile["size"] && $arFile["tmp_name"])
			{
				return $arFile;
			}
		}

		return array();
	}
}