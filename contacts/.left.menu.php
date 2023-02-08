<?

$shopTypes = \Intervolga\Custom\ORM\MagazinyPartnerovTable::getShopTypes();

// Формируем меню
$aMenuLinks = [];

//TODO: заменить GOROD на автоматическое определение после согласования с заказчиком

foreach ($shopTypes as $shopType)
{
	$aMenuLinks[] = [
		$shopType,
		"/contacts/?GOROD=".md5("Москва")."&TIPMAGAZINA=".md5($shopType),
		[],
		[],
		""
	];
}

?>