<? namespace Intervolga\Common\Tools;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Intervolga\Common\Tools\Orm\FormCrmTable;

Loc::loadMessages(__FILE__);

class CatchFormCrm
{
	const SYMBOL_DISABLE = '[disable]';

	/**
	 * @return bool результат выполнения функции
	 */
	public static function checkOption()
	{
		if (!Loader::includeModule('form'))
		{
			return false;
		}

		if (CatchFormCrm::isActive())
		{
			CatchFormCrm::breakSending();
		}
		else
		{
			CatchFormCrm::repairSending();
		}

		return true;
	}

	/**
	 * Проверить активность галочки
	 *
	 * @return bool активность галочки в админке
	 */
	public static function isActive()
	{
		if (Option::get("intervolga.common", "catch_form_crm") == "Y")
		{
			return true;
		}

		return false;
	}

	/**
	 * Прекратить отправку в CRM
	 *
	 * @return bool статус остановки
	 */
	public static function breakSending()
	{
		if (!static::exist())
		{
			return false;
		}

		$rsDataCRM = FormCrmTable::getList(
			array(
				'select' => array(
					'ID',
					'URL',
				),
			)
		);

		while ($data = $rsDataCRM->fetch())
		{
			static::breakConnection($data);
		}

		return true;
	}

    /**
     * Восстановить отправку в CRM
     *
     * @return bool статус восстановления
     */
    public static function repairSending()
    {
        if (!Loader::includeModule('form'))
        {
            return true;
        }

        if (!static::exist())
        {
            return false;
        }

		$rsDataCRM = FormCrmTable::getList(
			array(
				'select' => array(
					'ID',
					'URL',
				),
			)
		);

		while ($data = $rsDataCRM->fetch())
		{
			static::repairConnection($data);
		}

		return true;
	}

	/**
	 * Проверить есть ли хоть одна запись в таблице b_form_crm
	 *
	 * @return bool наличие записей в таблице b_form_crm
	 */
	protected static function exist()
	{
		$countEntity = FormCrmTable::getCount();

		if (intval($countEntity) > 0)
		{
			return true;
		}

		return false;
	}

	/**
	 * Прервать связь с CRM, подставив лишний символ в URL
	 *
	 * @param array $data данные записи CRM
	 * @return bool статус подстановки
	 */
	protected static function breakConnection(array $data)
	{
		if (!$data || !$data['URL'])
		{
			return false;
		}

		if (!static::checkUrl($data['URL']))
		{
			FormCrmTable::update(
				$data['ID'],
				array(
					'URL' => static::getBreakUrl($data['URL']),
				)
			);
		}

		return true;
	}

	/**
	 * Восстановить связь с CRM
	 *
	 * @param array $data данные записи CRM
	 * @return bool статус восстановления связь с CRM
	 */
	protected static function repairConnection(array $data)
	{
		if (!$data || !$data['URL'])
		{
			return false;
		}

		if (static::checkUrl($data['URL']))
		{
			FormCrmTable::update(
				$data['ID'],
				array(
					'URL' => static::getRepairUrl($data['URL']),
				)
			);
		}

		return true;
	}

	/**
	 * Получить восстановленный URL
	 *
	 * @param string $url сломанный адрес CRM
	 * @return string
	 */
	protected static function getRepairUrl($url)
	{
		return str_replace(static::SYMBOL_DISABLE, '', $url);
	}

	/**
	 * Получить сломанный URL
	 *
	 * @param string $url оригинальный адрес CRM
	 * @return string изменный адрес CRM
	 */
	protected static function getBreakUrl($url)
	{
		return $url . static::SYMBOL_DISABLE;
	}

	/**
	 * Проверить URL на вхождение строки
	 *
	 * @param string $url адрес CRM
	 * @return bool статус нахождения подстроки
	 */
	protected static function checkUrl($url)
	{
		if (strpos($url, static::SYMBOL_DISABLE) !== false)
		{
			return true;
		}

		return false;
	}
}