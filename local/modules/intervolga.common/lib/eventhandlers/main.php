<?namespace Intervolga\Common\EventHandlers;

use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Intervolga\Common\Tools\Log1c;
use Intervolga\Common\Tools\Orm\Log1cTable;
use Bitrix\Main\Context;
use Intervolga\Common\Tools\CsvAdminViewer;

class Main
{
    /**
     * Позиция кнопки "Просмотр файла CSV".
     */
    const SHOW_CSV_MENU_ITEM_POS = 3;

	public static function onProlog()
	{
		if (Log1c::is1cPage() && Log1c::isDebugMode())
		{
			/**
			 * @see \Intervolga\Common\Tools\Log1c::onAfterIBlockSectionAdd
			 */
			EventManager::getInstance()->addEventHandler(
				'iblock',
				'OnAfterIBlockSectionAdd',
				array(
					'\Intervolga\Common\Tools\Log1c',
					'onAfterIBlockSectionAdd'
				)
			);
			/**
			 * @see \Intervolga\Common\Tools\Log1c::onAfterIBlockSectionUpdate
			 */
			EventManager::getInstance()->addEventHandler(
				'iblock',
				'OnAfterIBlockSectionUpdate',
				array(
					'\Intervolga\Common\Tools\Log1c',
					'onAfterIBlockSectionUpdate'
				));
			/**
			 * @see \Intervolga\Common\Tools\Log1c::onAfterIBlockSectionDelete
			 */
			EventManager::getInstance()->addEventHandler(
				'iblock',
				'OnAfterIBlockSectionDelete',
				array(
					'\Intervolga\Common\Tools\Log1c',
					'onAfterIBlockSectionDelete'
				)
			);
			Log1cTable::log();
			Log1c::copyFile();
		}
	}

	public static function onPageStart()
	{
		/**
		 * @see onDie()
		 */
		register_shutdown_function(
			array(
				__CLASS__,
				'onDie',
			)
		);
	}

	public static function onDie()
	{
		if (Log1c::is1cPage() && Log1c::isDebugMode())
		{
			Log1C::logResponse();
			Log1cTable::logEnd();
		}
	}

    public static function onAdminListDisplay(&$list)
    {
        // Добавление кнопки "Просмотр CSV файла"
        if ($list->table_id == "tbl_fileman_admin")
        {
            if(Loader::includeModule('fileman'))
            {
                foreach ($list->aRows as $row)
                {
                    $filePath = $row->arRes['ABS_PATH'];
                    $fileType = \CFileMan::GetFileTypeEx($filePath);
                    if($fileType == 'csv')
                    {
                        $link = CsvAdminViewer::getAdminPageName() . '?path=' . urlencode($filePath);
                        $insertedAction = array(
                            'open_csv' => array(
                                'ICON' => '',
                                'TEXT' => Loc::getMessage('INTERVOLGA_COMMON.SHOW_CSV_MENU_ITEM_NAME'),
                                'ACTION' =>
                                    "javascript:BX.adminPanel.Redirect([],'" . $link . "', event);"
                            )
                        );
                        array_splice($row->aActions, static::SHOW_CSV_MENU_ITEM_POS, 0, $insertedAction);
                    }
                }
            }
        }
    }

    /**
     * Обработчик события построения меню административной части - события "OnBuildGlobalMenu".
     * Добавляет страницу просмотрщика csv файлов в список url текущего открытого пункта меню "Файлы и папки".
     * Необходимо, чтобы фокус с пункта меню не слетал при переходе на страницу просмотрщика csv файлов.
     *
     * @param $aGlobalMenu
     * @param $aModuleMenu
     */
    public static function onBuildGlobalMenu(&$aGlobalMenu, &$aModuleMenu)
    {
        $request = Context::getCurrent()->getRequest();
        $path = $request->get('path');
        $requestUri = urldecode($request->getRequestUri());

        if($path && strpos($requestUri, CsvAdminViewer::getAdminPageName()) !== false)
        {
            $fileInfo = pathinfo($path);
            $dirName = $fileInfo['dirname'];

            // "Спускаемся" до пункта меню "Файлы и папки"
            foreach ($aModuleMenu as &$moduleMenu)
            {
                if($moduleMenu['section'] == 'fileman')
                {
                    foreach ($moduleMenu['items'] as &$filemanSubMenu)
                    {
                        $subMenuItemsId = $filemanSubMenu['items_id'];
                        $wantedSubMenuItemsId = 'menu_fileman_file';
                        if(substr($subMenuItemsId, 0, strlen($wantedSubMenuItemsId)) === $wantedSubMenuItemsId)
                        {
                            static::findDirectoryAndAddPageUrl($filemanSubMenu['items'], $dirName);
                        }
                    }

                }
            }
        }
    }

    /**
     * Найти текущую открытую директорию пункта меню "Файлы и папки" и добавить к списку url (more_url) текущего
     * пункта меню страницу просмотрщика csv файлов. Рекурсивная функция.
     *
     * @param array $rootDirectory корневая директория пункта меню "Файлы и папки".
     * @param string $searchDirectoryPath путь текущей открытой директории.
     */
    protected static function findDirectoryAndAddPageUrl(array &$rootDirectory, $searchDirectoryPath)
    {

        foreach ($rootDirectory as &$subDirectory)
        {
            $currentDirectoryPath = static::getCurrentDirectoryPath($subDirectory);
            if($currentDirectoryPath == $searchDirectoryPath)
            {
                static::addShowCsvPageUrl($subDirectory);
            }
            elseif(!empty($subDirectory['items']))
            {
                static::findDirectoryAndAddPageUrl($subDirectory['items'], $searchDirectoryPath);
            }
        }
    }

    /**
     * Получить путь текущей директории пункта меню "Файлы и папки".
     * Путь парсится из url пункта меню.
     *
     * @param array $directory директория пункта меню.
     * @return string путь текущей директории пункта меню.
     */
    protected static function getCurrentDirectoryPath(array $directory)
    {
        $path = '';
        $url = $directory['url'];

        if(!empty($url))
        {
            $queryParameters = array();

            $url = urldecode($url);
            $query = parse_url($url, PHP_URL_QUERY);
            parse_str($query, $queryParameters);
            $path = $queryParameters['path'];
        }

        return $path;
    }

    /**
     * Добавить текущую страницу к списку url (more_url) директории пункта меню "Файлы и папки".
     *
     * @param array $directory директория пункта меню "Файлы и папки".
     */
    protected static function addShowCsvPageUrl(array &$directory)
    {
        $request = Context::getCurrent()->getRequest();
        $path = $request->get('path');

        if(!empty($path))
        {
            $directory['more_url'][] = CsvAdminViewer::getAdminPageName() . '?path=' . urlencode($path);
        }
    }
}