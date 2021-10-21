<?php

namespace Intervolga\Common\Admin;

use Bitrix\Main\Entity\EnumField;
use Bitrix\Main\Web\Uri;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Context;


Loc::loadMessages(__FILE__);

class SentMessages extends OrmListPage
{
	var $sites;
	var $filterFields;

	/**
	 * SentMessages constructor.
	 */
	public function __construct()
	{
		$this->sites = $this->getAllSites();
		parent::__construct('\Intervolga\Common\Tools\Orm\MessagesTable');
		$this->tableId = 'b_event';
		$this->buildFilterFieldsList();
	}

	/**
	 * @param \CAdminListRow $row
	 * @param string $ormRecordColumn
	 * @param object|string $ormRecordValue
	 * @param array $ormRecord
	 */
	protected function addOrmRecordColumnValue(&$row, $ormRecordColumn, $ormRecordValue, $ormRecord)
	{
		if ($ormRecordColumn == 'ID')
		{
			$ormClass = $this->ormClass;
			$uri = new Uri('/bitrix/admin/perfmon_row_edit.php');
			$uri->addParams(array(
				'lang' => LANGUAGE_ID,
				'table_name' => $ormClass::getTableName(),
				'pk' => array(
					$ormRecordColumn => $ormRecordValue,
				),
			));
			$this->addViewLinkField($row, $ormRecordColumn, $uri->getUri(), $ormRecordValue, Loc::getMessage('INTERVOLGA_COMMON.EDIT'));
		}
		elseif (is_object($ormRecordValue))
		{
			$row->addViewField($ormRecordColumn, $ormRecordValue->toString());
		}
		elseif (is_array($ormRecordValue))
		{
			$row->addViewField($ormRecordColumn, '<pre>' . print_r($ormRecordValue, true) . '</pre>');
		}
		elseif (in_array($ormRecordColumn, $this->getFileFields()))
		{
			$this->addViewFileField($row, $ormRecordColumn, $ormRecordValue);
		}
		else
		{
			if($ormRecordColumn == 'EVENT_NAME')
			{
				$uri = new Uri('/bitrix/admin/type_edit.php');
				$uri->addParams(array(
					'EVENT_NAME' => $ormRecordValue
				));
				$this->addViewLinkField($row, $ormRecordColumn, $uri->getUri(), $ormRecordValue, Loc::getMessage('INTERVOLGA_COMMON.EDIT'));
			}
			elseif ($ormRecordColumn == 'LID' && in_array($ormRecordValue, $this->sites))
			{
				$uri = new Uri('/bitrix/admin/site_edit.php');
				$uri->addParams(array(
					'lang' => LANGUAGE_ID,
					'LID' => $ormRecordValue
				));
				$this->addViewLinkField($row, $ormRecordColumn, $uri->getUri(), $ormRecordValue, Loc::getMessage('INTERVOLGA_COMMON.EDIT'));
			}
			elseif ($ormRecordColumn == 'MESSAGE_ID')
			{
				if($ormRecordValue)
				{
					$uri = new Uri('/bitrix/admin/message_edit.php');
					$uri->addParams(array(
						'lang' => LANGUAGE_ID,
						'ID' => $ormRecordValue
					));
					$this->addViewLinkField($row, $ormRecordColumn, $uri->getUri(), $ormRecordValue, Loc::getMessage('INTERVOLGA_COMMON.EDIT'));
				}
			}
			elseif($ormRecordColumn == 'C_FIELDS')
			{
				$ormRecordValue = unserialize($ormRecordValue);
				$ormRecordValue = print_r($ormRecordValue, true);
				$row->addViewField($ormRecordColumn, $ormRecordValue);
			}
			elseif($ormRecordColumn == 'SUCCESS_EXEC')
			{
				$ormRecordValue = $this->getExecDesc($ormRecordValue);
				$row->addViewField($ormRecordColumn, $ormRecordValue);
			}
			elseif($ormRecordColumn == 'DUPLICATE')
			{
				if($ormRecordValue == 'Y')
				{
					$ormRecordValue = Loc::getMessage('IV_MESSAGE_YES');
				}
				else
				{
					$ormRecordValue = Loc::getMessage('IV_MESSAGE_NO');
				}
				$row->addViewField($ormRecordColumn, $ormRecordValue);
			}
			else
			{
				$row->addViewField($ormRecordColumn, $ormRecordValue);
			}
		}
	}

