<? B_PROLOG_INCLUDED === true || die();
/**
 * @var string $mid module id from GET
 */
use Bitrix\Main\Config\Configuration;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Intervolga\Common\Agents\Log1CAgent;
use Intervolga\Common\Tools\CatchFormCrm;
use Intervolga\Common\Tools\Log1c;
use Intervolga\Common\Tools\EventAutoLoader;
use Intervolga\Common\Tools\Orm\Log1cTable;
use Bitrix\Main\Config\Option;
CJSCore::Init(array("jquery","date"));
Loc::loadMessages(__FILE__);

global $APPLICATION, $USER;

$module_id = "intervolga.common";
Loader::includeModule($module_id);
$handling = Configuration::getValue("exception_handling");
$isLog = $handling["log"]["class_name"] == "\\Intervolga\\Common\\Tools\\ExceptionHandler";
$log1cPeriod = '-';
$logsSize = \CFile::FormatSize(Log1CAgent::getLogRootSize(), 2);
$timeoutMessage = '';
if (Intervolga\Common\Agents\Log1CAgent::isTimeoutRootSize())
{
    $timeoutMessage = Loc::getMessage("INTERVOLGA_COMMON.TIMEOUT_MESSAGE");
}
if ($logFirstDate = Log1cTable::getFirstDate())
{
    $log1cPeriod = Loc::getMessage(
        "INTERVOLGA_COMMON.DATES_FROM_TO",
        array(
            '#FROM#' => Log1cTable::getFirstDate()->format('d.m.Y H:i'),
            '#TO#' => Log1cTable::getLastDate()->format('d.m.Y H:i'),
        )
    );
}

CatchFormCrm::checkOption();

