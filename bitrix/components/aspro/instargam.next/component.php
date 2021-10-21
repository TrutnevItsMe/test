<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(\Bitrix\Main\Loader::includeModule('aspro.next')){
	if(!isset($arParams['CACHE_TIME'])){
		$arParams['CACHE_TIME'] = 86400;
	}

	$arResult['ITEMS_COUNT'] = ($arParams['ITEMS_COUNT'] && intval($arParams['ITEMS_COUNT']) > 0) ? intval($arParams['ITEMS_COUNT']) : \Bitrix\Main\Config\Option::get('aspro.next', 'INSTAGRAMM_ITEMS_COUNT', '8');
	$arResult['ITEMS_VISIBLE'] = ($arParams['ITEMS_VISIBLE'] && intval($arParams['ITEMS_VISIBLE']) > 0) ? intval($arParams['ITEMS_VISIBLE']) : \Bitrix\Main\Config\Option::get('aspro.next', 'INSTAGRAMM_ITEMS_VISIBLE', '4');
	$arResult['TOKEN'] = $arParams['TOKEN'] ? $arParams['TOKEN'] : \Bitrix\Main\Config\Option::get('aspro.next', 'API_TOKEN_INSTAGRAMM', 'IGQVJXZAnUzbnA4TGhUMm5PNFRvdzROMDR1SFp0Si1HdXIyX2RHWXE5QTRYaXZA5TU9hYTcwRXM1eENnNXhHX0F5bklfV0pLRnVpenpQZAG1DRXNIOElyQmZACQ2hwUTFMYjFqUTdtV3I5dDAwaVFCQjc4MAZDZD');
	$arResult['TITLE'] = $arParams['TITLE'] ? $arParams['TITLE'] : \Bitrix\Main\Config\Option::get('aspro.next', 'INSTAGRAMM_TITLE_BLOCK', GetMessage('INSTAGRAM_TITLE'));
	$arResult['ALL_TITLE'] = $arParams['ALL_TITLE'] ? $arParams['ALL_TITLE'] : GetMessage('INSTAGRAM_ALL_ITEMS');
	$arResult['TEXT_LENGTH'] = ($arParams['TEXT_LENGTH'] && intval($arParams['TEXT_LENGTH']) > 0) ? intval($arParams['TEXT_LENGTH']) : 400;
	$arResult['WIDE_BLOCK'] = $arParams['WIDE_BLOCK'] ? ($arParams['WIDE_BLOCK'] === 'Y' ? 'Y' : 'N') : \Bitrix\Main\Config\Option::get('aspro.next', 'INSTAGRAMM_WIDE_BLOCK', 'N');

	if(!is_object($GLOBALS['USER'])){
		$GLOBALS['USER'] = new CUser();
	}

	$arResult['IS_AJAX'] = (isset($_POST['AJAX_REQUEST_INSTAGRAM']) && $_POST['AJAX_REQUEST_INSTAGRAM'] === 'Y') ? 'Y' : 'N';

	if(
		$this->startResultCache(
			$arParams['CACHE_TIME'],
			array(
				($arParams['CACHE_GROUPS'] === 'N'? false: $GLOBALS['USER']->GetGroups()),
				$arResult
			)
		)
	){
		if($arResult['IS_AJAX'] === 'Y'){
			$obInstagram = new CInstargramNext($arResult['TOKEN'], $arParams['ITEMS_COUNT']);

			$arData = $obInstagram->getInstagramPosts();
			//$arUser = $obInstagram->getInstagramUser();

			if($arData){
				if($arData['error']['message']){
					$arResult['ERROR'] = $arData['error']['message'];
				}
				elseif($arData['data']){
					$arResult['ITEMS'] = array_slice($arData['data'], 0, $arParams['ITEMS_COUNT']);
					$arResult['USER']['username'] = $arData['data'][0]['username'];
				}
			}

			if($arResult['ERROR']){
				$this->AbortResultCache();
				?>
				<?if($GLOBALS['USER']->IsAdmin()):?>
					<br>
					<div class="alert alert-danger">
						<strong>Error: </strong><?=$arResult['ERROR']?>
					</div>
				<?endif;?>
				<?
			}
		}

		$this->IncludeComponentTemplate();
	}
}
else{
	return;
}
?>