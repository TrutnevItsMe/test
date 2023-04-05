<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?if($GET["debug"] == "y")
	error_reporting(E_ERROR | E_PARSE);
IncludeTemplateLangFile(__FILE__);
global $APPLICATION, $arRegion, $arSite, $arTheme;
$arSite = CSite::GetByID(SITE_ID)->Fetch();
$htmlClass = ($_REQUEST && isset($_REQUEST['print']) ? 'print' : false);
$bIncludedModule = (\Bitrix\Main\Loader::includeModule("aspro.next"));?>


<?php
$asset = Bitrix\Main\Page\Asset::getInstance();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>" <?=($htmlClass ? 'class="'.$htmlClass.'"' : '')?>>
<head>
	<title><?$APPLICATION->ShowTitle()?></title>
	<?$APPLICATION->ShowMeta("viewport");?>
	<?$APPLICATION->ShowMeta("HandheldFriendly");?>
	<?$APPLICATION->ShowMeta("apple-mobile-web-app-capable", "yes");?>
	<?$APPLICATION->ShowMeta("apple-mobile-web-app-status-bar-style");?>
	<?$APPLICATION->ShowMeta("SKYPE_TOOLBAR");?>
	<?$APPLICATION->ShowHead();?>
	<?$APPLICATION->AddHeadString('<script>BX.message('.CUtil::PhpToJSObject( $MESS, false ).')</script>', true);?>

	<?$asset->addCss(SITE_TEMPLATE_PATH."/css/common/display.css");?>
	<?$asset->addCss(SITE_TEMPLATE_PATH."/css/common/margin.css");?>
	<?$asset->addCss(SITE_TEMPLATE_PATH."/css/common/size.css");?>
	<?$asset->addCss(SITE_TEMPLATE_PATH."/css/common/float.css");?>
	<?$asset->addCss(SITE_TEMPLATE_PATH."/css/common/cursor.css");?>
	<?$asset->addCss(SITE_TEMPLATE_PATH."/css/common/align-items.css");?>
	<?$asset->addCss(SITE_TEMPLATE_PATH."/css/common/justify-content.css");?>

	<?if($bIncludedModule)
		CNext::Start(SITE_ID);?>
