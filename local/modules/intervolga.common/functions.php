<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

function dump($var, $die = false, $all = false)
{
	global $USER;

	if ($USER->isAdmin() || $all)
	{
		?>
		<style type="text/css">
			pre {
				page-break-inside: avoid;
				font-family: Monaco, Menlo, Consolas, "Courier New", monospace;
				color: #333333;
				display: block;
				padding: 9.5px;
				margin: 0 0 10px;
				font-size: 13px;
				line-height: 20px;
				word-break: break-all;
				word-wrap: break-word;
				white-space: pre;
				white-space: pre-wrap;
				background-color: #f5f5f5;
				border: 1px solid #ccc;
				-webkit-border-radius: 4px;
				-moz-border-radius: 4px;
				border-radius: 4px;
			}

			.muted {
				color: #999999;
			}

			.text-warning {
				color: #c09853;
			}

			.text-error {
				color: #b94a48;
			}

			.text-info {
				color: #3a87ad;
			}

			.text-success {
				color: #468847;
			}
		</style>
		<?
		ob_start();
		var_dump($var);
		$vd = ob_get_clean();

		// Double left margins
		$vd = str_replace('  ', '    ', $vd);

		// Colorize booleans
		$vd = str_replace('bool(true)', 'bool <span class="text-warning">true</span>', $vd);
		$vd = str_replace('bool(false)', 'bool <span class="text-warning">false</span>', $vd);

		// Colorize NULL's
		$vd = str_replace('NULL', '<span class="muted">NULL</span>', $vd);

		$vd = preg_replace(array(
			'/=>[ \n\r]*/', // Remove extra returns
			'/\[(\S*?)\]/', // Colorize array keys
			'/int\((\d*)\)/', // Colorize int values
			'/float\((\d*)\)/', // Colorize float values
			'/( "\S*")/', // Colorize strings
			'/(\(\d*\))/' // Make sizes inclined
		), array(
			' => ',
			'[<span class="text-info">${1}</span>]',
			'int <span class="text-error">${1}</span>',
			'float <span class="text-error">${1}</span>',
			'<span class="text-success">${1}</span>',
			'<i>${1}</i>'
		), $vd);
		?>
		<pre><?= $vd ?></pre><?
	}

	if ($die) die();
}

/**
 * @deprecated расширенный функционал доступен через resizeImageIV()
 *
 * @param $img
 * @param $size
 * @param $resizeType
 * @param bool $bInitSizes
 * @param bool $arFilters
 * @param bool $bImmediate
 * @param bool $jpgQuality
 * @return mixed
 * @throws Exception
 */
function resizeImageGetIV($img, $size, $resizeType, $bInitSizes = true, $arFilters = false, $bImmediate = false,
                          $jpgQuality = false)
{
	if (is_array($img) && isset($img['ID']) && isset($img['SRC']) && isset($img['WIDTH']) && isset($img['HEIGHT']))
	{ // assume array returned by CFile::GetByID
		$img['width'] = $img['WIDTH'];
		$img['height'] = $img['HEIGHT'];
	}
	elseif (is_int($img) || is_string($img) && $img === strval(intval($img)))
	{ // assume ID from b_file table
		$img = CFile::GetFileArray(intval($img));
		$img['width'] = $img['WIDTH'];
		$img['height'] = $img['HEIGHT'];
	}
	else
	{
		throw new Exception("Unknown argument type " . $img);
	}

	if (!is_array($size))
	{
		throw new Exception("Size should be an array with width or height key or both");
	}

	if (!$size['height'] && $size['width'])
	{
		$size['height'] = round(1.0*$img['height']*$size['width']/$img['width']);
	}
	elseif ($size['height'] && !$size['width'])
	{
		$size['width'] = round(1.0*$img['width']*$size['height']/$img['height']);
	}

	return CFile::ResizeImageGet($img, array('width' => $size['width'], 'height' => $size['height']), $resizeType,
		$bInitSizes, $arFilters, $bImmediate, $jpgQuality);
}

