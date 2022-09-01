<?php

use Bitrix\Main\PhoneNumber\Parser;
use Bitrix\Main\PhoneNumber\Format;
use Bitrix\Main\UserTable;
use Intervolga\Common\Highloadblock\HlbWrap;

class ManagersByUser extends CBitrixComponent{

	protected function fillResult(){

		global $USER;

		if ($USER){
			$rsUser = UserTable::GetByID($USER->GetID());
			$arUser = $rsUser->fetch();
			$HlBlock = new HlbWrap(HL_BLOCK_CODE_PARTNERY);
			$dbPartnery = $HlBlock->getList(["filter" => ["UF_XML_ID" => $arUser["XML_ID"]]]);

			while ($partner = $dbPartnery->fetch()){
				$partner["DISPLAY_OSNMENEDZHERTELEF"] = Parser::getInstance()->parse($partner["UF_OSNMENEDZHERTELEF"])->format(Format::NATIONAL);
				$partner["DISPLAY_POMOSHNIKTELEFON1"] = Parser::getInstance()->parse($partner["UF_POMOSHNIKTELEFON1"])->format(Format::NATIONAL);
				$partner["DISPLAY_POMOSHNIKTELEFON2"] = Parser::getInstance()->parse($partner["UF_POMOSHNIKTELEFON2"])->format(Format::NATIONAL);
				$partner["DISPLAY_POMOSHNIKTELEFON3"] = Parser::getInstance()->parse($partner["UF_POMOSHNIKTELEFON3"])->format(Format::NATIONAL);
				$partner["DISPLAY_OSNMENEDZHERRABTE"] = Parser::getInstance()->parse($partner["UF_OSNMENEDZHERRABTE"])->format(Format::NATIONAL);
				$partner["DISPLAY_POMRABTELEFON1"] = Parser::getInstance()->parse($partner["UF_POMRABTELEFON1"])->format(Format::NATIONAL);
				$partner["DISPLAY_POMRABTELEFON2"] = Parser::getInstance()->parse($partner["UF_POMRABTELEFON2"])->format(Format::NATIONAL);
				$partner["DISPLAY_POMRABTELEFON3"] = Parser::getInstance()->parse($partner["UF_POMRABTELEFON3"])->format(Format::NATIONAL);
				$this->arResult["PARTNERS"][] = $partner;
			}
		}
	}

	public function executeComponent() {

		self::fillResult();
		$this->includeComponentTemplate();
	}
}