</head>
<?$bIndexBot = (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Lighthouse') !== false); // is indexed yandex/google bot?>
<body class="site_<?=SITE_ID?> <?=($bIncludedModule ? "fill_bg_".strtolower(CNext::GetFrontParametrValue("SHOW_BG_BLOCK")) : "");?> <?=($bIndexBot ? "wbot" : "");?>" id="main">
	<div id="panel"><?$APPLICATION->ShowPanel();?></div>
	<?if(!$bIncludedModule):?>
		<?$APPLICATION->SetTitle(GetMessage("ERROR_INCLUDE_MODULE_ASPRO_NEXT_TITLE"));?>
		<center><?$APPLICATION->IncludeFile(SITE_DIR."include/error_include_module.php");?></center></body></html><?die();?>
	<?endif;?>
			<a class="review_form"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 448c141.4 0 256-93.1 256-208S397.4 32 256 32S0 125.1 0 240c0 45.1 17.7 86.8 47.7 120.9c-1.9 24.5-11.4 46.3-21.4 62.9c-5.5 9.2-11.1 16.6-15.2 21.6c-2.1 2.5-3.7 4.4-4.9 5.7c-.6 .6-1 1.1-1.3 1.4l-.3 .3 0 0 0 0 0 0 0 0c-4.6 4.6-5.9 11.4-3.4 17.4c2.5 6 8.3 9.9 14.8 9.9c28.7 0 57.6-8.9 81.6-19.3c22.9-10 42.4-21.9 54.3-30.6c31.8 11.5 67 17.9 104.1 17.9zM128 208a32 32 0 1 1 0 64 32 32 0 1 1 0-64zm128 0a32 32 0 1 1 0 64 32 32 0 1 1 0-64zm96 32a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/></svg></a>

	<?$arTheme = $APPLICATION->IncludeComponent("aspro:theme.next", ".default", array("COMPONENT_TEMPLATE" => ".default"), false, array("HIDE_ICONS" => "Y"));?>
	<?include_once('defines.php');?>
	<?CNext::SetJSOptions();?>

	<div class="wrapper1 <?=($isIndex && $isShowIndexLeftBlock ? "with_left_block" : "");?> <?=CNext::getCurrentPageClass();?> <?=CNext::getCurrentThemeClasses();?>">
		<?CNext::get_banners_position('TOP_HEADER');?>

		<div class="header_wrap visible-lg visible-md title-v<?=$arTheme["PAGE_TITLE"]["VALUE"];?><?=($isIndex ? ' index' : '')?>">
			<header id="header">
				<?CNext::ShowPageType('header');?>
			</header>
		</div>
		<?CNext::get_banners_position('TOP_UNDERHEADER');?>

		<?if($arTheme["TOP_MENU_FIXED"]["VALUE"] == 'Y'):?>
			<div id="headerfixed">
				<?CNext::ShowPageType('header_fixed');?>
			</div>
		<?endif;?>

		<div id="mobileheader" class="visible-xs visible-sm">
			<?CNext::ShowPageType('header_mobile');?>
			<div id="mobilemenu" class="<?=($arTheme["HEADER_MOBILE_MENU_OPEN"]["VALUE"] == '1' ? 'leftside':'dropdown')?> <?=($arTheme['HEADER_MOBILE_MENU_COMPACT']['VALUE'] == 'Y' ? 'menu-compact':'')?>">
				<?CNext::ShowPageType('header_mobile_menu');?>
			</div>
		</div>

		<?if($arTheme['MOBILE_FILTER_COMPACT']['VALUE'] === 'Y'):?>
		    <div id="mobilefilter" class="visible-xs visible-sm scrollbar-filter"></div>
		<?endif;?>
		
		<?/*filter for contacts*/
		if($arRegion)
		{
			if($arRegion['LIST_STORES'] && !in_array('component', $arRegion['LIST_STORES']))
			{
				if($arTheme['STORES_SOURCE']['VALUE'] != 'IBLOCK')
					$GLOBALS['arRegionality'] = array('ID' => $arRegion['LIST_STORES']);
				else
					$GLOBALS['arRegionality'] = array('PROPERTY_STORE_ID' => $arRegion['LIST_STORES']);
			}
		}
		if($isIndex)
		{
			$GLOBALS['arrPopularSections'] = array('UF_POPULAR' => 1);
			$GLOBALS['arrFrontElements'] = array('PROPERTY_SHOW_ON_INDEX_PAGE_VALUE' => 'Y');
		}?>

		<div class="wraps hover_<?=$arTheme["HOVER_TYPE_IMG"]["VALUE"];?>" id="content">
			<?if(!$is404 && !$isForm && !$isIndex):?>
				<?$APPLICATION->ShowViewContent('section_bnr_content');?>
				<?if($APPLICATION->GetProperty("HIDETITLE") !== 'Y'):?>
					<!--title_content-->
					<?CNext::ShowPageType('page_title');?>
					<!--end-title_content-->
				<?endif;?>
				<?$APPLICATION->ShowViewContent('top_section_filter_content');?>
			<?endif;?>

			<?if($isIndex):?>
				<div class="wrapper_inner front <?=($isShowIndexLeftBlock ? "" : "wide_page");?>">
			<?elseif(!$isWidePage):?>
				<div class="wrapper_inner <?=($isHideLeftBlock ? "wide_page" : "");?>">
			<?endif;?>

				<?if(($isIndex && $isShowIndexLeftBlock) || (!$isIndex && !$isHideLeftBlock) && !$isBlog):?>
					<div class="right_block <?=(defined("ERROR_404") ? "error_page" : "");?> wide_<?=CNext::ShowPageProps("HIDE_LEFT_BLOCK");?>">
				<?endif;?>
					<div class="middle <?=($is404 ? 'error-page' : '');?>">
						<?CNext::get_banners_position('CONTENT_TOP');?>
						<?if(!$isIndex):?>
							<div class="container">
								<?//h1?>
								<?if($isHideLeftBlock && !$isWidePage):?>
									<div class="maxwidth-theme">
								<?endif;?>
								<?if($isBlog):?>
									<div class="row">
										<div class="col-md-9 col-sm-12 col-xs-12 content-md <?=CNext::ShowPageProps("ERROR_404");?>">
								<?endif;?>
						<?endif;?>
						<?CNext::checkRestartBuffer();?>