$options = array(
    "general" => array(
        Loc::getMessage("INTERVOLGA_COMMON.GENERAL"),
        array("is_prod", Loc::getMessage("INTERVOLGA_COMMON.IS_PROD"), "", array("checkbox")),
        array("note" => Loc::getMessage("INTERVOLGA_COMMON.IS_PROD_NOTE")),
        array("autoload_custom_events", Loc::getMessage("INTERVOLGA_COMMON.AUTOLOAD_CUSTOM_EVENTS"), "", array("checkbox")),
        array("note" => Loc::getMessage("INTERVOLGA_COMMON.AUTOLOAD_NOTE",
            array("#URL#" => "/bitrix/admin/fileman_admin.php?lang=" . LANGUAGE_ID . "&path=" . EventAutoLoader::DEFAULT_PATH, "#PATH#" => EventAutoLoader::DEFAULT_PATH))),
        Loc::getMessage("INTERVOLGA_COMMON.DEBUG"),
        array("catch_form_crm", Loc::getMessage("INTERVOLGA_COMMON.CATCH_FORM_CRM"), "", array("checkbox"), Loader::includeModule('form') ? '' : 'Y'),
        array("catch_all_emails", Loc::getMessage("INTERVOLGA_COMMON.CATCH_ALL_EMAILS"), "", array("checkbox")),
        array("redirect_emails", Loc::getMessage("INTERVOLGA_COMMON.REDIRECT_EMAILS"), "", array("text", 80)),
        array("note" => Loc::getMessage("INTERVOLGA_COMMON.CATCH_ALL_EMAILS_NOTE")),
        array("log_rotate_days", Loc::getMessage("INTERVOLGA_COMMON.LOG_ROTATE_DAYS"), "", array("text", 5)),
        array("note" => Loc::getMessage("INTERVOLGA_COMMON.LOG_ROTATE_DAYS_NOTE", array(
            "#PATH#" => "/log/",
            "#URL#" => "/bitrix/admin/fileman_admin.php?path=/log/&lang=" . LANGUAGE_ID,
        ))),
        array(
			"",
			Loc::getMessage("INTERVOLGA_COMMON.LOG_EXCEPTIONS"),
			$isLog ? Loc::getMessage("INTERVOLGA_COMMON.YES") : Loc::getMessage("INTERVOLGA_COMMON.NO"),
			array("statichtml"),
		),
        array("note" => Loc::getMessage("INTERVOLGA_COMMON.LOG_EXCEPTIONS_NOTE", array(
                "#CLASS_NAME#" => "\\Intervolga\\Common\\Tools\\ExceptionHandler",
                "#PATH#" => "exception_handling&gt;value&gt;log&gt;class_name",
                "#HREF#" => "/bitrix/admin/fileman_file_edit.php?path=/bitrix/.settings.php&full_src=Y&lang=" . LANGUAGE_ID,
            )
        )),
        array('log_inclusion_type',Loc::getMessage('INTERVOLGA_COMMON.DEBUG_LOG_INCLUSION_TYPE'),'enable',array('selectbox',array(
            'enable'=>Loc::getMessage('INTERVOLGA_COMMON.DEBUG_LOG_ENABLE'),
            'disable'=>Loc::getMessage('INTERVOLGA_COMMON.DEBUG_LOG_DISABLE'),
            'enable_without'=>Loc::getMessage('INTERVOLGA_COMMON.DEBUG_LOG_ENABLE_WITHOUT'),
            'enable_until'=>Loc::getMessage('INTERVOLGA_COMMON.DEBUG_LOG_ENABLE_UNTIL'),
        ))),
        array('log_files_exclude',	"Исключить следующие файлы:",Loc::getMessage('INTERVOLGA_COMMON.DEBUG_LOG_ENABLE_WITHOUT_TEXTAREA_PLACEHOLDER'),array('textarea',	10,	50)),
        array('log_until_time')
    ),
    "1c_exchange" => array(
        Loc::getMessage("INTERVOLGA_COMMON.DEBUG"),
        array(
            "debug_1c",
            Loc::getMessage("INTERVOLGA_COMMON.DEBUG_1C"),
            "",
            array("checkbox"),
        ),
        array(
            "note" => Loc::getMessage(
                "INTERVOLGA_COMMON.DEBUG_1C_NOTE",
                array(
                    "#PATH#" => Log1c::LOGS_PATH,
                    "#URL#" => "/bitrix/admin/fileman_admin.php?lang=" . LANGUAGE_ID . "&path=" . Log1c::LOGS_PATH,
                )
            ),
        ),
        array(
            '',
            Loc::getMessage(
                "INTERVOLGA_COMMON.DEBUG_1C_SIZE",
                array(
                    '#PATH#' => Log1c::LOGS_PATH,
                )
            ),
            $timeoutMessage . $logsSize,
            array('statichtml'),
        ),
        array(
            '',
            Loc::getMessage("INTERVOLGA_COMMON.DEBUG_1C_LAST_EXCHANGE"),
            $log1cPeriod,
            array('statichtml'),
        ),
        array(
            'max_size_folder_1c_exchange',
            Loc::getMessage(
                "INTERVOLGA_COMMON.DEBUG_1C_MAX_SIZE",
                array(
                    '#PATH#' => Log1c::LOGS_PATH,
                )
            ),
            '',
            array('text', 20),
        ),
        array(
            'log_1c_period',
            Loc::getMessage(
                "INTERVOLGA_COMMON.LOG_1C_PERIOD",
                array(
                    '#URL#' => '/bitrix/admin/intervolga.common_log1c.php?lang=' . LANGUAGE_ID,
                )
            ),
            '',
            array('text', 5),
        ),
    ),
);
$tabs = array(
    array(
        "DIV" => "general",
        "TAB" => Loc::getMessage("INTERVOLGA_COMMON.TAB_GENERAL"),
        "TITLE" => Loc::getMessage("INTERVOLGA_COMMON.TAB_GENERAL"),
    ), array(
        "DIV" => "1c_exchange",
        "TAB" => Loc::getMessage("INTERVOLGA_COMMON.1C_EXCHANGE"),
        "TITLE" => Loc::getMessage("INTERVOLGA_COMMON.1C_EXCHANGE"),
    ),
);
if ($USER->IsAdmin())
{
    if (check_bitrix_sessid() && strlen($_POST["save"]) > 0)
    {
        foreach ($options as $option)
        {
            __AdmSettingsSaveOptions($module_id, $option);
        }
        LocalRedirect($APPLICATION->GetCurPageParam());
    }
}
$logUntilTime=Option::get('intervolga.common', 'log_until_time');
$tabControl = new CAdminTabControl("tabControl", $tabs);
$tabControl->Begin();
?>
<form method="POST"
      action="<? echo $APPLICATION->GetCurPage() ?>?mid=<?= htmlspecialcharsbx($mid) ?>&lang=<?= LANGUAGE_ID ?>">
    <?echo bitrix_sessid_post()?>
    <? $tabControl->BeginNextTab(); ?>
    <? __AdmSettingsDrawList($module_id, $options["general"]); ?>
    <td class="adm-detail-valign-top" width="50%"><?=Loc::getMessage('INTERVOLGA_COMMON.DEBUG_LOG_ENABLE_UNTIL_LABEL')?><a name="opt_log_until_time"></a></td>
    <td width="50%"><input type="text" value="<?=$logUntilTime?>" id="log_until_time" name="log_until_time" onclick="BX.calendar({node: this, field: this, bTime: true});"></td>
    <? $tabControl->BeginNextTab(); ?>
    <? __AdmSettingsDrawList($module_id, $options["1c_exchange"]); ?>
    <? $tabControl->Buttons(array("btnApply" => false, "btnCancel" => false, "btnSaveAndAdd" => false)); ?>
    <?=bitrix_sessid_post();?>
    <? $tabControl->End(); ?>
</form>
<script>
    /*
    * Функция определяет видимость полей связанных с пунктом настроек - в меню опций "Создание csv-логов"
    * */
    BX.ready(function()
    {
        var select=document.getElementsByName('log_inclusion_type')[0];
        window.onload=toogleMode(select);
        BX.bind(select, 'change', BX.delegate(toogleMode,this));

        function toogleMode(e){

            var newMode='';
            if(e.target) {
                newMode = e.target.value;
            }
            else
                newMode = e.value;

            var textArea=BX.findParent(document.getElementsByName('log_files_exclude')[0],{tagName:'tr'});
            var dateTime=BX.findParent(BX('log_until_time'),{tagName:'tr'});

            switch (newMode) {
                case 'enable':
                case 'disable':
                    BX.hide(textArea);
                    BX.hide(dateTime);
                    break;
                case 'enable_without':
                    BX.show(textArea);
                    BX.hide(dateTime);
                    break;
                case 'enable_until':
                    BX.hide(textArea);
                    BX.show(dateTime);
            }
        }
    });
</script>