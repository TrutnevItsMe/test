<? namespace Intervolga\Common\Tools\Orm;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\Validator\Length;
use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class FormCrmTable extends DataManager
{
	public static function getTableName()
	{
		return 'b_form_crm';
	}

	public static function getMap()
	{
		return array(
			new IntegerField(
				'ID',
				array(
					'primary' => true,
					'autocomplete' => true,
					'title' => Loc::getMessage('INTERVOLGA_COMMON.CRM_TABLE_ID'),
				)
			),
			new StringField(
				'NAME',
				array(
					'required' => true,
					'validation' => array(__CLASS__, 'validateName'),
					'title' => Loc::getMessage('INTERVOLGA_COMMON.CRM_TABLE_NAME'),
				)
			),
			new BooleanField(
				'ACTIVE', array(
					'values' => array('N', 'Y'),
				)
			),
			new StringField(
				'URL',
				array(
					'required' => true,
					'validation' => array(__CLASS__, 'validateUrl'),
					'title' => Loc::getMessage('INTERVOLGA_COMMON.CRM_TABLE_URL'),
				)
			),
			new StringField(
				'AUTH_HASH',
				array(
					'validation' => array(__CLASS__, 'validateAuthHash'),
					'title' => Loc::getMessage('INTERVOLGA_COMMON.CRM_TABLE_AUTH_HASH'),
				)
			),
		);
	}

	/**
	 * Returns validators for NAME field.
	 *
	 * @return array
	 */
	public static function validateName()
	{
		return array(
			new Length(null, 255),
		);
	}

	/**
	 * Returns validators for URL field.
	 *
	 * @return array
	 */
	public static function validateUrl()
	{
		return array(
			new Length(null, 255),
		);
	}

	/**
	 * Returns validators for AUTH_HASH field.
	 *
	 * @return array
	 */
	public static function validateAuthHash()
	{
		return array(
			new Length(null, 32),
		);
	}
}