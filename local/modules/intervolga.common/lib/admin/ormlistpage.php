<?namespace Intervolga\Common\Admin;

use Bitrix\Main\Application;
use Bitrix\Main\Context;
use Bitrix\Main\Entity\EnumField;
use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\Entity\Field;
use Bitrix\Main\IO\File;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Web\Uri;

Loc::loadMessages(__FILE__);

abstract class OrmListPage
{
	/**
	 * Full orm class name.
	 *
	 * @var string|\Bitrix\Main\Entity\DataManager
	 */
	protected $ormClass = '';
	/**
	 * Table id attribute.
	 *
	 * @var string
	 */
	protected $tableId = 'ormEntity';
	/**
	 * Orm fields.
	 *
	 * @var array orm map
	 */
	protected $map = array();
	/**
	 * Orm fields to be displayed.
	 *
	 * @var array
	 */
	protected $displayMap = array();
	/**
	 * Get list filter.
	 *
	 * @var array filter
	 */
	protected $filter = array();

	/**
	 * Admin list object.
	 *
	 * @var \CAdminList
	 */
	protected $adminList = null;
	/**
	 * Admin list sorting object.
	 *
	 * @var \CAdminSorting
	 */
	protected $adminSorting = null;
	/**
	 * Admin list filter object.
	 * @var \CAdminFilter
	 */
	protected $adminFilter = null;

	/**
	 * Prepares orm admin list for displaying.
	 *
	 * @param string|\Bitrix\Main\Entity\DataManager $ormClass
	 */
	public function __construct($ormClass)
	{
		$this->initialize($ormClass);
		$this->doActions();
		$this->setHeaders();
		$this->select();
		$this->adminList->addAdminContextMenu(array());
		$this->adminList->checkListMode();
	}

	public function show()
	{
		$this->showFilter();
		$this->adminList->displayList();
	}

	/**
	 * Initializes variables, loads orm mapper, etc.
	 *
	 * @param string|\Bitrix\Main\Entity\DataManager $ormClass
	 */
	protected function initialize($ormClass)
	{
		$this->ormClass = $ormClass;
		$this->map = $ormClass::getMap();
		$this->makeDisplayMap();
		$this->adminSorting = new \CAdminSorting($this->tableId, 'ID', 'asc');
		$this->adminList = new \CAdminList($this->tableId, $this->adminSorting);
	}

	/**
	 * Runs all admin list actions.
	 */
	protected function doActions()
	{
		if ($arIds = $this->adminList->GroupAction())
		{
			if ($arIds)
			{
				foreach ($arIds as $iId)
				{
					switch($_REQUEST['action'])
					{
						case 'delete':
							$sClass = $this->ormClass;
							$obResult = $sClass::delete($iId);
							if (!$obResult->isSuccess())
							{
								$this->adminList->AddGroupError('(ID=' . $iId . ') ' . implode('<br>', $obResult->getErrorMessages()), $iId);
							}
							break;
					}
				}
			}
		}
	}

	/**
	 * Sets table headers.
	 */
	protected function setHeaders()
	{
		$arHeaders = array();
		foreach ($this->displayMap as $sMapItemKey => $mapItem)
		{
			$arHeaders []= array(
				'id' => $sMapItemKey,
				'content' => self::getMapEntityItemTitle($mapItem, $sMapItemKey),
				'sort' => $sMapItemKey,
				'default' => TRUE,
			);
		}
		$this->adminList->AddHeaders($arHeaders);
	}

	/**
	 * Prepares and runs select query.
	 */
	protected function select()
	{
		$this->makeFilter();
		$this->executeSelect();
	}

