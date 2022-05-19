<?php

namespace Intervolga\Custom\EventHandlers;

use Intervolga\Common\Tools\EventHandler;
use Intervolga\Custom\Import\Sets;

class Form extends EventHandler {

	const REGISTER_WEB_FORM_ID = 10;

	public static function onAfterResultAdd($WEB_FORM_ID, $RESULT_ID){

		if (\Bitrix\Main\Loader::IncludeModule("form") && $WEB_FORM_ID == static::REGISTER_WEB_FORM_ID){

			$arResult = [];
			$arAnswer2 = [];

			$webFormResult = $arAnswer = \CFormResult::GetDataByID(
				$RESULT_ID,
				array(),
				$arResult,
				$arAnswer2);

			$managerList = \CUser::GetList(
				$by = "ID",
				 $order = "asc",
				$filter = array(
					"GROUPS_ID" => [MANAGER_GROUP_ID],
					"ACTIVE" => "Y"
				));

			$arManagersEmail = [];

			while ($manager = $managerList->GetNext()){
				$arManagersEmail[] = $manager["EMAIL"];
			}

			if ($webFormResult["SEX"][0]["ANSWER_VALUE"] == "MALE"){
				$sex = "мужской";
			}
			elseif ($webFormResult["SEX"][0]["ANSWER_VALUE"] == "FEMALE"){
				$sex = "женский";
			}

			$arEventFields = [
				"MANAGER_EMAIL" => implode(',', $arManagersEmail),
				"ANSWER_SURNAME" => $webFormResult["SURNAME"][0]["USER_TEXT"],
				"ANSWER_NAME" => $webFormResult["NAME"][0]["USER_TEXT"],
				"ANSWER_PATRONYMIC" => $webFormResult["PATRONYMIC"][0]["USER_TEXT"],
				"ANSWER_EMAIL" => $webFormResult["EMAIL"][0]["USER_TEXT"],
				"ANSWER_LOGIN" => $webFormResult["LOGIN"][0]["USER_TEXT"],
				"ANSWER_PASSWORD" => md5($webFormResult["PASSWORD"][0]["USER_TEXT"]),
				"ANSWER_PHONE" => $webFormResult["PHONE"][0]["USER_TEXT"],
				"ANSWER_BIRTHDATE" => $webFormResult["BIRTHDATE"][0]["USER_DATE"],
				"ANSWER_SEX" => $sex
			];

			\CEvent::Send("REGISTER_FORM_COMPLETED",
			"s1",
				$arEventFields
			);
		}
	}
}