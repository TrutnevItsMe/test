<?define("STATISTIC_SKIP_ACTIVITY_CHECK", "true");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Web\Json;
use Intervolga\Custom\Import\CustomPrices;
$error = [];
header("Content-type: application/json; charset=utf-8");
\Bitrix\Main\Diag\Debug::writeToFile(__FILE__ . ':' . __LINE__ . "\n(" . date('Y-m-d H:i:s') . ")\n" . print_r($_POST, true) . "\n\n", '', 'log/__debug_erofeev.log');
if (!check_bitrix_sessid()) {
	$error = ['errorText' => 'Неверный sessid'];
} else {
	if ( trim($_POST['userXmlId'])
		&& trim($_POST['counterpartyXmlId'])
		&& trim($_POST['agreementXmlId'])
		&& is_array($_POST['basket'])
	) {
		try {
			$prices = CustomPrices::get(
				$_POST['userXmlId'],
				$_POST['counterpartyXmlId'],
				$_POST['agreementXmlId'],
				$_POST['basket']
			);
			CustomPrices::set($prices, $_POST['basket']);
		} catch (Error $err) {
			// Произошла ошибка исполнения
			$error = [
				'errorText' => 'Произошла ошибка при получении цен',
				'message' => $err->getMessage()
			];
		}
	} else {
		$error = ['errorText' => 'Неверные входные данные', 'post' => $_POST];
	}
}
\Bitrix\Main\Diag\Debug::writeToFile(__FILE__ . ':' . __LINE__ . "\n(" . date('Y-m-d H:i:s') . ")\n" . print_r($error ? ['result' => 'error', 'data' => $error] : ['result' => 'ok', 'data' => $prices], true) . "\n\n", '', 'log/__debug_erofeev.log');
echo Json::encode(
	$error ? ['result' => 'error', 'data' => $error] : ['result' => 'ok', 'data' => $prices]
);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");