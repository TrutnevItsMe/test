<?php

use Bitrix\Main\PhoneNumber\Parser;
use Bitrix\Main\PhoneNumber\Format;
use Bitrix\Main\UserTable;
use Intervolga\Common\Highloadblock\HlbWrap;

class ManagersByUser extends CBitrixComponent{

	protected function fillArResult(){

		global $USER;

		if ($USER){
			$rsUser = UserTable::GetByID($USER->GetID());
			$arUser = $rsUser->fetch();
			$HlBlock = new HlbWrap(HL_BLOCK_CODE_PARTNERY);
			$dbPartnery = $HlBlock->getList(["filter" => ["UF_XML_ID" => $arUser["XML_ID"]]]);

			$partner = $dbPartnery->fetch();
			$this->arResult["PARTNER"] = $partner;

			$this->arResult["PARTNER"]["DISPLAY_OSNMENEDZHERTELEF"] = Parser::getInstance()->parse($partner["UF_OSNMENEDZHERTELEF"])->format(Format::NATIONAL);
			$this->arResult["PARTNER"]["DISPLAY_POMOSHNIKTELEFON1"] = Parser::getInstance()->parse($partner["UF_POMOSHNIKTELEFON1"])->format(Format::NATIONAL);
		}
	}

	public function executeComponent() {

		self::fillArResult();
		$this->includeComponentTemplate();
	}
}