/**
 * @param array|int $src - Идентификатор файла из таблицы b_file или массив описания файла
 * @param string|array $params - строковый ключ массива параметров или массив параметров со следующими возможными ключами:
 *                              int height - высота итогового изображения
 *                              int width - ирина итогового изображения
 *                              const resize_type - тип масштабирования
 *                                  BX_RESIZE_IMAGE_EXACT - масштабирует c сохранением пропорций, обрезая лишнее
 *                                  BX_RESIZE_IMAGE_PROPORTIONAL - масштабирует с сохранением пропорций
 *                                  BX_RESIZE_IMAGE_PROPORTIONAL_ALT - масштабирует с сохранением пропорций за ширину
 *                                                                     при этом принимается максимальное значение из высоты/ширины,
 *                                                                     улучшенная обработка вертикальных картинок
 *                              array resize_filters - массив массивов для постобработки картинки с помощью фильтров
 *                                   bool resize_immediate - флаг передается в обработчик события OnBeforeResizeImage,
 *                                                 по смыслу означает масштабирование непосредственно при вызове метода
 *                              bool|int resize_quality - число, устанавливающее в процентах качество JPG при масштабировании
 *                              bool permit_extensions - разрешить добавление пустых пикселей, если изображение меньше нужных размеров
 *                              array align - выравнивание изображения, при добавлении пустых пикселей,
 *                                            имеет вид array('h' => 'center', 'v' => 'center')
 *                                            варианты h(выравнивание по горизонтали): left, center, right
 *                                            варианты v(выравнивание по вертикали): top, center, bottom
 *                              array background - цвет пикселей для заполнения пустых пикселей в виде
 *                                                 array("r" => 255, "g" => 255, "b" => 255, "a" => 127)
 *                              array filters - массив фильтров, возможны ключи:
 *                                              sharpness - резкость, параметр level - уровень резкости
 * 												gd_imagefilter - функция imagefilter из библиотеки Graphics Draw,
 *                                                               ITERATIONS - количество итераций применения функции к изображению,
 *                                                               PARAMS - массив параметров передаваемых функции
 *                                              watermark - водяной знак, принимает следующие параметры
 *                                                          img - id или массив файла водяного знака
 *                                                          left - отступ, в %, от левого края картинки до граница области для вписывания знака
 *                                                          top - отступ, в %, от верхнего края картинки до граница области для вписывания знака
 *                                                          width - ширина области для вписывания знака в % от картинки
 *                                                          height - высота области для вписывания знака в % от картинки
 *                                                          transparency - прозрачность знака в %
 *                                                          hAlign - выравнивание знака в области вписывания (left, center, right)
 *                                                          vAlign - выравнивание знака в области вписывания (top, center, bottom)
 * 								bool use_empty - если true, то скрипт пытается найти файл заглушку
 *                              string convert - mime-тип в котором сохранить результат
 * @return array|bool
 * @throws \Bitrix\Main\ArgumentTypeException
 * @throws \Bitrix\Main\ArgumentNullException
 */
