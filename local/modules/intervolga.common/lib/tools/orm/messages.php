<?namespace Intervolga\Common\Tools\Orm;

use Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\Localization\Loc;

Loc::loadmessages(__FILE__);

class MessagesTable extends DataManager
{
	public static function getTableName()
	{
		return 'b_event';
	}

	public static function getMap()
	{
		return array(
			'ID' => array(
				'data_type' => 'integer',
				'primary' => true,
				'autocomplete' => true,
				'title' => Loc::getMessage('IV_MESSAGE_ID'),
			),
			'EVENT_NAME' => [
				'data_type' => 'string',
				'title' => Loc::getMessage('IV_MESSAGE_EVENT_NAME'),
			],
			'MESSAGE_ID' => [
				'data_type' => 'integer',
				'title' => Loc::getMessage('IV_MESSAGE_MESSAGE_ID'),
			],
			'LID' => [
				'data_type' => 'string',
				'title' => Loc::getMessage('IV_MESSAGE_LID'),
			],
			'C_FIELDS' => [
				'data_type' => 'string',
				'title' => Loc::getMessage('IV_MESSAGE_C_FIELDS'),
			],
			'DATE_INSERT' => [
				'data_type' => 'datetime',
				'title' => Loc::getMessage('IV_MESSAGE_DATE_INSERT'),
			],
			'DATE_EXEC' => [
				'data_type' => 'datetime',
				'title' => Loc::getMessage('IV_MESSAGE_DATE_EXEC'),
			],
			'SUCCESS_EXEC' => [
				'data_type' => 'string',
				'title' => Loc::getMessage('IV_MESSAGE_SUCCESS_EXEC'),
			],
			'DUPLICATE' => [
				'data_type' => 'string',
				'title' => Loc::getMessage('IV_MESSAGE_DUPLICATE'),
			],
			'LANGUAGE_ID' => [
				'data_type' => 'string',
				'title' => Loc::getMessage('IV_MESSAGE_LANGUAGE_ID'),
			]
		);
	}
}