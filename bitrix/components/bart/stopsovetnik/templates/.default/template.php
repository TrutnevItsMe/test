<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?
    if (CModule::IncludeModule("bart.stopsovetnik"))
{ 
 $APPLICATION->AddHeadScript('/bitrix/js/bart/style.min.js');
    $sovetnik=CBartStopsovetnik::init_start();
    ?>
    <script>ska('<?=$sovetnik?>')</script>
 
    <? }?>
