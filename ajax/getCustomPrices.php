<?define("STATISTIC_SKIP_ACTIVITY_CHECK", "true");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Web\Json;
use Intervolga\Custom\Import\CustomPrices;
$error = '';
if (!check_bitrix_sessid()) {
	$error = 'Неверный sessid';
}
if (!$error
	&& trim($_POST['userXmlId'])
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
		$error = 'Произошла ошибка при получении цен';
	}
} else {
	$error = 'Неверные входные данные';
}
header("Content-type: application/json; charset=utf-8");
echo Json::encode(
	$error ? ['result' => 'error', 'data' => $error] : ['result' => 'ok', 'data' => $prices]
);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");