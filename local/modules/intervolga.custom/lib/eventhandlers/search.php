<?
namespace Intervolga\Custom\EventHandlers;

use Intervolga\Common\Tools\EventHandler;

class Search extends EventHandler
{
	const CATALOG_IBLOCK_ID = 17;

	public static function BeforeIndex($arFields)
	{
		if ($arFields["MODULE_ID"] == "iblock"
			&& $arFields["PARAM2"] == self::CATALOG_IBLOCK_ID)
		{
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