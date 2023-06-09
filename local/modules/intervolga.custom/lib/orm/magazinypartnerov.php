<?php

namespace Intervolga\Custom\ORM;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

class MagazinyPartnerovTable extends DataManager
{
	public static function getTableName()
	{
		return "b_abmagazinypartnerov";
	}

	public static function getMap()
	{
		return [
			"ID" => [
				"data_type" => "integer",
				"primary" => true,
				"autocomplete" => true,
			],
			"UF_VLADELETS" => [
				"data_type" => "string",
				"title" => Loc::getMessage("VLADELETS")
			],
			"UF_NAME" => [
				"data_type" => "string",
				"title" => Loc::getMessage("NAME")
			],
			"UF_XML_ID" => [
				"data_type" => "string",
				"title" => Loc::getMessage("XML_ID")
			],
			"UF_KOD" => [
				"data_type" => "string",
				"title" => Loc::getMessage("KOD")
			],
			"UF_METRO" => [
				"data_type" => "string",
				"title" => Loc::getMessage("METRO")
			],
			"UF_KOORDINATY" => [
				"data_type" => "string",
				"title" => Loc::getMessage("KOORDINATY")
			],
			"UF_VREMYARABOTY" => [
				"data_type" => "string",
				"title" => Loc::getMessage("VREMYARABOTY")
			],
			"UF_GOROD" => [
				"data_type" => "string",
				"title" => Loc::getMessage("GOROD")
			],
			"UF_RODITEL" => [
				"data_type" => "string",
				"title" => Loc::getMessage("RODITEL")
			],
			"UF_DESCRIPTION" => [
				"data_type" => "string",
				"title" => Loc::getMessage("DESCRIPTION")
			],
			"UF_VERSION" => [
				"data_type" => "string",
				"title" => Loc::getMessage("VERSION")
			],
			"UF_PHOTOS" => [
				'data_type' => "integer",
				'validation' => function() {
					return array(
						function ($value) {
							/*Валидация - файл может быть только картинкой*/
							if(intval($value)>0){
								$rsFile = \CFile::GetByID($value);
								if($arFile = $rsFile->Fetch()){
									if (!\CFile::IsImage($arFile["FILE_NAME"], $arFile["CONTENT_TYPE"])) {
										return Loc::getMessage("FILE_ERROR");
									}
								}
							}
							return true;
						}
					);
				}
			],
			"UF_TIPMAGAZINA" => [
				"data_type" => "string",
				"title" => Loc::getMessage("TIPMAGAZINA")
			],
			"UF_TELEFON" => [
				"data_type" => "string",
				"title" => Loc::getMessage("TELEFON")
			],
			"UF_ADRES" => [
				"data_type" => "string",
				"title" => Loc::getMessage("ADRES")
			],
		];
	}

	public static function getById($id)
	{
		$rs = static::getList([
			"select" => ["*", "UF_*"],
			"filter" => [
				"=ID" => $id
			]
		]);

		return $rs->fetch();
	}

	public static function getShopTypes()
	{
		$rs = static::getList([
			"select" => ["UF_TIPMAGAZINA"],
			"group" => ["UF_TIPMAGAZINA"]
		]);

		$typeShops = [];

		while ($typeShop = $rs->fetch())
		{
			if ($typeShop["UF_TIPMAGAZINA"])
			{
				$typeShops[] = $typeShop["UF_TIPMAGAZINA"];
			}
		}

		return $typeShops;
	}
}