<?php

use Intervolga\Custom\ORM\MagazinyPartnerovTable;
use Intervolga\Custom\ORM\PartneryTable;
use Intervolga\Custom\ORM\KontragentyTable;

class PartnerDetail extends CBitrixComponent
{
	public function executeComponent()
	{
		if (!isset($this->arParams["DURATION"])
		|| !is_numeric($this->arParams["DURATION"])
		|| $this->arParams["DURATION"] < 0)
		{
			$this->arParams["DURATION"] = 1000;
		}

		if (!isset($this->arParams["INTERVAL_DURATION"])
			|| !is_numeric($this->arParams["INTERVAL_DURATION"])
			|| $this->arParams["INTERVAL_DURATION"] < 0)
		{
			$this->arParams["INTERVAL_DURATION"] = 2000;
		}

		$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();

		$this->arResult = MagazinyPartnerovTable::getById($this->arParams["ID"]);

		if ($this->arResult["UF_PHOTOS"])
		{
			$arImageIds = unserialize($this->arResult["UF_PHOTOS"]);

			foreach ($arImageIds as $imageId)
			{
				$this->arResult["PHOTOS"][] = \CFile::GetByID($imageId)->Fetch();
			}
		}

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