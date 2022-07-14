<?php

namespace Intervolga\Custom\Tools;

use Bitrix\Main\Application;

class SaleUtil {

	static function getStatusIdByXml(string $xmlId){

		$lang = Application::getInstance()->getContext()->getLanguage();
		$dbStatus = \CSaleStatus::GetList([],
			["LID" => $lang,
			 "XML_ID" => $xmlId],
			false,
			false,
			["ID"]);
		$arStatus = $dbStatus->GetNext();

		if ($arStatus != null){
			return $arStatus["ID"];
		}
		else{
			return null;
		}
	}
}