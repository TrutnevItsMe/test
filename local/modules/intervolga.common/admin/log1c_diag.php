<?
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;
use Bitrix\Main\Page\Asset;
use Intervolga\Common\Admin\Log1CDiag;

CJSCore::Init('jquery');

$assets = Asset::getInstance();
$assets->addJs('/local/js/intervolga.common/admin/gantt.js');
$assets->addJs('/local/js/intervolga.common/admin/tooltip.js');

$request = Application::getInstance()->getContext()->getRequest();

define('ADMIN_MODULE_NAME', 'intervolga.common');

$jsData = array();

if (Loader::includeModule('intervolga.common'))
{
	$findStartVal = $request->get('find_start');
	$findEndVal = $request->get('find_end');
	$data = Log1CDiag::getData($findStartVal, $findEndVal);
	if ($groups = Log1CDiag::prepareGroups($data))
	{
		$jsData['GROUPS'] = $groups;
	}
	if ($items = Log1CDiag::prepareItems($data))
	{
		$jsData['ITEMS'] = $items;
	}
}

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');
?>
	<script src="/local/js/intervolga.common/vis/dist/vis.js"></script>
	<link href="/local/js/intervolga.common/vis/dist/vis-timeline-graph2d.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?
if (Loader::includeModule('intervolga.common') && $USER->isAdmin())
{
	$APPLICATION->setTitle(Loc::getMessage('INTERVOLGA_COMMON.DIAG_1C_PAGE'));
	?>
	<form method="get">
		<?=Loc::getMessage('INTERVOLGA_COMMON.START')?>: <?echo CAdminCalendar::CalendarDate('find_start', $request->get('find_start') ? : '', 10, true); ?>
		<?=Loc::getMessage('INTERVOLGA_COMMON.END')?>: <?echo CAdminCalendar::CalendarDate('find_end', $request->get('find_end') ? : '', 10, true); ?>
		<input type="submit" value="<?=Loc::getMessage('INTERVOLGA_COMMON.DRAW')?>">
	</form>
	<br />
	<div id="visualization"></div>
	<br />
	<? if ($jsData): ?>
		<input type="button" onclick="window.reInitGantt()" value="<?=Loc::getMessage('INTERVOLGA_COMMON.REDRAW')?>">
	<? endif; ?>
<?
}
?>

<? if ($jsData): ?>
	<script type="text/javascript" data-skip-moving="true">
		window.ganttChart = {};
		window.ganttChart.groups = <?=\Bitrix\Main\Web\Json::encode($jsData['GROUPS'])?>;
		window.ganttChart.items = <?=\Bitrix\Main\Web\Json::encode($jsData['ITEMS'])?>;
		window.initGantt();
		window.initTooltip();
	</script>
<? endif;

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
?>