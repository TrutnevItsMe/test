<?
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;
use Intervolga\Common\Admin\Log1CSearch;

$request = Application::getInstance()->getContext()->getRequest();

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');

if (Loader::includeModule('intervolga.common') && $USER->isAdmin())
{
	$APPLICATION->setTitle(Loc::getMessage('INTERVOLGA_COMMON.SEARCH_1C_PAGE'));
	?>
    <form method="POST" id="iv_search_form" action="javascript:void(null);" ENCTYPE="multipart/form-data" name="iv_search_form">
        <input type="hidden" name="replace_phrase" value="">
        <input type="hidden" name="subdir" value="1">
		<?=InputType("hidden", "file", '*.xml', '')?>
		<?=InputType("hidden", "dir", '/log/1c', '')?>

        <?
		$aTabs = array(
			array("DIV" => "search", "TAB" => Loc::getMessage('INTERVOLGA_COMMON.SEARCH_1C_PAGE')));

		$tabControl = new CAdminTabControl("tabControl", $aTabs, false, true);
		$tabControl->Begin();
		$tabControl->BeginNextTab();
        ?>
        <tr>
            <td><span class="required">*</span><?=Loc::getMessage('INTERVOLGA_COMMON.SEARCH_PHRASE_LABEL')?></td>
            <td><?=InputType("text", "phrase", '', '')?></td>
        </tr>
        <tr>
            <td>
                <input type="submit" name="start_search" id="start_search" value="<?=Loc::getMessage('INTERVOLGA_COMMON.SEARCH_START_LABEL')?>">
            </td>
            <td>
                <input type="button" id="stop_search" value="<?=Loc::getMessage('INTERVOLGA_COMMON.SEARCH_STOP_LABEL')?>" onclick="stopSearch()" disabled>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <div id="search_process">
                    <div class="process-text" style="display: none;">
                        <div class="bxfm-wait-1"></div>
                        <span id="search_process_label"><?=Loc::getMessage('INTERVOLGA_COMMON.SEARCH_PROGRESS_LABEL')?></span>
                    </div>
                    <div><strong><?=Loc::getMessage('INTERVOLGA_COMMON.SEARCH_RESULTS_LABEL')?>: <span id="search_results_count">0</span></strong></div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="bxfm-search-res" id="bxfm_search_res" style="display: block; width: 100%;">
                    <table>
                        <tbody id="iv-search-results">
                            <tr class="bxfm-s-res-head">
                                <td class="bxfm-h-type"></td>
                                <td class="bxfm-h-path">Имя файла</td>
                                <td class="bxfm-h-date">Дата изменения</td>
                                <td class="bxfm-h-size">Размер</td>
                                <td class="bxfm-h-search-cnt">Количество вхождений</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
        <?
		$tabControl->EndTab();
		$tabControl->Buttons();
		$tabControl->End();
		?>
    </form>
    <style>
        div.bxfm-wait-1 {
            display: block !important;
            background: url(/bitrix/themes/.default/images/wait.gif) no-repeat 0px 0 transparent;
            width: 20px;
            height: 20px;
            float: left;
            margin: -1px 3px 0 0;
        }
    </style>
    <script
            src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
            crossorigin="anonymous"></script>
    <div id="result_vars"></div>
    <script>
      var bitrixSessid = "<?=bitrix_sessid()?>";
      var searchResults = [];
      var ajaxInstance;
      $(document).on('submit', '#iv_search_form', function () {
        $('#search_process .process-text').show();
        searchResults = [];
        $('#search_results_count').html(0);
        $("#start_search").prop('disabled', true);
        $("#stop_search").prop('disabled', false);
        sendSearchRequest();
        return false;
      });

      function stopSearch()
      {
        if (typeof ajaxInstance != 'undefined')
        {
          ajaxInstance.abort();
        }
        $("#stop_search").prop('disabled', true);
        $("#start_search").prop('disabled', false);
        $('#search_process .process-text').hide();
      }

      //Метод отправки запроса поиск
      //Вызывает сам себя, пока не закончится поиск
      function sendSearchRequest(lastFile)
      {
        var data = $('#iv_search_form').serialize();
        if (typeof lastFile != 'undefined')
        {
          data += '&last_path=' + lastFile;
        }
        ajaxInstance = $.ajax({
          type: "POST",
          url: "/bitrix/admin/fileman_admin.php?lang=ru&fu_action=search&site=s1&fu_site=s1&sessid=" + bitrixSessid,
          data: data,
          success: function(msg){
            $('#result_vars').html(msg);
            if (window.fmsResult.length)
            {
              window.fmsResult.forEach(function(item, i) {
                searchResults.push(item);
              });
            }
            if (!window.fmsBstoped && window.fmsBtimeout)
            {
              sendSearchRequest(window.fmsLastPath);
            }
            else
            {
              processResult();
            }
            $('#search_results_count').html(searchResults.length);
          }
        });
      }

      //Выводит результаты поиска
      function processResult() {
        if (searchResults.length)
        {
          $('#iv-search-results').html('<tr class="bxfm-s-res-head">\n' +
            '                                <td class="bxfm-h-type"></td>\n' +
            '                                <td class="bxfm-h-path">Имя файла</td>\n' +
            '                                <td class="bxfm-h-date">Дата изменения</td>\n' +
            '                                <td class="bxfm-h-size">Размер</td>\n' +
            '                                <td class="bxfm-h-search-cnt">Количество вхождений</td>\n' +
            '                            </tr>');
          searchResults.forEach(function(item, i) {
            var fileRegexp = Array.from(item.path.matchAll(/.+\/(?<file>.+\.xml)$/gm));
            var fileName = item.path;
            if (Array.isArray(fileRegexp))
            {
              fileName = fileRegexp[0].groups.file;
            }
            $('#iv-search-results').append('<tr title="' + fileName + '">\n' +
              '                                <td><img src="' + item.type_src + '"></td>\n' +
              '                                <td style="text-align: left;">\n' +
              '                                    <a href="fileman_file_view.php?path=' + encodeURIComponent(item.path) + '&amp;lang=ru&amp;site=s1" target="_blank">import___c25308fa-6216-4ff0-8e91-5343e3ddf1e4.xml</a>\n' +
              '                                </td>\n' +
              '                                <td>' + item.str_date + '</td>\n' +
              '                                <td>' + item.str_size + '</td>\n' +
              '                                <td>' + item.repl_count + '</td>\n' +
              '                            </tr>');
          });
        }
        $('#search_process .process-text').hide();
        $("#stop_search").prop('disabled', true);
        $("#start_search").prop('disabled', false);
      }
    </script>

<?
}
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');