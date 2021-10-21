<?namespace Intervolga\Common\Tools\Orm;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Context;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\EnumField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type\DateTime;
use Intervolga\Common\Tools\Log1c;

Loc::loadMessages(__FILE__);

class Log1cTable extends DataManager
{
	const PASSWORD_CORRECT = 'correct';
	const PASSWORD_INCORRECT = 'incorrect';

	protected static $lastId = 0;

	public static function getTableName()
	{
		return 'intervolga_common_1c_log';
	}

	public static function getMap()
	{
		return array(
			new IntegerField('ID', array(
				'primary' => true,
			)),
			new DateTimeField(
				'DATE_CREATE',
				array(
					'title' => Loc::getMessage('INTERVOLGA_COMMON.LOG_1C_DATE_CREATE'),
				)
			),
			new IntegerField(
				'DATE_START_MS',
				array(
					'title' => Loc::getMessage('INTERVOLGA_COMMON.LOG_1C_DATE_START_MS'),
				)
			),
			new DateTimeField(
				'DATE_END',
				array(
					'title' => Loc::getMessage('INTERVOLGA_COMMON.LOG_1C_DATE_END'),
				)
			),
			new IntegerField(
				'DATE_END_MS',
				array(
					'title' => Loc::getMessage('INTERVOLGA_COMMON.LOG_1C_DATE_END_MS'),
				)
			),
			new StringField('IP'),
			new StringField(
				'USER_AGENT',
				array(
					'title' => Loc::getMessage('INTERVOLGA_COMMON.LOG_1C_USER_AGENT'),
				)
			),
			new IntegerField('USER',
				array(
					'title' => Loc::getMessage('INTERVOLGA_COMMON.LOG_1C_USER'),
				)
			),
			new StringField('SESSID',
				array(
					'title' => Loc::getMessage('INTERVOLGA_COMMON.LOG_1C_SESSID'),
				)
			),
			new StringField('GET_PARAMS', array(
				'serialized' => true,
				'title' => Loc::getMessage('INTERVOLGA_COMMON.LOG_1C_GET'),
			)),
			new StringField('TYPE',
				array(
					'title' => Loc::getMessage('INTERVOLGA_COMMON.LOG_1C_TYPE'),
				)
			),
			new StringField('MODE',
				array(
					'title' => Loc::getMessage('INTERVOLGA_COMMON.LOG_1C_MODE'),
				)
			),
			new StringField('FILE_NAME',
				array(
					'title' => Loc::getMessage('INTERVOLGA_COMMON.LOG_1C_FILE_NAME'),
				)
			),
			new IntegerField('FILE_SIZE',
				array(
					'title' => Loc::getMessage('INTERVOLGA_COMMON.LOG_1C_FILE_SIZE'),
				)
			),
			new StringField('AUTH_LOGIN',
				array(
					'title' => Loc::getMessage('INTERVOLGA_COMMON.LOG_1C_AUTH_LOGIN'),
				)
			),
			new EnumField('AUTH_PASSWORD',
				array(
					'title' => Loc::getMessage('INTERVOLGA_COMMON.LOG_1C_AUTH_PASSWORD'),
					'values' => array(
						static::PASSWORD_CORRECT,
						static::PASSWORD_INCORRECT,
					),
				)
			),
			new IntegerField('SECTIONS_DELETED',
				array(
					'title' => Loc::getMessage('INTERVOLGA_COMMON.LOG_1C_SECTIONS_DELETED'),
				)
			),
			new IntegerField('SECTIONS_UPDATED',
				array(
					'title' => Loc::getMessage('INTERVOLGA_COMMON.LOG_1C_SECTIONS_UPDATED'),
				)
			),
			new IntegerField('SECTIONS_ADDED',
				array(
					'title' => Loc::getMessage('INTERVOLGA_COMMON.LOG_1C_SECTIONS_ADDED'),
				)
			),
			new StringField('RESPONSE',
				array(
					'title' => Loc::getMessage('INTERVOLGA_COMMON.LOG_1C_RESPONSE'),
				)
			),
			new StringField('RESPONSE_FILE',
				array(
					'title' => Loc::getMessage('INTERVOLGA_COMMON.LOG_1C_RESPONSE_FILE'),
				)
			),
			new StringField('FILES_MAP',
				array(
					'title' => Loc::getMessage('INTERVOLGA_COMMON.LOG_1C_FILES_MAP'),
				)
			),
			new StringField('COPY_FILE',
				array(
					'title' => Loc::getMessage('INTERVOLGA_COMMON.LOG_1C_COPY_FILE'),
				)
			),
		);
	}