	/**
	 * Prepares filter for select query.
	 */
	protected function makeFilter()
	{
		foreach ($this->displayMap as $sMapItemKey => $arMapItem)
		{
			if ($GLOBALS['find_' . $sMapItemKey])
			{
				$this->filter[$sMapItemKey] = $GLOBALS['find_' . $sMapItemKey];
			}
		}
		if ($arIntervalFields = $this->getIntervalFields())
		{
			foreach ($arIntervalFields as $sIntervalField)
			{
				if (strlen($GLOBALS['find_' . $sIntervalField . '_1']) > 0)
				{
					$this->filter['>=' . $sIntervalField] = $GLOBALS['find_' . $sIntervalField . '_1'];
				}
				if (strlen($GLOBALS['find_' . $sIntervalField . '_2']) > 0)
				{
					$this->filter['<=' . $sIntervalField] = $GLOBALS['find_' . $sIntervalField . '_2'];
				}
			}
		}
		if ($arDateFields = $this->getDateFields())
		{
			foreach ($arDateFields as $sDateField)
			{
				if (strlen($GLOBALS['find_' . $sDateField . '_1']) > 0)
				{
					$this->filter['>=' . $sDateField] = $GLOBALS['find_' . $sDateField . '_1'];
				}
				if (strlen($GLOBALS['find_' . $sDateField . '_2']) > 0)
				{
					$this->filter['<=' . $sDateField] = $GLOBALS['find_' . $sDateField . '_2'];
					if (strlen($GLOBALS['find_' . $sDateField . '_2']) == 10)
					{
						$this->filter['<=' . $sDateField] .= ' 23:59:59';
					}
				}
			}
		}
		if (strlen($GLOBALS['find']) && strlen($GLOBALS['find_type']))
		{
			if (in_array($GLOBALS['find_type'], array_keys($this->displayMap)))
			{
				$this->filter[$GLOBALS['find_type']] = $GLOBALS['find'];
			}
		}

		if ($GLOBALS['del_filter'] == 'Y')
		{
			$this->filter = array();
		}
	}

	/**
	 * Runs select query.
	 */
	protected function executeSelect()
	{
		$arOrder = array('ID' => 'ASC');
		if (strlen($GLOBALS['by']) && strlen($GLOBALS['order']))
		{
			$arOrder = array($GLOBALS['by'] => $GLOBALS['order']);
		}
		$arGetListParams = array(
			'order' => $arOrder,
			'filter' => $this->filter,
		);
		if ($this->getFieldsFilter())
		{
			$arGetListParams['select'] = $this->getFieldsFilter();
		}
		$dbOrmRecords = call_user_func(array($this->ormClass, 'getList'), $arGetListParams);
		$dbAdminOrmRecords = new \CAdminResult($dbOrmRecords, $this->tableId);
		$dbAdminOrmRecords->navStart();
		$this->adminList->navText($dbAdminOrmRecords->GetNavPrint(Loc::getMessage('INTERVOLGA_COMMON.ORM_REPORTS')));
		while ($arOrmRecord = $dbAdminOrmRecords->NavNext())
		{
			$obRow =& $this->adminList->addRow($arOrmRecord['ID']);
			$obRow->addViewField('ID', $arOrmRecord['ID']);
			$this->addOrmRecord($obRow, $arOrmRecord);
		}
	}

	/**
	 * Adds orm record to admin list.
	 *
	 * @param \CAdminListRow $row admin list row
	 * @param array $ormRecord orm record
	 */
	protected function addOrmRecord(&$row, $ormRecord)
	{
		foreach ($ormRecord as $ormRecordColumn => $ormRecordValue)
		{
			$this->addOrmRecordColumnValue($row, $ormRecordColumn, $ormRecordValue, $ormRecord);
		}
	}

	/**
	 * Adds orm record's column value to admin list.
	 *
	 * @param \CAdminListRow $row admin list row
	 * @param string $ormRecordColumn orm record key
	 * @param string|object $ormRecordValue orm record value
	 * @param array $ormRecord orm record
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
			$row->addViewField($ormRecordColumn, $ormRecordValue);
		}
	}

	/**
	 * @param \CAdminListRow $row
	 * @param string $ormRecordColumn
	 * @param string $filePath
	 */
	protected function addViewFileField(&$row, $ormRecordColumn, $filePath)
	{
		if ($filePath)
		{
			$file = new File($filePath);
			if ($file->isExists())
			{
				$url = '/bitrix/admin/fileman_file_edit.php?full_src=Y&site=s1&lang=ru&path=';
				$root = Application::getDocumentRoot();
				$filePath = str_replace($root, '', $filePath);
				$this->addViewLinkField($row, $ormRecordColumn, $url . urlencode($filePath), $file->getName());
			}
			else
			{
				$row->addViewField($ormRecordColumn, $file->getName());
			}
		}
	}

	/**
	 * @param \CAdminListRow $row
	 * @param string $ormRecordColumn
	 * @param string $url
	 * @param string $text
	 * @param string $title
	 */
	protected function addViewLinkField(&$row, $ormRecordColumn, $url, $text, $title = '')
	{
		$titleAttr = '';
		if ($title)
		{
			$titleAttr = ' title="' . $title . '" ';
		}
		$row->addViewField($ormRecordColumn, '<a href="' . $url . '"'. $titleAttr .'>' . $text . '</a>');
	}