	/**
	 * @param string $mapItemKey
	 * @param array|\Bitrix\Main\Entity\Field $mapItem
	 */
	protected function showMapItemFilterInput($mapItemKey, $mapItem)
	{
		if($this->filterFields[$mapItemKey])
		{
			$request = Context::getCurrent()->getRequest();
			if($mapItemKey == 'SUCCESS_EXEC')
			{
				$this->multiSelect($mapItemKey, $request);
			}
			else
			{
				$this->inputSelect($mapItemKey, $request);
			}
		}
		else
		{
			parent::showMapItemFilterInput($mapItemKey, $mapItem);
		}
	}

	protected function makeFilter()
	{
		parent::makeFilter();
		if($this->filter['SUCCESS_EXEC'][0] == null)
		{
			unset($this->filter['SUCCESS_EXEC']);
		}
	}

	/**
	 * @param $mapItemKey
	 * @param $request
	 */
	protected function inputSelect($mapItemKey, $request)
	{
		?>
		<select name="find_<?=$mapItemKey?>">
			<option value=""><?=Loc::getMessage('INTERVOLGA_COMMON.NOT_SET')?></option>
			<? foreach ($this->filterFields[$mapItemKey] as $key => $value):?>
				<option value="<?=$key?>" <? if ($request->getQuery('find_' . $mapItemKey) === $key): ?>selected<? endif ?>>
					<?=$value?>
				</option>
			<? endforeach ?>
		</select>
		<?
	}

	/**
	 * @param $mapItemKey
	 * @param $request
	 */
	protected function multiSelect($mapItemKey, $request)
	{
		$multiplyField = $request->getQuery('find_' . $mapItemKey);
		?>
		<select name="find_<?=$mapItemKey?>[]" multiple>
			<option value="" <?if($this->filter['SUCCESS_EXEC'][0] == null):?> selected <?endif;?>><?=Loc::getMessage('INTERVOLGA_COMMON.NOT_SET')?></option>
			<? foreach ($this->filterFields[$mapItemKey] as $key => $value):?>
				<option value="<?=$key?>" <? if (in_array($key,$multiplyField)): ?>selected<? endif ?>>
					<?=$value?>
				</option>
			<? endforeach ?>
		</select>
		<?
	}

	/**
	 * @param null $ormRecordValue
	 * @return array|mixed
	 */
	protected function getExecDesc($ormRecordValue = null)
	{
		$desc = [
			'O' => '[0] '.Loc::getMessage('IV_MESSAGE_ERROR_NOT_FOUND'),
			'N' => '[N] '.Loc::getMessage('IV_MESSAGE_IN_QUEUE'),
			'Y' => '[Y] '.Loc::getMessage('IV_MESSAGE_SUCCESS'),
			'F' => '[F] '.Loc::getMessage('IV_MESSAGE_ERROR_MAIN'),
			'P' => '[P] '.Loc::getMessage('IV_MESSAGE_ERROR_PART'),
		];
		if($ormRecordValue !== null || $ormRecordValue == 'O')
		{
			return $desc[$ormRecordValue];
		}
		else
		{
			return $desc;
		}
	}

	/**
	 * Filling filter fields with values
	 */
	protected function buildFilterFieldsList()
	{
		$this->getEventList();
		$this->filterFields['SUCCESS_EXEC'] = $this->getExecDesc();
		$this->filterFields['DUPLICATE'] = [
			'Y' => 'Да',
			'N' => 'Нет'
		];
		$langs = [];
		$rsLang = \CLanguage::GetList($by='lid', $order='desc');
		while ($arLang = $rsLang->Fetch())
		{
			$langs[$arLang['LID']] = $arLang['NAME'];
		}
		$this->filterFields['LANGUAGE_ID'] = $langs;
	}

	/**
	 * @return array|string[]
	 */
	protected function getDateFields()
	{
		return ['DATE_INSERT', 'DATE_EXEC'];
	}

	/**
	 * @return array
	 */
	protected function getAllSites()
	{
		$rsSites = \Bitrix\Main\SiteTable::getList();
		$sites = [];
		while($arSite = $rsSites->fetch())
		{
			$this->filterFields['LID'][$arSite['LID']] = '['.$arSite['LID'].'] '.$arSite['NAME'];
			$sites[] = $arSite['LID'];
		}
		return $sites;
	}

	/**
     * Sets events in $this->filterFields
	 */
	protected function getEventList()
	{
		$request = Context::getCurrent()->getRequest();
		$lang = $request->getQuery('lang') ? $request->getQuery('lang') : LANG ;
		$listEvents = \CEventType::GetList(['LID' => $lang], ['name'=>'asc']);
		while($event = $listEvents-> fetch())
		{
			$desc = $event['NAME'].' ['.$event['EVENT_NAME'].']';
			$this->filterFields['EVENT_NAME'][$event['EVENT_NAME']] = $desc;
		}
	}
}