function resizeImageIV($src, $params)
{
    if (is_string($params)) {
        $params = \Intervolga\Common\TemplateParameters::getResizeParam($params);
    }

    $width = null;
    $height = null;
    if (is_array($src)
        && isset($src['ID'])
        && isset($src['SRC'])
        && isset($src['WIDTH'])
        && isset($src['HEIGHT'])
    ) { // assume array returned by CFile::GetByID
        $width = $src['WIDTH'];
        $height = $src['HEIGHT'];
    } elseif (is_int($src) || is_string($src) && $src === strval(intval($src))) { // assume ID from b_file table
        $src = CFile::GetFileArray(intval($src));
        $width = $src['WIDTH'];
        $height = $src['HEIGHT'];
    } elseif (($src === false || is_null($src))
        && file_exists($_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/empty_image.png')
        && $params['use_empty'] === true
    ) {
        $emptyImgId = false;
        if ($emptyImg = \Bitrix\Main\FileTable::getList(array('filter' => array('=DESCRIPTION' => 'resizeImageIVEmptyImage')))->fetch()) {
            if ($emptyImg['DESCRIPTION'] == 'resizeImageIVEmptyImage') {
                $emptyImgId = $emptyImg['ID'];
                if (is_object($USER) && $USER->CanDoOperation('cache_control')) {
                    if (isset($_GET["clear_cache_session"])) {
                        if (strtoupper($_GET["clear_cache_session"]) == "Y") {
                            $_SESSION["SESS_CLEAR_CACHE"] = "Y";
                        } elseif (strlen($_GET["clear_cache_session"]) > 0) {
                            unset($_SESSION["SESS_CLEAR_CACHE"]);
                        }
                    }

                    if (isset($_GET["clear_cache"]) && strtoupper($_GET["clear_cache"]) == "Y" && $GLOBALS['empty_image_reloaded'] !== true) {
                        $emptyImgId = \Intervolga\Common\Main\File::update(
                            $emptyImg['ID'],
                            array(
                                'SRC' => $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/empty_image.png'
                            )
                        );
                        $GLOBALS['empty_image_reloaded'] = true;
                    }
                }
            }
        }

        if (!$emptyImgId) {
            $emptyImgId = \Intervolga\Common\Main\File::add(array(
                'SRC' => $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/empty_image.png',
                'DESCRIPTION' => 'resizeImageIVEmptyImage',
                'FOLDER' => 'empty_image'
            ));
            $GLOBALS['empty_image_reloaded'] = true;
        }
        if ($emptyImgId) {
            $src = CFile::GetFileArray($emptyImgId);
            $width = $src['WIDTH'];
            $height = $src['HEIGHT'];
        } else {
            throw new \Bitrix\Main\IO\FileNotFoundException("Empty image not found in database");
        }
    } else {
        throw new \Bitrix\Main\ArgumentTypeException("Unknown argument type " . $src);
    }

    $resizeType = ($params['resize_type'] && in_array(
            intval($params['resize_type']),
            array(
                BX_RESIZE_IMAGE_PROPORTIONAL,
                BX_RESIZE_IMAGE_EXACT,
                BX_RESIZE_IMAGE_PROPORTIONAL_ALT
            )
        ))
        ? intval($params['resize_type'])
        : BX_RESIZE_IMAGE_EXACT;

    if (!$params['height'] && !$params['width']) {
        throw new \Bitrix\Main\ArgumentNullException("Not specified nor height nor width");
    } elseif (!$params['height'] && $params['width']) {
        $params['height'] = $resizeType == BX_RESIZE_IMAGE_PROPORTIONAL ? round(1.0 * $height * $params['width'] / $width) : $height;
    } elseif ($params['height'] && !$params['width']) {
        $params['width'] = $resizeType == BX_RESIZE_IMAGE_PROPORTIONAL ? round(1.0 * $width * $params['height'] / $height) : $width;
    }

    $bInitSizes = true;
    $arFilters = $params['resize_filters'] ?: false;
    $bImmediate = $params['resize_immediate'] ?: false;
    $jpgQuality = is_numeric($params['resize_quality'])
        ? intval($params['resize_quality'])
        : intval(\Bitrix\Main\Config\Option::get('main', 'image_resize_quality', '95'));
    if ($jpgQuality <= 0 || $jpgQuality > 100) {
        $jpgQuality = 95;
    }

    $res = CFile::ResizeImageGet(
        $src['ID'],
        array(
            'width' => $params['width'],
            'height' => $params['height']
        ),
        $resizeType,
        $bInitSizes,
        $arFilters,
        $bImmediate,
        $jpgQuality
    );

    if (!$res) {
        return false;
    }

    if($params['permit_extensions'] !== true) {
        $params['width'] = $res['width'];
        $params['height'] = $res['height'];
    }

    $params['filters'] = is_array($params['filters']) ? $params['filters'] : array();

    if (($params['permit_extensions'] === true
            && ($res['width'] < $params['width'] || $res['height'] < $params['height']))
        || !empty($params['filters'])
    ) {
        $color = array(
            'r' => isset($params['background']['r']) ? $params['background']['r'] : 255,
            'g' => isset($params['background']['g']) ? $params['background']['g'] : 255,
            'b' => isset($params['background']['b']) ? $params['background']['b'] : 255,
            'a' => isset($params['background']['a']) ? $params['background']['a'] : 127
        );

        $uploadDirName = COption::GetOptionString("main", "upload_dir", "upload");
        $cacheImageFile = "/" . $uploadDirName . "/resize_cache/" . $src["SUBDIR"] . "/";
        CheckDirPath($_SERVER['DOCUMENT_ROOT'] . $cacheImageFile);

        $arr = explode('.', array_pop(explode('/', $res['src'])));
        if ($params['permit_extensions'] === true) {
            $arr[count($arr) - 2] .= '_extensions';
        }
        $arr[count($arr) - 2] .= '_' . $res['width'] . '_' . $res['height']
            . '_' . $params['width'] . '_' . $params['height'];
        if ($params['permit_extensions'] === true) {
            $arr[count($arr) - 2] .= '_' . implode('_', array_values($color));
        }
        if (!empty($params)) {
            $arr[count($arr) - 2] .= md5(serialize($params));
        }
        switch ($params['convert']) {
            case 'image/jpeg':
                $arr[count($arr) - 1] = 'jpg';
                break;
            case 'image/png':
                $arr[count($arr) - 1] = 'png';
                break;
            case 'image/gif':
                $arr[count($arr) - 1] = 'gif';
                break;
            default:
                $params['convert'] = false;
        }

        $srcPath = $_SERVER['DOCUMENT_ROOT'] . $cacheImageFile . implode('.', $arr);
        $itemRes = null;

        if (!file_exists($srcPath) || ($_REQUEST['clear_cache'] == 'Y' && $GLOBALS['USER']->IsAdmin())) {
            $tableau = getimagesize($_SERVER['DOCUMENT_ROOT'] . $res['src']);

            $src_im = null;
            $dst_im = imagecreatetruecolor($params['width'], $params['height']);
            switch ($tableau["mime"]) {
                case 'image/jpeg':
                    $src_im = imagecreatefromjpeg($_SERVER['DOCUMENT_ROOT'] . $res['src']);
                    break;
                case 'image/png':
                    $src_im = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'] . $res['src']);
                    break;
                case 'image/gif':
                    $src_im = imagecreatefromgif($_SERVER['DOCUMENT_ROOT'] . $res['src']);
                    break;
            }

            switch ($params['convert'] ?: $tableau["mime"]) {
                case 'image/jpeg':
                    $col = imagecolorallocate($dst_im, $color['r'], $color['g'], $color['b']);
                    break;
                case 'image/png':
                    imagesavealpha($dst_im, true);
                    $col = imagecolorallocatealpha($dst_im, $color['r'], $color['g'], $color['b'], $color['a']);
                    break;
                case 'image/gif':
                    imagesavealpha($dst_im, true);
                    $col = imagecolorallocatealpha($dst_im, $color['r'], $color['g'], $color['b'], $color['a']);
                    break;
            }

            if ($src_im) {
                imagefill($dst_im, 0, 0, $col);

                $x = round(($params['width'] - $res["width"]) / 2);
                switch ($params['align']['h']) {
                    case 'left':
                        $x = 0;
                        break;
                    case 'right':
                        $x = $params['width'] - $res["width"];
                        break;
                }

                $y = round(($params['height'] - $res["height"]) / 2);
                switch ($params['align']['v']) {
                    case 'top':
                        $y = 0;
                        break;
                    case 'bottom':
                        $y = $params['height'] - $res["height"];
                        break;
                }

                imagecopy(
                    $dst_im,
                    $src_im,
                    $x,
                    $y,
                    0,
                    0,
                    $res["width"],
                    $res["height"]
                );

                if (!empty($params['filters'])) {
                    foreach ($params['filters'] as $filterName => $filterParams) {
                        switch ($filterName) {
                            case 'sharpness':
                                $k = floatval($filterParams['level'] ?: 1);
                                imageconvolution(
                                    $dst_im,
                                    array(
                                        array(-1 * $k, -1 * $k, -1 * $k),
                                        array(-1 * $k, 8 * $k + 1, -1 * $k),
                                        array(-1 * $k, -1 * $k, -1 * $k)
                                    ),
                                    1,
                                    0.001
                                );
                                break;
                            case 'gd_imagefilter':
                                $countIteration = intval($filterParams['ITERATIONS']);
                                if ($countIteration <= 0) {
                                    $countIteration = 1;
                                }
                                for ($i = 0; $i < $countIteration; $i++) {
                                    call_user_func_array(
                                        'imagefilter',
                                        array_merge(array($dst_im), $filterParams['PARAMS'])
                                    );
                                }
                                break;
                            case 'watermark':
                                $realSettings = array(
                                    'left' => $res["width"] * ($filterParams['left'] ?: 0) / 100,
                                    'top' => $res["height"] * ($filterParams['top'] ?: 0) / 100,
                                    'width' => $res["width"] * ($filterParams['width'] ?: 100) / 100,
                                    'height' => $res["height"] * ($filterParams['height'] ?: 100) / 100,
                                );
                                $realSettings['img'] = resizeImageIV(
                                    $filterParams['img'],
                                    array(
                                        'width' => $realSettings['width'],
                                        'height' => $realSettings['height'],
                                        "permit_extensions" => true,
                                        "resize_type" => BX_RESIZE_IMAGE_PROPORTIONAL,
                                        "align" => array("h" => $filterParams['hAlign'] ?: 'center', "v" => $filterParams['vAlign'] ?: 'center'),
                                        "convert" => "image/png"
                                    )
                                );
                                $water_im = null;
                                if ($realSettings['img']) {
                                    $water_im = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'] . $realSettings['img']['SRC']);
                                    imagesavealpha($water_im, true);
                                }
                                if ($water_im) {
                                    $cut = imagecreatetruecolor($realSettings["width"], $realSettings["height"]);
                                    imagecopy(
                                        $cut,
                                        $dst_im,
                                        0,
                                        0,
                                        round(($params['width'] - $res["width"]) / 2) + $realSettings['left'],
                                        round(($params['height'] - $res["height"]) / 2) + $realSettings['top'],
                                        $realSettings["width"],
                                        $realSettings["height"]
                                    );
                                    imagecopy(
                                        $cut,
                                        $water_im,
                                        0,
                                        0,
                                        0,
                                        0,
                                        $realSettings["width"],
                                        $realSettings["height"]
                                    );
                                    imagecopymerge(
                                        $dst_im,
                                        $cut,
                                        round(($params['width'] - $res["width"]) / 2) + $realSettings['left'],
                                        round(($params['height'] - $res["height"]) / 2) + $realSettings['top'],
                                        0,
                                        0,
                                        $realSettings["width"],
                                        $realSettings["height"],
                                        isset($filterParams['transparency']) ? $filterParams['transparency'] : 100
                                    );
                                }
                                break;
                        }
                    }
                }

                switch ($params['convert'] ?: $tableau["mime"]) {
                    case 'image/jpeg':
                        imagejpeg($dst_im, $srcPath, $jpgQuality);
                        break;
                    case 'image/png':
                        imagepng($dst_im, $srcPath);
                        break;
                    case 'image/gif':
                        imagegif($dst_im, $srcPath);
                        break;
                }
            }
        }

        $res = array(
            "src" => str_replace($_SERVER['DOCUMENT_ROOT'], '', $srcPath),
            "width" => $params['width'],
            "height" => $params['height'],
            "size" => filesize($srcPath)
        );
    }

    if ($src["WIDTH"]) {
        $src["~WIDTH"] = $src["WIDTH"];
    }
    $src["WIDTH"] = $res['width'];
    if ($src["HEIGHT"]) {
        $src["~HEIGHT"] = $src["HEIGHT"];
    }
    $src["HEIGHT"] = $res['height'];
    if ($src["SRC"]) {
        $src["~SRC"] = $src["SRC"];
    }
    $src["SRC"] = $res['src'];
    if ($src["FILE_SIZE"]) {
        $src["~FILE_SIZE"] = $src["FILE_SIZE"];
    }
    $src["FILE_SIZE"] = $res['size'];

    return $src;
}

function parseFileInputFromHtml($html)
{
	$result = '';

	if (preg_match('(<input[^>]*?type="file"[^>]*?>)', $html, $matches))
	{
		$result .= '<span class="btn btn-default"><span class="choose" data-default="' . Loc::getMessage('CHOOSE_FILE') . '">' . Loc::getMessage('CHOOSE_FILE') . '</span>';

		$matches[0] = str_replace('/>', '>', $matches[0]);
		$result .= str_replace('>', ' onchange="fileChanged(this)">', $matches[0]);

		$result .= '</span>';
	}

	if (preg_match('(<img[^>]*?>)', $html, $matches))
	{
		$result .= $matches[0];
	}

	if (preg_match('(<input[^>]*?type="checkbox"[^>]*?>)', $html, $matches))
	{
		$result .= '<label class="checkbox">' . $matches[0] . ' ' . Loc::getMessage('REMOVE_FILE') . '</label>';
	}

	return $result;
}

if (!function_exists('isEnglishSymbol'))
{
	/**
	 * Check that symbol is a part of english aplhabet
	 *
	 * @param string $symbol symbol to check
	 *
	 * @return bool
	 */
	function isEnglishSymbol($symbol)
	{
		return preg_match("/[a-z]/i", $symbol);
	}
}

if (!function_exists('isRussianSymbol'))
{
	/**
	 * Check that symbol is a part of russian aplhabet
	 *
	 * @param string $symbol symbol to check
	 *
	 * @return bool
	 */
	function isRussianSymbol($symbol)
	{
		return preg_match("/[а-яё]/i", $symbol);
	}
}

if (!function_exists("cmpBySort"))
{
	/**
	 * Resort array according to SORT value
	 *
	 * @param $a
	 * @param $b
	 *
	 * @return int - difference between two SORT keys
	 */
	function cmpBySort($a, $b)
	{
		$a["SORT"] = ($a["SORT"] == null ? 500 : intval($a["SORT"]));
		$b["SORT"] = ($b["SORT"] == null ? 500 : intval($b["SORT"]));

		return $a["SORT"] - $b["SORT"];
	}
}

?>
