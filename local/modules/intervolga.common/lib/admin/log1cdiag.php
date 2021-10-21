<?namespace Intervolga\Common\Admin;

use Bitrix\Main\Type\DateTime;
use Intervolga\Common\Tools\Orm\Log1cTable;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;
use Bitrix\Main\IO\File;

Loc::loadMessages(__FILE__);

class Log1CDiag
{
	protected static $path = '/bitrix/admin/intervolga.common_log1c.php';

	/**
	 * @param $findStart
	 * @param $findEnd
	 * @return array
	 */
	public static function getData($findStart, $findEnd)
	{
		$result = array();

		if ($findStart && $findEnd)
		{
			$rsData = Log1cTable::getList(
				array(
					'filter' => array(
						'>DATE_CREATE' => $findStart,
						'<=DATE_CREATE' => $findEnd,
					),
				)
			);

			while ($rec = $rsData->fetch())
			{
				if (strlen($rec['DATE_END_MS']) && strlen($rec['DATE_START_MS']))
				{
					$rec['START_MS'] = static::getTimestampByDate($rec['DATE_CREATE'], $rec['DATE_START_MS']);
					$rec['END_MS'] = static::getTimestampByDate($rec['DATE_END'], $rec['DATE_END_MS']);
					$rec['DIFF_MS'] = static::calcDiffMs($rec['START_MS'], $rec['END_MS']);
				}

				$rec['IS_COMPLETE'] = $rec['DATE_END'] ? true : false;
				$result[$rec['SESSID']][] = $rec;
			}
		}

		return $result;
	}

	/**
	 * Calculate diff ms
	 *
	 * @param $startMs
	 * @param $endMs
	 * @return int
	 */
	public static function calcDiffMs($startMs, $endMs)
	{
		return abs($endMs - $startMs) / 1000;
	}

	/**
	 * @param string $sessionId
	 * @param array $items
	 * @return string
	 */
	public static function getSessionInterval($sessionId, array $items)
	{
		$stack = [];
		$nameParts = [];
		$type = '';
		foreach ($items as $item)
		{
			$type = $item['TYPE'];
			if ($stack[count($stack) - 1]['MODE'] == $item['MODE'])
			{
				$stack[count($stack) - 1]['COUNT']++;
				continue;
			}
			$stack[] = array(
				'MODE' => $item['MODE'],
				'COUNT' => 1,
			);
		}
		foreach ($stack as $stackItem)
		{
			if ($stackItem['COUNT'] > 1)
			{
				$nameParts[] = Loc::getMessage('INTERVOLGA_COMMON.GANTT_GROUP_CHUNK', array('#MODE#' => $stackItem['MODE'], '#COUNT#' => $stackItem['COUNT']));
			}
			else
			{
				$nameParts[] = $stackItem['MODE'];
			}
		}

		return $type. ':' . implode(',', $nameParts);
	}

	/**
	 * @param array $data
	 * @return array
	 */
	public static function prepareGroups($data)
	{
		$result = array();

		if ($data)
		{
			$ind = 1;
			foreach ($data as $idSession => $items)
			{
				$result[] = array(
					'id' => $idSession,
					'content' => static::getContentGroup($idSession, $items),
					'title' => Loc::getMessage('INTERVOLGA_COMMON.GANTT_GROUP_TITLE', array('#ID#' => $idSession)),
					'value' => $ind,
				);
				$ind++;
			}
		}

		return $result;
	}

	/**
	 * @param string $idSession
	 * @param array $items
	 * @return string
	 */
	public static function getContentGroup($idSession, array $items)
	{
		return Loc::getMessage('INTERVOLGA_COMMON.GANTT_GROUP_CONTENT', array('#ID#' => $idSession, '#TEXT#' => static::getSessionInterval($idSession, $items)));
	}

	/**
	 * @param array $data
	 * @return array
	 */
	public static function prepareItems($data)
	{
		$result = array();

		if ($data)
		{
			foreach ($data as $idSession => $items)
			{
				foreach ($items as $arData)
				{
					$result[] = array(
						'id' => $arData['ID'],
						'group' => $idSession,
						'content' => static::getContent($arData),
						'title' => static::getTitleItem($arData),
						'style' => static::getStyle($arData),
						'start' => $arData['START_MS'],
						'end' => $arData['IS_COMPLETE'] ? $arData['END_MS'] : static::getTimestamp(time()),
						'type' => 'background',
						'className' => 'vis-element',
					);
				}
			}
		}

		return $result;
	}

	/**
	 * @param int $ms
	 * @return int
	 */
	protected static function getTimestamp($ms)
	{
		return $ms * 1000;
	}

