<?namespace Intervolga\Common\Admin;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UserTable;
use Intervolga\Common\Tools\Orm\Log1cTable;

Loc::loadMessages(__FILE__);

class Log1CPage extends OrmListPage
{
	public function __construct()
	{
		/**
		 * @see \Intervolga\Common\Tools\Orm\Log1cTable
		 */
		parent::__construct('\Intervolga\Common\Tools\Orm\Log1cTable');
		$this->tableId = 'intervolga_log1c';
	}

	protected function getDateFields()
	{
		return array(
			'DATE_CREATE',
			'DATE_END',
		);
	}

	protected function getIntervalFields()
	{
		return array(
			'ID',
			'SECTIONS_DELETED',
			'SECTIONS_UPDATED',
			'SECTIONS_ADDED',
			'FILE_SIZE',
			'USER',
		);
	}

	protected function getFileFields()
	{
		return array(
			'RESPONSE_FILE',
			'FILES_MAP',
			'COPY_FILE',
		);
	}

	protected function addOrmRecordColumnValue(&$row, $ormRecordColumn, $ormRecordValue, $ormRecord)
	{
		if ($ormRecordColumn == 'FILE_SIZE')
		{
			if ($ormRecordValue)
			{
				$ormRecordValue = \CFile::FormatSize($ormRecordValue);
			}
			else
			{
				$ormRecordValue = '';
			}
		}
		elseif ($ormRecordColumn == 'RESPONSE')
		{
			$ormRecordValue = $this->prepareResponseDisplay($ormRecordValue);
		}
		elseif ($ormRecordColumn == 'AUTH_PASSWORD')
		{
			if ($ormRecordValue == Log1cTable::PASSWORD_CORRECT)
			{
				$ormRecordValue = Loc::getMessage('YES');
			}
			elseif ($ormRecordValue == Log1cTable::PASSWORD_INCORRECT)
			{
				$ormRecordValue = Loc::getMessage('NO');
			}
			else
			{
				$ormRecordValue = '';
			}
		}
		if ($ormRecordColumn == 'USER')
		{
			if ($ormRecordValue)
			{
				$user = UserTable::getById($ormRecordValue)->fetch();
				$url = '/bitrix/admin/user_edit.php?lang=ru&ID=' . intval($ormRecordValue);
				static::addViewLinkField($row, $ormRecordColumn, $url, '[' . $ormRecordValue . '] ' . $user['LOGIN']);
			}
		}
		elseif ($ormRecordColumn == 'SESSID')
		{
			static::addViewFilterLinkField($row, $ormRecordColumn, $ormRecordValue);
		}
		else
		{
			parent::addOrmRecordColumnValue($row, $ormRecordColumn, $ormRecordValue, $ormRecord);
		}
	}

	/**
	 * @param string $response
	 *
	 * @return string
	 */
	protected function prepareResponseDisplay($response)
	{
		$response = trim(strip_tags($response));

		$explode = explode("\n", $response);
		$explode[0] = trim($explode[0]);
		if ($explode[0] == 'success')
		{
			$explode[0] = $this->wrapColor($explode[0], 'green');
			$response = implode("\n", $explode);
		}
		elseif ($explode[0] == 'progress')
		{
			$explode[0] = $this->wrapColor($explode[0], 'blue');
			$response = implode("\n", $explode);
		}
		elseif ($explode[0] == 'failure')
		{
			$explode[0] = $this->wrapColor($explode[0], 'red');
			$response = implode("\n", $explode);
		}
		elseif ($explode[0] == '<?xml version="1.0" encoding="windows-1251"?>')
		{
			$response = htmlspecialchars($response);
		}
		else
		{
			$response = str_replace('Fatal error', $this->wrapColor('Fatal error', 'red'), $response);
			$response = str_replace('MySQL Query Error', $this->wrapColor('MySQL Query Error', 'red'), $response);
			$response = str_replace('Parse error', $this->wrapColor('Parse error', 'red'), $response);
		}
		$response = nl2br($response);

		return $response;
	}

	/**
	 * @param string $text
	 * @param string $color
	 *
	 * @return string
	 */
	protected static function wrapColor($text, $color)
	{
		return '<b style="color: ' . $color . ';">' . $text . '</b>';
	}

	public function show()
	{
		parent::show();
		$this->showFooter();
	}

	protected function showFooter()
	{
		$url = '/bitrix/admin/settings.php?lang=ru&mid=intervolga.common';
		$debug = Option::get('intervolga.common', 'debug_1c');
		echo BeginNote();
		if ($debug == 'Y')
		{
			echo Loc::getMessage('INTERVOLGA_COMMON.DEBUG_Y', array('#URL#' => $url));
		}
		else
		{
			echo Loc::getMessage('INTERVOLGA_COMMON.DEBUG_N', array('#URL#' => $url));
		}
		echo EndNote();
	}
}