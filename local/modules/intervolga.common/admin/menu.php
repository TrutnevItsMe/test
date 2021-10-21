<?
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
$menu = array();

if ($USER->isAdmin())
{
	$menu[] = array(
		'parent_menu' => 'global_menu_settings',
		'section' => 'intervolga.common',
		'sort' => 100,
		'text' => Loc::getMessage('INTERVOLGA_COMMON.INTERVOLGA_MENU'),
		'title' => Loc::getMessage('INTERVOLGA_COMMON.INTERVOLGA_MENU'),
		'items_id' => 'menu_intervolga.common',
		'icon' => 'intervolga_icon',
		'items' => array(
			array(
				'text' => Loc::getMessage('INTERVOLGA_COMMON.LOG_1C_PAGE'),
				'url' => 'intervolga.common_log1c.php?lang=' . LANG,
				'more_url' => array(
					'intervolga.common_log1c.php',
				),
				'title' => Loc::getMessage('INTERVOLGA_COMMON.LOG_1C_PAGE'),
			),
			array(
				'text' => Loc::getMessage('INTERVOLGA_COMMON.MESSAGES_PAGE'),
				'url' => 'intervolga.common_messages.php?lang='.LANG,
				'more_url' => array(
					'intervolga.common_messages.php',
				),
				'title' => Loc::getMessage('INTERVOLGA_COMMON.MESSAGES_PAGE'),
			),
			array(
				'text' => Loc::getMessage('INTERVOLGA_COMMON.DIAG_1C_PAGE'),
				'url' => 'intervolga.common_log1c_diag.php?lang=' . LANG,
				'more_url' => array(
					'intervolga.common_log1c_diag.php',
				),
				'title' => Loc::getMessage('INTERVOLGA_COMMON.DIAG_1C_PAGE'),
			),
			array(
				'text' => Loc::getMessage('INTERVOLGA_COMMON.SEARCH_1C_PAGE'),
				'url' => 'intervolga.common_search_log1c.php?lang=' . LANG,
				'more_url' => array(
					'intervolga.common_search_log1c.php',
				),
				'title' => Loc::getMessage('INTERVOLGA_COMMON.SEARCH_1C_PAGE'),
			),
		),
	);
}

return $menu;