	/**
	 * @param string $date
	 * @param int $ms
	 * @return int unix time
	 */
	protected static function getTimestampByDate($date, $ms)
	{
		$obDate = new DateTime($date);
		return static::getTimestamp($obDate->getTimestamp()) + $ms;
	}

	/**
	 * @param array $data
	 * @return string
	 */
	protected static function getContent($data)
	{
		$filePath = static::getPathFile($data['ID']);
		if (strlen($data['FILE_NAME']))
		{
			return Loc::getMessage('INTERVOLGA_COMMON.GANTT_ITEM_TITLE_FILE', array(
				'#TYPE#' => $data['TYPE'],
				'#MODE#' => $data['MODE'],
				'#ID#' => $data['ID'],
				'#HREF#' => $filePath,
				'#FILE_NAME#' => static::getCuteFileName($data['FILE_NAME']),
			));
		}
		else
		{
			return Loc::getMessage('INTERVOLGA_COMMON.GANTT_ITEM_TITLE', array(
				'#TYPE#' => $data['TYPE'],
				'#MODE#' => $data['MODE'],
				'#ID#' => $data['ID'],
				'#HREF#' => $filePath,
			));
		}
	}

	protected static function getCuteFileName($fileName)
	{
		$pos = strpos($fileName, '___');
		if ($pos !== false)
		{
			return substr($fileName, 0, $pos) . '...xml';
		}
		return $fileName;
	}

	/**
	 * @param int $id
	 * @return string
	 */
	protected static function getPathFile($id)
	{
		return static::$path . "?find=$id&find_type=ID";
	}

	/**
	 * @param array $data
	 * @return string
	 */
	protected static function getTitleItem($data)
	{
		$response = $data['RESPONSE'] ? : 'processing';
		$diffMs = $data['DIFF_MS'] ? : 'false';
		$filePath = static::getPathFile($data['ID']);

		return Loc::getMessage(
			'INTERVOLGA_COMMON.TOOLTIP_TEXT',
			array(
				'#TYPE#' => $data['TYPE'],
				'#MODE#' => $data['MODE'],
				'#ID#' => $data['ID'],
				'#HREF#' => $filePath,
				'#FILE#' => static::getViewFilePath($data['COPY_FILE']),
				'#FILE_SIZE#' => \CFile::FormatSize($data['FILE_SIZE']),
				'#TIME#' => $diffMs,
				'#IS_HTTP#' => static::isHttp($data['AUTH_PASSWORD']),
				'#FILE_LOG#' => static::getViewFilePath($data['RESPONSE_FILE']),
				'#RESPONSE#' => nl2br($response),
			)
		);
	}

	/**
	 * @param $auth
	 * @return string
	 */
	protected static function isHttp($auth)
	{
		return $auth ? 'Да' : '<b>Нет</b>' ;
	}

	/**
	 * @param string $color
	 * @param string $background
	 * @return string
	 */
	protected static function getColor($color, $background)
	{
		return "color: $color; background-color: $background;";
	}

	/**
	 * @param array $data
	 * @return string
	 */
	protected static function getStyle($data)
	{
		$status = static::getColor('black', '#7fc7ff');

		$response = trim(str_replace("<br />\n", '', $data['RESPONSE']));

		$explode = explode("\n", $response);
		$explode[0] = trim($explode[0]);
		if ($explode[0] == 'failure')
		{
			$status = static::getColor('black', '#ff6347');
		}
		elseif ($explode[0] == 'success')
		{
			$status = static::getColor('black', '#00ff00');
		}

		if (strpos($data['RESPONSE'], 'MySQL Query Error') !== false || strpos($data['RESPONSE'], 'Fatal error') !== false || strpos($data['RESPONSE'], 'Parse error') !== false)
		{
			$status = static::getColor('black', '#ff6347');
		}

		if (!$data['IS_COMPLETE'])
		{
			$status = static::getColor('black', '#ffff00');
		}

		return $status;
	}

	/**
	 * @param $filePath
	 * @return string
	 */
	public static function getViewFilePath($filePath)
	{
		$link = '';
		if ($filePath)
		{
			$file = new File($filePath);
			if ($file->isExists())
			{
				$url = '/bitrix/admin/fileman_file_edit.php?full_src=Y&site=s1&lang=ru&path=';
				$root = Application::getDocumentRoot();
				$filePath = str_replace($root, '', $filePath);
				$link = static::getViewLink($url . urlencode($filePath), $file->getName(), $file->getName());
			}
		}

		return $link;
	}

	/**
	 * @param $url
	 * @param $text
	 * @param $title
	 * @return string
	 */
	public static function getViewLink($url, $text, $title)
	{
		$titleAttr = '';
		if ($title)
		{
			$titleAttr = ' title="' . $title . '" ';
		}
		return '<a href="' . $url . '"'. $titleAttr .'>' . $text . '</a>';
	}
}