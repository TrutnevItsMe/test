<?
use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Intervolga\Common\Tools\CsvAdminViewer;

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/fileman/prolog.php");

global $USER, $APPLICATION, $adminChain;
if (!($USER->CanDoOperation('fileman_admin_files')
	|| $USER->CanDoOperation('fileman_view_file_structure'))
)
{
	$APPLICATION->AuthForm(Loc::getMessage('INTERVOLGA_COMMON.ACCESS_DENIED'));
}

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/fileman/include.php");

define('ADMIN_MODULE_NAME', 'intervolga.common');
$APPLICATION->SetTitle(Loc::getMessage('INTERVOLGA_COMMON.PAGE_TITLE'));

if (Loader::includeModule('intervolga.common'))
{
	try
	{
		$request = Context::getCurrent()->getRequest();
		$isFirstStringHeader = $request->get('is_first_string_header');
		$isFirstStringHeader = (empty($isFirstStringHeader) || $isFirstStringHeader == 'Y') ? true : false;

		$csvAdminViewer = new CsvAdminViewer($path, $isFirstStringHeader);
		$fileInfo = $csvAdminViewer->getFileInfo();
	}
	catch (\Bitrix\Main\IO\IoException $exception)
	{
		$strWarning = Loc::getMessage('INTERVOLGA_COMMON.INCORRECT_FILE_TYPE');
	}

	if (empty($strWarning) && !empty($fileInfo))
	{
		$APPLICATION->SetTitle(
			Loc::getMessage('INTERVOLGA_COMMON.PAGE_TITLE')
			. ' "'
			. $fileInfo['PARSED_PATH']['LAST']
			. '"'
		);

		// Хлебные крошки
		foreach ($fileInfo['PARSED_PATH']["AR_PATH"] as $chainLevel)
		{
			$adminChain->AddItem(
				array(
					"TEXT" => htmlspecialcharsex($chainLevel["TITLE"]),
					"LINK" => ((strlen($chainLevel["LINK"]) > 0) ? $chainLevel["LINK"] : ""),
				)
			);
		}

		// Проверка прав доступа на файл
		$io = CBXVirtualIo::GetInstance();
		if (!$USER->CanDoFileOperation('fm_view_file', $fileInfo['AR_PATH']))
		{
			$strWarning = Loc::getMessage('INTERVOLGA_COMMON.ACCESS_DENIED');
		}
		else
		{
			if (!$io->FileExists($fileInfo['ABS_PATH']))
			{
				$strWarning = Loc::getMessage('INTERVOLGA_COMMON.FILE_NOT_FOUND');
			}
		}

		if (empty($strWarning) && !empty($csvAdminViewer))
		{
			try
			{
				$csvAdminViewer->init();
			}
			catch (\Bitrix\Main\IO\IoException $exception)
			{
				$strWarning = Loc::getMessage('INTERVOLGA_COMMON.FILE_NOT_READ');
			}
		}
	}
}
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');

CAdminMessage::ShowMessage($strWarning);

if (Loader::includeModule('intervolga.common') && empty($strWarning) && !empty($csvAdminViewer))
{
	$csvAdminViewer->show();
}

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');