<div class="top_inner_block_wrapper maxwidth-theme">
	<section class="page-top maxwidth-theme <?CNext::ShowPageProps('TITLE_CLASS');?>">
		<?$APPLICATION->IncludeComponent(
	"bitrix:breadcrumb", 
	"next1", 
	array(
		"START_FROM" => "0",
		"PATH" => "",
		"SITE_ID" => "s1",
		"SHOW_SUBSECTIONS" => "N",
		"COMPONENT_TEMPLATE" => "next1"
	),
	false
);?>
		<div class="page-top-main">
			<?=$APPLICATION->ShowViewContent('product_share')?>
			<h1 id="pagetitle"><?$APPLICATION->ShowTitle(false)?></h1>
		</div>
	</section>
</div>