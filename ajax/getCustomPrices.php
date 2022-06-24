<?define("STATISTIC_SKIP_ACTIVITY_CHECK", "true");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Web\Json;
use Intervolga\Custom\Import\CustomPrices;
$error = [];
header("Content-type: application/json; charset=utf-8");
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
			/* TODO: DELETE
			\Bitrix\Main\Diag\Debug::dumpToFile($prices, $varName = '', $fileName = 'log.txt');
			*/
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
echo Json::encode(
	$error ? ['result' => 'error', 'data' => $error] : ['result' => 'ok', 'data' => $prices]
);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");