	/**
	 * @param \CAdminListRow $row
	 * @param string $ormRecordColumn
	 * @param mixed $ormRecordValue
	 */
	protected function addViewFilterLinkField($row, $ormRecordColumn, $ormRecordValue)
	{
		global $APPLICATION;
		$deleteParams = array(
			'mode',
			'find_' . $ormRecordColumn
		);
		$url = $APPLICATION->getCurPageParam('find_' . $ormRecordColumn . '=' . urlencode($ormRecordValue), $deleteParams);
		$this->addViewLinkField($row, $ormRecordColumn, $url, $ormRecordValue, Loc::getMessage('INTERVOLGA_COMMON.FILTER_THIS'));
	}

	public function initializeFilter()
	{
		$filterPopup = array();
		foreach ($this->displayMap as $mapItemKey => $mapItem)
		{
			if ($title = self::getMapEntityItemTitle($mapItem, $mapItemKey))
			{
				$filterPopup[$mapItemKey] = $title;
			}
		}

		$this->adminFilter = new \CAdminFilter($this->tableId . '_filter', $filterPopup);
	}

	/**
	 * Shows admin filter.
	 */
	protected function showFilter()
	{
		global $APPLICATION;
		?>
		<form name="find_form" method="get" action="<?=$APPLICATION->GetCurPage()?>">
			<?$this->adminFilter->Begin();?>
			<tr>
				<td>
					<?=Loc::getMessage('INTERVOLGA_COMMON.ORM_FIND')?>
				</td>
				<td>
					<input type="text" name="find" value="<?=htmlspecialcharsbx($GLOBALS['find'])?>" title="<?=Loc::getMessage('INTERVOLGA_COMMON.ORM_FIND')?>">
					<?
					$arr = array(
						'reference' => array(),
						'reference_id' => array(),
					);
					foreach ($this->displayMap as $mapItemKey => $mapItem)
					{
						$arr['reference'][] = self::getMapEntityItemTitle($mapItem, $mapItemKey);
						$arr['reference_id'][] = $mapItemKey;
					}
					echo selectBoxFromArray('find_type', $arr, $GLOBALS['find_type'], '', '');
					?>
				</td>
			</tr>
			<? foreach ($this->displayMap as $mapItemKey => $mapItem):?>
				<?$this->showMapItemFilter($mapItemKey, $mapItem)?>
			<? endforeach ?>
			<?
				$this->adminFilter->Buttons(array(
					'table_id' => $this->tableId,
					'url' => $APPLICATION->GetCurPage(),
					'form' => 'find_form'
				));
				$this->adminFilter->End();
			?>
		</form>
		<?
	}

	/**
	 * Shows admin filter for single map item.
	 *
	 * @param string $mapItemKey map item code
	 * @param Field|array $mapItem map item array
	 */
	protected function showMapItemFilter($mapItemKey, $mapItem)
	{
		?>
		<tr>
			<td><?=static::getMapEntityItemTitle($mapItem, $mapItemKey)?></td>
			<td><?$this->showMapItemFilterInput($mapItemKey, $mapItem)?></td>
		</tr>
		<?
	}

	/**
	 * Shows admin filter input for single map item.
	 *
	 * @param string $mapItemKey map item code
	 * @param Field|array $mapItem map item array
	 */
	protected function showMapItemFilterInput($mapItemKey, $mapItem)
	{
		?>
		<? if ($this->getIntervalFields() && in_array($mapItemKey, $this->getIntervalFields())): ?>
			<?$this->showMapItemFilterIntervalInput($mapItemKey, $mapItem) ?>
		<? elseif ($this->getDateFields() && in_array($mapItemKey, $this->getDateFields())): ?>
			<?$this->showMapItemFilterDateInput($mapItemKey, $mapItem) ?>
		<? elseif ($mapItem instanceof EnumField): ?>
			<?$this->showMapItemFilterEnumInput($mapItemKey, $mapItem) ?>
		<? else: ?>
			<input type="text" name="find_<?=$mapItemKey?>" value="<?=htmlspecialchars($_REQUEST['find_' . $mapItemKey])?>">
		<?endif ?>
		<?
	}

