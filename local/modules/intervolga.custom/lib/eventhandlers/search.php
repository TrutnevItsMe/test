<?
namespace Intervolga\Custom\EventHandlers;

use Bitrix\Bizproc\Automation\Engine\DelayInterval;
use Intervolga\Common\Tools\EventHandler;

class Search extends EventHandler
{
	public static function BeforeIndex($arFields)
	{
		// Выбираем ID каталога
		\Bitrix\Main\Loader::includeModule('catalog');
		$rsCatalogIblock = \Bitrix\Catalog\CatalogIblockTable::getList([
			'filter' => ['PRODUCT_IBLOCK_ID' => 0]
		])->fetch();

		$CATALOG_IBLOCK_ID = $rsCatalogIblock["IBLOCK_ID"];

		if ($arFields["MODULE_ID"] == "iblock"
			&& $arFields["PARAM2"] == $CATALOG_IBLOCK_ID)
		{
			\Bitrix\Main\Loader::includeModule("iblock");

			if ($arFields["ITEM_ID"])
			{
				// Выбираем раздел товара
				$elementSection = \CIBlockElement::GetElementGroups($arFields["ITEM_ID"]);
				$elementSection = $elementSection->Fetch();

				$outOfProductionCode = "snyato_s_proizvodstva";
				$accessoriesForCodePart = "komplektuyushchie_dlya";

				// Исключаем из поиска товары из разделов "Комплектующие для" и "Снято с производства"
				if (strstr($elementSection["CODE"], $outOfProductionCode) !== false
				|| strstr($elementSection["CODE"], $accessoriesForCodePart) !== false)
				{
					$arFields = [];
					return $arFields;
				}
			}

			$db_props = \CIBlockElement::GetProperty
			(                                 // Запросим свойства индексируемого элемента
				$arFields["PARAM2"],         // BLOCK_ID индексируемого свойства
				$arFields["ITEM_ID"],       // ID индексируемого свойства
				["sort" => "asc"],         // Сортировка (можно упустить)
				["CODE"=>"CML2_ARTICLE"]  // CODE свойства (в данном случае артикул)
			);

			if($arProp = $db_props->Fetch())
			{
				// разобьем артикул по частям
				$splittedArticle = explode("-", $arProp["VALUE"]);

				for ($i = 0; $i < count($splittedArticle); ++$i)
				{
					// Объединяем по частям
					$sliceArticle= array_slice($splittedArticle, 0, $i);
					$arFields["TITLE"] .= " ".implode("-", $sliceArticle);
				}
			}
			return $arFields;
		}
	}
}