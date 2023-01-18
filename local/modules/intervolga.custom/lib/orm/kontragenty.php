<?php

namespace Intervolga\Custom\ORM;

use \Bitrix\Main\Entity\DataManager;

class KontragentyTable extends DataManager
{
	public static function getTableName()
	{
		return "b_kontragenty";
	}

	public static function getMap()
	{
		return [
			"ID" => [
				"data_type" => "integer",
				"primary" => true,
				"autocomplete" => true,
			],
			"UF_ADRESADOSTAVKI1" => [
				"data_type" => "string",
				"title" => "UF_ADRESADOSTAVKI1"
			],
			"UF_ADRESADOSTAVKI10" => [
				"data_type" => "string",
				"title" => "UF_ADRESADOSTAVKI10"
			],
			"UF_ADRESADOSTAVKI11" => [
				"data_type" => "string",
				"title" => "UF_ADRESADOSTAVKI11"
			],
			"UF_ADRESADOSTAVKI12" => [
				"data_type" => "string",
				"title" => "UF_ADRESADOSTAVKI12"
			],
			"UF_ADRESADOSTAVKI13" => [
				"data_type" => "string",
				"title" => "UF_ADRESADOSTAVKI13"
			],
			"UF_ADRESADOSTAVKI14" => [
				"data_type" => "string",
				"title" => "UF_ADRESADOSTAVKI14"
			],
			"UF_ADRESADOSTAVKI2" => [
				"data_type" => "string",
				"title" => "UF_ADRESADOSTAVKI2"
			],
			"UF_ADRESADOSTAVKI3" => [
				"data_type" => "string",
				"title" => "UF_ADRESADOSTAVKI3"
			],
			"UF_ADRESADOSTAVKI4" => [
				"data_type" => "string",
				"title" => "UF_ADRESADOSTAVKI4"
			],
			"UF_ADRESADOSTAVKI5" => [
				"data_type" => "string",
				"title" => "UF_ADRESADOSTAVKI5"
			],
			"UF_ADRESADOSTAVKI6" => [
				"data_type" => "string",
				"title" => "UF_ADRESADOSTAVKI6"
			],
			"UF_ADRESADOSTAVKI7" => [
				"data_type" => "string",
				"title" => "UF_ADRESADOSTAVKI7"
			],
			"UF_ADRESADOSTAVKI8" => [
				"data_type" => "string",
				"title" => "UF_ADRESADOSTAVKI8"
			],
			"UF_ADRESADOSTAVKI9" => [
				"data_type" => "string",
				"title" => "UF_ADRESADOSTAVKI9"
			],
			"UF_DESCRIPTION" => [
				"data_type" => "string",
				"title" => "UF_DESCRIPTION"
			],
			"UF_ELEKTRONNAYAPOCHT" => [
				"data_type" => "string",
				"title" => "UF_ELEKTRONNAYAPOCHT"
			],
			"UF_FAKS" => [
				"data_type" => "string",
				"title" => "UF_FAKS"
			],
			"UF_INN" => [
				"data_type" => "string",
				"title" => "UF_INN"
			],
			"UF_KOD" => [
				"data_type" => "string",
				"title" => "UF_KOD"
			],
			"UF_KPP" => [
				"data_type" => "string",
				"title" => "UF_KPP"
			],
			"UF_NAME" => [
				"data_type" => "string",
				"title" => "UF_NAME"
			],
			"UF_PARTNER" => [
				"data_type" => "string",
				"title" => "UF_PARTNER"
			],
			"UF_POCHTOVYYADRES" => [
				"data_type" => "string",
				"title" => "UF_POCHTOVYYADRES"
			],
			"UF_TELEFON" => [
				"data_type" => "string",
				"title" => "UF_TELEFON"
			],
			"UF_VERSION" => [
				"data_type" => "string",
				"title" => "UF_VERSION"
			],
			"UF_XML_ID" => [
				"data_type" => "string",
				"title" => "UF_XML_ID"
			],
			"UF_YURFIZLITSO" => [
				"data_type" => "string",
				"title" => "UF_YURFIZLITSO"
			],
			"UF_YURIDICHESKIYADRE" => [
				"data_type" => "string",
				"title" => "UF_YURIDICHESKIYADRE"
			],
		];
	}

	/**
	 * @param string $partnerXmlId
	 * @return array|false
	 */
	public static function getByPartner(string $partnerXmlId)
	{
		$kontragenty = [];

		$rs = static::getList([
			"filter" => [
				"=UF_PARTNER" => $partnerXmlId,
			],
			"select" => ["*"]
		]);

		while ($kontragent = $rs->fetch())
		{
			$kontragenty[] = $kontragent;
		}

		if (count($kontragenty))
		{
			return $kontragenty;
		}

		return false;
	}
}