	/**
	 * Shows admin filter enum input for single map item.
	 *
	 * @param string $mapItemKey map item code
	 * @param EnumField $mapItem map item array
	 */
	protected function showMapItemFilterEnumInput($mapItemKey, EnumField $mapItem)
	{
		$request = Context::getCurrent()->getRequest();
		?>
		<select name="find_<?=$mapItemKey?>">
			<option value=""><?=Loc::getMessage('INTERVOLGA_COMMON.NOT_SET')?></option>
			<? foreach ($mapItem->getValues() as $value):?>
				<option value="<?=$value?>" <? if ($request->getQuery('find_' . $mapItemKey) == $value): ?>selected<? endif ?>>
					<?=$value?>
				</option>
			<? endforeach ?>
		</select>
		<?
	}

	/**
	 * Shows admin filter interval input for single map item.
	 *
	 * @param string $mapItemKey map item code
	 * @param Field|array $mapItem map item array
	 */
	protected function showMapItemFilterIntervalInput($mapItemKey, $mapItem)
	{
		?>
		<input type="text" name="find_<?=$mapItemKey?>_1" size="10" value="<?=htmlspecialcharsbx($_REQUEST['find_' . $mapItemKey . '_1'])?>">
		...
		<input type="text" name="find_<?=$mapItemKey?>_2" size="10" value="<?=htmlspecialcharsbx($_REQUEST['find_' . $mapItemKey . '_2'])?>">
		<?
	}

	/**
	 * Shows admin filter date input for single map item.
	 *
	 * @param string $mapItemKey map item code
	 * @param Field|array $mapItem map item array
	 */
	protected function showMapItemFilterDateInput($mapItemKey, $mapItem)
	{
		echo \CAdminCalendar::CalendarPeriod(
			"find_{$mapItemKey}_1",
			"find_{$mapItemKey}_2",
			htmlspecialcharsbx($_REQUEST["find_{$mapItemKey}_1"]),
			htmlspecialcharsbx($_REQUEST["find_{$mapItemKey}_2"]),
			"Y",
			10,
			true
		);
	}

	/**
	 * Returns map entity field title.
	 *
	 * @param Field|array $mapEntityItem entity map item
	 *
	 * @param string $default default title
	 *
	 * @return string
	 */
	public function getMapEntityItemTitle($mapEntityItem, $default = '')
	{
		$title = '';
		if (is_array($mapEntityItem))
		{
			$title = $mapEntityItem['title'];
		}
		elseif ($mapEntityItem instanceof Field)
		{
			if ($mapEntityItem->getTitle())
			{
				$title = $mapEntityItem->getTitle();
			}
			else
			{
				$title = $mapEntityItem->getName();
			}
		}
		if (!$title)
		{
			$title = $default;
		}
		return $title;
	}

	/**
	 * Fills display fields array.
	 */
	public function makeDisplayMap()
	{
		$displayMap = array();
		if ($this->map)
		{
			foreach ($this->map as $mapItemKey => $mapItem)
			{
				if (is_array($mapItem) && $mapItem['reference'])
				{
					continue;
				}
				if (is_object($mapItem) && $mapItem instanceof ExpressionField)
				{
					continue;
				}
				if (is_object($mapItem) && $mapItem instanceof Field)
				{
					$mapItemKey = $mapItem->getName();
				}
				$displayMap[$mapItemKey] = $mapItem;
			}
		}
		if ($fieldsFilter = $this->getFieldsFilter())
		{
			$filteredMap = array();
			foreach ($fieldsFilter as $mapItemKey)
			{
				if ($displayMap[$mapItemKey])
				{
					$filteredMap[$mapItemKey] = $displayMap[$mapItemKey];
				}
				elseif ($this->map[$mapItemKey])
				{
					$filteredMap[$mapItemKey] = $this->map[$mapItemKey];
				}
			}
			$displayMap = $filteredMap;
		}
		$this->displayMap = $displayMap;
	}

	/**
	 * Returns available orm entity field codes.
	 *
	 * Makes possible to change original orm fields order, hide unnecessary fields etc.
	 *
	 * @return string[]
	 */
	protected function getFieldsFilter()
	{
		return array();
	}

	/**
	 * Returns date field codes.
	 *
	 * Date fields will have date filter.
	 *
	 * @return string[]
	 */
	protected function getDateFields()
	{
		return array();
	}

	/**
	 * Returns fields with interval filter.
	 *
	 * @return string[]
	 */
	protected function getIntervalFields()
	{
		return array();
	}

	/**
	 * @return string[]
	 */
	protected function getFileFields()
	{
		return array();
	}
}