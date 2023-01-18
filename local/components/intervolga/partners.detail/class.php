<?php

use Intervolga\Custom\ORM\MagazinyPartnerovTable;
use Intervolga\Custom\ORM\PartneryTable;
use Intervolga\Custom\ORM\KontragentyTable;

class PartnerDetail extends CBitrixComponent
{
	public function executeComponent()
	{
		$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();

		$this->arResult = MagazinyPartnerovTable::getById($this->arParams["ID"]);
		$this->arResult["BACK_URL"] = $request->get("backurl")."?";

		foreach ($_GET as $key => $value)
		{
			if ($key == "backurl")
			{
				continue;
			}

			$this->arResult["BACK_URL"] .= "&$key=$value";
		}

		$this->arResult["PARTNER"] = [];
		$this->arResult["KONTRAGENTS"] = [];

		if ($this->arResult["UF_VLADELETS"])
		{
			$this->arResult["PARTNER"] = PartneryTable::getByXmlId($this->arResult["UF_VLADELETS"]);
			$this->arResult["KONTRAGENTS"] = KontragentyTable::getByPartner($this->arResult["UF_VLADELETS"]);
		}

		$this->includeComponentTemplate();
	}
}