<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<div class="social-icons">
	<?if(
		$arParams["SOCIAL_TITLE"] && (
			!empty($arResult["SOCIAL_VK"]) ||
			!empty($arResult["SOCIAL_ODNOKLASSNIKI"]) ||
			!empty($arResult["SOCIAL_FACEBOOK"]) ||
			!empty($arResult["SOCIAL_TWITTER"]) ||
			!empty($arResult["SOCIAL_INSTAGRAM"]) ||
			!empty($arResult["SOCIAL_TELEGRAM"]) ||
			!empty($arResult["SOCIAL_MAIL"]) ||
			!empty($arResult["SOCIAL_YOUTUBE"]) ||
			!empty($arResult["SOCIAL_GOOGLEPLUS"]) ||
			!empty($arResult["SOCIAL_VIBER"]) ||
			!empty($arResult["SOCIAL_WHATS"]) ||
			!empty($arResult["SOCIAL_ZEN"]) ||
			!empty($arResult["SOCIAL_PINTEREST"]) ||
			!empty($arResult["SOCIAL_SNAPCHAT"]) ||
			!empty($arResult["SOCIAL_TIKTOK"]) ||
			!empty($arResult["SOCIAL_LINKEDIN"])
		)
	):?>
		<div class="small_title"><?=$arParams["SOCIAL_TITLE"];?></div>
	<?endif;?>
	<!-- noindex -->
	<ul>
		<?if(!empty($arResult['SOCIAL_VK'])):?>
			<li class="vk">
				<a href="<?=$arResult['SOCIAL_VK']?>" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_VK')?>">
					<?=GetMessage('TEMPL_SOCIAL_VK')?>
				</a>
			</li>
		<?endif;?>
		<?if(!empty($arResult['SOCIAL_FACEBOOK'])):?>
			<li class="facebook">
				<a href="<?=$arResult['SOCIAL_FACEBOOK']?>" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_FACEBOOK')?>">
					<?=GetMessage('TEMPL_SOCIAL_FACEBOOK')?>
				</a>
			</li>
		<?endif;?>
		<?if(!empty($arResult['SOCIAL_TWITTER'])):?>
			<li class="twitter">
				<a href="<?=$arResult['SOCIAL_TWITTER']?>" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_TWITTER')?>">
					<?=GetMessage('TEMPL_SOCIAL_TWITTER')?>
				</a>
			</li>
		<?endif;?>
		<?if(!empty($arResult['SOCIAL_INSTAGRAM'])):?>
			<li class="instagram">
				<a href="<?=$arResult['SOCIAL_INSTAGRAM']?>" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_INSTAGRAM')?>">
					<?=GetMessage('TEMPL_SOCIAL_INSTAGRAM')?>
				</a>
			</li>
		<?endif;?>
		<?if(!empty($arResult['SOCIAL_TELEGRAM'])):?>
			<li class="telegram">
				<a href="<?=$arResult['SOCIAL_TELEGRAM']?>" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_TELEGRAM')?>">
					<?=GetMessage('TEMPL_SOCIAL_TELEGRAM')?>
				</a>
			</li>
		<?endif;?>
		<?if(!empty($arResult['SOCIAL_YOUTUBE'])):?>
			<li class="ytb">
				<a href="<?=$arResult['SOCIAL_YOUTUBE']?>" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_YOUTUBE')?>">
					<?=GetMessage('TEMPL_SOCIAL_YOUTUBE')?>
				</a>
			</li>
		<?endif;?>
		<?if(!empty($arResult['SOCIAL_ODNOKLASSNIKI'])):?>
			<li class="odn">
				<a href="<?=$arResult['SOCIAL_ODNOKLASSNIKI']?>" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_ODNOKLASSNIKI')?>">
					<?=GetMessage('TEMPL_SOCIAL_ODNOKLASSNIKI')?>
				</a>
			</li>
		<?endif;?>
		<?if(!empty($arResult['SOCIAL_GOOGLEPLUS'])):?>
			<li class="gplus">
				<a href="<?=$arResult['SOCIAL_GOOGLEPLUS']?>" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_GOOGLEPLUS')?>">
					<?=GetMessage('TEMPL_SOCIAL_GOOGLEPLUS')?>
				</a>
			</li>
		<?endif;?>
		<?if(!empty($arResult['SOCIAL_MAIL'])):?>
			<li class="mail">
				<a href="<?=$arResult['SOCIAL_MAIL']?>" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_MAILRU')?>">
					<?=GetMessage('TEMPL_SOCIAL_MAILRU')?>
				</a>
			</li>
		<?endif;?>
		<?if(!empty($arResult['SOCIAL_VIBER'])):?>
			<li class="viber">
				<a href="<?=$arResult['SOCIAL_VIBER']?>" class="dark-color" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_VIBER')?>">
					<?=GetMessage('TEMPL_SOCIAL_VIBER')?>
				</a>
			</li>
		<?endif;?>
		<?if(!empty($arResult['SOCIAL_WHATS'])):?>
			<li class="whats">
				<a href="<?=$arResult['SOCIAL_WHATS']?>" class="dark-color" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_WHATS')?>">
					<?=GetMessage('TEMPL_SOCIAL_WHATS')?>
				</a>
			</li>
		<?endif;?>
		<?if(!empty($arResult['SOCIAL_ZEN'])):?>
			<li class="zen">
				<a href="<?=$arResult['SOCIAL_ZEN']?>" class="dark-color" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_ZEN')?>">
					<?=GetMessage('TEMPL_SOCIAL_ZEN')?>
				</a>
			</li>
		<?endif;?>
		<?if(!empty($arResult['SOCIAL_TIKTOK'])):?>
			<li class="tiktok">
				<a href="<?=$arResult['SOCIAL_TIKTOK']?>" class="dark-color" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_TIKTOK')?>">
					<?=GetMessage('TEMPL_SOCIAL_TIKTOK')?>
				</a>
			</li>
		<?endif;?>
		<?if(!empty($arResult['SOCIAL_PINTEREST'])):?>
			<li class="pinterest">
				<a href="<?=$arResult['SOCIAL_PINTEREST']?>" class="dark-color" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_PINTEREST')?>">
					<?=GetMessage('TEMPL_SOCIAL_PINTEREST')?>
				</a>
			</li>
		<?endif;?>
		<?if(!empty($arResult['SOCIAL_SNAPCHAT'])):?>
			<li class="snapchat">
				<a href="<?=$arResult['SOCIAL_SNAPCHAT']?>" class="dark-color" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_SNAPCHAT')?>">
					<?=GetMessage('TEMPL_SOCIAL_SNAPCHAT')?>
				</a>
			</li>
		<?endif;?>
		<?if(!empty($arResult['SOCIAL_LINKEDIN'])):?>
			<li class="linkedin">
				<a href="<?=$arResult['SOCIAL_LINKEDIN']?>" class="dark-color" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_LINKEDIN')?>">
					<?=GetMessage('TEMPL_SOCIAL_LINKEDIN')?>
				</a>
			</li>
		<?endif;?>
	</ul>
	<!-- /noindex -->
</div>