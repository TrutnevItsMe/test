<?

// Получаем ID ИБ бреендов

$res = CIBlock::GetList([],
	[
		"CODE" => "aspro_next_brands"
	]
);

// Получаем активные бренды
if ($brandIb = $res->GetNext())
{
	$res = CIBlockElement::GetList(
		[],
		[
			"IBLOCK_ID" => $brandIb["ID"],
			"ACTIVE" => "Y"
		],
		false,
		false,
		["CODE", "NAME"]
	);

	$arBrands = [];

	while ($brand = $res->GetNext())
	{
		$arBrands[] = $brand;
	}
}

// Формируем меню
$aMenuLinks = [];

$aMenuLinks[] = [
	"Наши бренды",
	"/brands/index.php",
	[],
	[],
	""
];

foreach ($arBrands as $arBrand)
{
	$aMenuLinks[] = [
		$arBrand["NAME"],
		"/brands/".$arBrand["CODE"]."/",
		[],
		[],
		""
	];
}

?>