<? namespace Intervolga\Common\Components;

class SectionList
{
	/**
	 * Turns flat section items array into nested array.
	 *
	 * Can be used with bitrix:section.list component.
	 *
	 * @param array $sections sections array
	 * @param bool $addSelected true to add SELECTED=Y for selected item's parents
	 *
	 * @return array nested sections array
	 */
	public static function reorganizeSections(array $sections, $addSelected = true)
	{
		// Ключи массива - ID разделов
		$arNewSections = array();
		foreach ($sections as $section)
			$arNewSections[$section["ID"]] = $section;
		$sections = $arNewSections;

		// Определить максимальную глубину вложенности разделов
		$maxDepth = 0;
		foreach ($sections as $section)
		{
			if ($section["DEPTH_LEVEL"] > $maxDepth)
			{
				$maxDepth = $section["DEPTH_LEVEL"];
			}
		}
		// Сворачивать более глубокие секции в своих родителей
		for ($i = $maxDepth; $i > 1; $i--)
		{
			// Свернуть разделы уровня $i в разделы уровня $i-1
			foreach ($sections as $k => $section)
			{
				if ($section["DEPTH_LEVEL"] == $i && $section["IBLOCK_SECTION_ID"])
				{
					$sections[$section["IBLOCK_SECTION_ID"]]["SECTIONS"][$section["ID"]] = $section;
					if ($addSelected && $section["SELECTED"])
					{
						$sections[$section["IBLOCK_SECTION_ID"]]["SELECTED"] = "Y";
					}
					unset($sections[$k]);
				}
			}
		}

		return $sections;
	}
}