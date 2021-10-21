<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
  $arComponentDescription = array(
	"NAME" => GetMessage("BART_STOPSOVETNIK_STOP_ANDEKS_SOVETNIK"),
	"DESCRIPTION" => GetMessage("BART_STOPSOVETNIK_MODULQ_POZVOLAET_NE"),
	"CACHE_PATH" => "Y",
	"SORT" => 10,
	"PATH" => array(
		"ID" => "content",
		"CHILD" => array(
			"ID" => "catalog",
			"NAME" => GetMessage("BART_STOPSOVETNIK_KATALOG"),
			"SORT" => 10
		)
	)
);
