<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use Intervolga\Common\Highloadblock\HlbWrap;

$hl = new HlbWrap('Partnery');
$partner = $hl->getList([
	'filter' => ['=UF_XML_ID' => $arResult['arUser']["XML_ID"]],
	'select' => ['UF_OSNOVNOYMENEDZHER', 'UF_POMOSHNIK1', 'UF_POMOSHNIK2', 'UF_POMOSHNIK3',
		'UF_OSNMENEDZHERTELEF', 'UF_POMOSHNIKTELEFON1', 'UF_POMOSHNIKTELEFON2', 'UF_POMOSHNIKTELEFON3',
		'UF_OSNMENEDZHERADRES', 'UF_POMOSHNIKADRES1', 'UF_POMOSHNIKADRES2', 'UF_POMOSHNIKADRES3',
		/*, 'UF_POMOSHNIK4',*/],
])->fetch();
if ($partner
	&& ($partner['UF_OSNOVNOYMENEDZHER'] || $partner['UF_POMOSHNIK1'] || $partner['UF_POMOSHNIK2']
		|| $partner['UF_POMOSHNIK3'] || $partner['UF_POMOSHNIK4'])
) {
	$arResult['POMOSHNIKI'] = $partner;
}
