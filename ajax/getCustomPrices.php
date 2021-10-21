<?define("STATISTIC_SKIP_ACTIVITY_CHECK", "true");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Web\Json;
use Intervolga\Custom\Import\CustomPrices;

$prices = CustomPrices::get(
	$_POST['userXmlId'],
	$_POST['counterpartyXmlId'],
	$_POST['agreementXmlId'],
	$_POST['basket']
);
CustomPrices::set($prices, $_POST['basket']);
header('Content-type: application/json');
echo Json::encode(['result' => 'ok', 'data' => $prices]);


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");