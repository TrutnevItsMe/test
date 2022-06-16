<?php

namespace Intervolga\Custom\Tools;

use Bitrix\Highloadblock as HL;

class HighloadblockUtil {

	static function getHLBlockIdByCode(string $code){

		$rs = HL\HighloadBlockTable::getList([
			"filter" => ["NAME" => $code],
			"select" => ["ID"]
		]);

		$HLId = $rs->fetch();

		if (isset($HLId["ID"])){
			$HLId = $HLId["ID"];
		}
		else{
			$HLId = null;
		}

		return $HLId;
	}

	static function getList($id, $arFilter = [], $arSelect = ["*", "UF_*"]){

		$hlBlock = HL\HighloadBlockTable::getById($id)->fetch();
		$entity = HL\HighloadBlockTable::compileEntity($hlBlock);
		$entityDataClass = $entity->getDataClass();

		return $entityDataClass::getList([
			"filter" => $arFilter,
			"select" => $arSelect
		]);
	}
}