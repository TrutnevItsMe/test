
<?php

foreach ($arResult['SECTIONS'] as $i => $arSection)
{
	if ($arSection["CODE"] == "snyato_s_proizvodstva"
	|| strpos($arSection["CODE"], "komplektuyushchie_dlya") !== false)
	{
		unset($arResult['SECTIONS'][$i]);
	}
}