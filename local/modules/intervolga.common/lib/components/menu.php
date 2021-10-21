<?namespace Intervolga\Common\Components;

class Menu
{
	/**
	 * Turns flat menu items array into nested array.
	 *
	 * Can be used with bitrix:menu component.
	 *
	 * @param array $menuItems menu items
	 * @param bool $addSelected true to add SELECTED=Y for selected item's parents
	 *
	 * @return array nested menu items array
	 */
	public static function reorganizeItems(array $menuItems, $addSelected = true)
	{
		$maxDepth = 0;
		foreach ($menuItems as $menuItem)
		{
			if ($menuItem["DEPTH_LEVEL"] > $maxDepth)
			{
				$maxDepth = $menuItem["DEPTH_LEVEL"];
			}
		}

		for ($i = $maxDepth; $i > 1; $i--)
		{
			$lastParent = 0;
			foreach ($menuItems as $k => $menuItem)
			{
				if ($menuItem["DEPTH_LEVEL"] == $i - 1)
				{
					$lastParent = $k;
				}
				elseif ($menuItem["DEPTH_LEVEL"] == $i)
				{
					$menuItems[$lastParent]["ITEMS"][] = $menuItem;
					if ($addSelected && $menuItem["SELECTED"])
					{
						$menuItems[$lastParent]["SELECTED"] = "Y";
					}
					unset($menuItems[$k]);
				}
			}
		}

		return $menuItems;
	}
}