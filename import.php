<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
global $APPLICATION, $USER;
$APPLICATION->SetTitle("Скрипт запуска импорта/экспорта");

if (!$USER->IsAdmin())
{
	LocalRedirect(SITE_DIR);
}
else
{
	$root = \Bitrix\Main\Application::getDocumentRoot();
	\Bitrix\Main\Config\Option::set("catalog", "DEFAULT_SKIP_SOURCE_CHECK", "Y");
	\Bitrix\Main\Config\Option::set("sale", "secure_1c_exchange", "N");
	
	$highloadFiles = array();
	foreach (scandir($root . "/upload/1c_highloadblock/") as $file)
	{
		if ($file{0} == "." || $file{0} == "..")
		{
			continue;
		}
		$highloadFiles[] = $file;
	}

	$catalogFiles = array();
	foreach (scandir($root . "/upload/1c_catalog/") as $file)
	{
		if ($file{0} == "." || $file{0} == "..")
		{
			continue;
		}
		$catalogFiles[] = $file;
	}

	$saleFiles = array();
	foreach (scandir($root . "/upload/1c_exchange/") as $file)
	{
		if ($file{0} == "." || $file{0} == "..")
		{
			continue;
		}
		$saleFiles[] = $file;
	}

	// Stop progress
	unset($_SESSION["BX_HL_IMPORT"]);
	unset($_SESSION["BX_CML2_IMPORT"]);
	unset($_SESSION["BX_CML2_EXPORT"]);

	$script = "/bitrix/admin/1c_exchange.php";
}
?>
	<div class="alert alert-info">Для импорта будет использован файл <b><?=$script?></b></div>
<? if ($highloadFiles): ?>
	<h1>Импорт highload-блоков</h1>
	<ul>
		<? foreach ($highloadFiles as $highloadImportFile): ?>
			<li>
				<a href="<?=$script?>?type=reference&mode=import&filename=<?=urlencode($highloadImportFile)?>&sessid=<?=bitrix_sessid()?>" target="_blank">
					Импорт <b><?=$highloadImportFile?></b>
				</a>
				(<a target="_blank" href="/bitrix/admin/fileman_file_view.php?site=<?=SITE_ID?>&path=<?=urlencode("/upload/1c_highloadblock/".$highloadImportFile)?>">Просмотр</a>)
			</li>
		<? endforeach ?>
	</ul>
<? endif ?>
<? if ($catalogFiles): ?>
	<h1>Импорт Торгового каталога</h1>
	<ul>
		<? foreach ($catalogFiles as $catalogImportFile): ?>
			<li>
				<a href="<?=$script?>?type=catalog&mode=import&filename=<?=urlencode($catalogImportFile)?>&sessid=<?=bitrix_sessid()?>" target="_blank">
					Импорт <b><?=$catalogImportFile?></b>
				</a>
				(<a target="_blank" href="/bitrix/admin/fileman_file_view.php?site=<?=SITE_ID?>&path=<?=urlencode("/upload/1c_catalog/".$catalogImportFile)?>">Просмотр</a>)
			</li>
		<? endforeach ?>
	</ul>
<? endif ?>
<? if ($saleFiles): ?>
	<h1>Импорт заказов</h1>
	<ul>
		<? foreach ($saleFiles as $saleImportFile): ?>
			<li>
				<a href="<?=$script?>?type=sale&mode=import&filename=<?=urlencode($saleImportFile)?>&sessid=<?=bitrix_sessid()?>" target="_blank">
					Импорт <b><?=$saleImportFile?></b>
				</a>
				(<a target="_blank" href="/bitrix/admin/fileman_file_view.php?site=<?=SITE_ID?>&path=<?=urlencode("/upload/1c_exchange/".$saleImportFile)?>">Просмотр</a>)
			</li>
		<? endforeach ?>
	</ul>
<? endif ?>
	<h1>Выгрузки</h1>
	<ul>
		<li>
			<a href="<?=$script?>?type=sale&mode=query" target="_blank">Выгрузка заказов</a>
		</li>
		<li>
			<a href="<?=$script?>?type=sale&mode=info&sessid=<?=bitrix_sessid()?>" target="_blank">
				Выгрузка справочника статусов
			</a>
		</li>
	</ul>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>