	public static function log()
	{
		static::clearOld();
		global $USER;
		$request = Context::getCurrent()->getRequest();
		$server = Context::getCurrent()->getServer();

		$importFile = Log1c::getImportingFile($request->getQuery('type'), $request->getQuery('filename'));
		$fileSize = ($importFile && $importFile->isExists()) ? $importFile->getSize() : '';
		$result = static::add(array(
			'IP' => $server->get('REMOTE_ADDR'),
			'USER_AGENT' => $server->get('HTTP_USER_AGENT'),
			'USER' => $USER->getId(),
			'SESSID' => bitrix_sessid(),
			'DATE_CREATE' => new DateTime(),
			'DATE_START_MS' => Log1c::getMicroseconds(),
			'GET_PARAMS' => $request->getQueryList()->toArray(),
			'TYPE' => $request->getQuery('type'),
			'MODE' => $request->getQuery('mode'),
			'FILE_NAME' => $request->getQuery('filename'),
			'FILE_SIZE' => $fileSize,
			'AUTH_LOGIN' => $server->get('PHP_AUTH_USER'),
			'AUTH_PASSWORD' => static::getPasswordResult($server->get('PHP_AUTH_PW')),
		));
		if ($result->isSuccess())
		{
			static::$lastId = $result->getId();
		}
	}

	/**
	 * @param string $password
	 *
	 * @return string
	 */
	protected static function getPasswordResult($password)
	{
		global $USER;
		$result = '';
		if (strlen($password))
		{
			if ($USER->isAuthorized())
			{
				$result = static::PASSWORD_CORRECT;
			}
			else
			{
				$result = static::PASSWORD_INCORRECT;
			}
		}

		return $result;
	}

	/**
	 * @param array $log
	 *
	 * @throws \Exception
	 */
	public static function logUpdate(array $log)
	{
		if (static::$lastId)
		{
			if ($log['RESPONSE'])
			{
				$log['RESPONSE'] = str_replace('<br>', "\n", $log['RESPONSE']);
			}
			static::update(static::$lastId, $log);
		}
	}

	/**
	 * @throws \Exception
	 */
	public static function logEnd()
	{
		if (static::$lastId)
		{
			$log = array();
			$request = Context::getCurrent()->getRequest();
			$importFile = Log1c::getImportingFile($request->getQuery('type'), $request->getQuery('filename'));
			if ($importFile && $importFile->isExists())
			{
				$log['FILE_SIZE'] = $importFile->getSize();
			}
			$log['DATE_END'] = new DateTime();
			$log['DATE_END_MS'] = Log1c::getMicroseconds();
			static::update(static::$lastId, $log);
		}
	}

	/**
	 * @param string $fileName
	 *
	 * @return string
	 * @throws \Bitrix\Main\ArgumentException
	 */
	public static function getCopiedFile($fileName)
	{
		$fetch = static::getList(array(
			'filter' => array(
				'=FILE_NAME' => $fileName,
				'!COPY_FILE' => false,
			),
			'order' => array(
				'ID' => 'DESC',
			),
			'select' => array(
				'ID',
				'COPY_FILE'
			),
		))->fetch();
		if ($fetch)
		{
			return $fetch['COPY_FILE'];
		}
		else
		{
			return '';
		}
	}

	/**
	 * @return \Bitrix\Main\Type\DateTime|null
	 * @throws \Bitrix\Main\ArgumentException
	 */
	public static function getLastDate()
	{
		$fetch = static::getList(array(
			'select' => array(
				'DATE_CREATE'
			),
			'order' => array(
				'DATE_CREATE' => 'DESC',
			),
			'limit' => 1,
		))->fetch();
		return $fetch['DATE_CREATE'];
	}

	/**
	 * @return \Bitrix\Main\Type\DateTime|null
	 * @throws \Bitrix\Main\ArgumentException
	 */
	public static function getFirstDate()
	{
		$fetch = static::getList(array(
			'select' => array(
				'DATE_CREATE'
			),
			'order' => array(
				'DATE_CREATE' => 'ASC',
			),
			'limit' => 1,
		))->fetch();
		return $fetch['DATE_CREATE'];
	}

	protected static function clearOld()
	{
		$days = Option::get('intervolga.common', 'log_1c_period');
		if ($days > 0)
		{
			$date = new DateTime();
			$date->add("-" . $days . " days");
			$getList = static::getList(array(
				'filter' => array(
					'<DATE_CREATE' => $date,
				),
				'select' => array(
					'ID',
				),
			));
			while ($record = $getList->fetch())
			{
				static::delete($record['ID']);
			}
		}
	}
}