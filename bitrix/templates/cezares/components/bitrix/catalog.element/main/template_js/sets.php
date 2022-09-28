<?php
?>


<script id="sets-template" type="text/html"

	<div class="set_new">
		<h3>Комплект из:</h3>
		{{#ITEMS}}
		<div class="set_item_new set_item_base"
			 data-id="{{ID}}"
			 data-amount="{{AMOUNT}}"
			 data-price="{{PRICE}}"
			 data-old-price="{{PRICE}}"
			 data-discount="{{PRICE}}">
			<div class="cont">
				<div class="product-preview-head product-preview-head--left">
					<span class="product-preview-status-btn product-preview-status-btn--added"></span>
				</div>
				<span class="set_item_base_img">
					{{#PREVIEW_PICTURE}}
						<img src="{{PREVIEW_PICTURE}}">
					{{/PREVIEW_PICTURE}}
					{{^PREVIEW_PICTURE}}
						<img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png">
					{{/PREVIEW_PICTURE}}

					{{#SPECIAL_OFFER}}
						<div class="stickers">
							{{#HIT}}
								<div><div class="sticker_khit">{{HIT}}</div></div>
							{{/HIT}}

							{{#STOCK}}
								<div><div class="sticker_aktsiya">{{STOCK}}</div></div>
							{{/STOCK}}

							{{#SALE}}
								<div><div class="sticker_rasprodazha">{{SALE}}</div></div>
							{{/SALE}}

							{{#NEW}}
								<div><div class="sticker_novinka">{{NEW}}</div></div>
							{{/NEW}}

							{{#PERCENT_5}}
							<div><div class="sticker_5">{{PERCENT_5}}</div></div>
							{{/PERCENT_5}}

							{{#PERCENT_6}}
							<div><div class="sticker_6">{{PERCENT_6}}</div></div>
							{{/PERCENT_6}}

							{{#PERCENT_7}}
							<div><div class="sticker_7">{{PERCENT_7}}</div></div>
							{{/PERCENT_7}}

							{{#PERCENT_9}}
							<div><div class="sticker_9">{{PERCENT_9}}</div></div>
							{{/PERCENT_9}}

							{{#PERCENT_10}}
								<div><div class="sticker_10">{{PERCENT_10}}</div></div>
							{{/PERCENT_10}}

							{{#PERCENT_15}}
								<div><div class="sticker_15">{{PERCENT_15}}</div></div>
							{{/PERCENT_15}}

							{{#PERCENT_20}}
								<div><div class="sticker_20">{{PERCENT_20}}</div></div>
							{{/PERCENT_20}}

							{{#PERCENT_25}}
								<div><div class="sticker_25">{{PERCENT_25}}</div></div>
							{{/PERCENT_25}}

							{{#PERCENT_30}}
								<div><div class="sticker_30">{{PERCENT_30}}</div></div>
							{{/PERCENT_30}}

							{{#PERCENT_40}}
								<div><div class="sticker_40">{{PERCENT_40}}</div></div>
							{{/PERCENT_40}}

							{{#PERCENT_50}}
								<div><div class="sticker_50">{{PERCENT_50}}</div></div>
							{{/PERCENT_50}}

							{{#PERCENT_58}}
								<div><div class="sticker_58">{{PERCENT_58}}</div></div>
							{{/PERCENT_58}}

							{{#PERCENT_70}}
								<div><div class="sticker_70">{{PERCENT_70}}</div></div>
							{{/PERCENT_70}}

						</div>
					{{/SPECIAL_OFFER}}
				</span>

				<span data-price="{{PRICE}}"
					  class="set_item_base_price">{{PRICE}}&#160;₽</span>
				<br/>
				<br/>
				<br/>
				<a class="component_name"
				   href="{{DETAIL_PAGE_URL}}">{{NAME}}</a><br/>
				<span class="component_article">{{ARTICLE}}</span>
				<div class="include_base">В комплекте</div>
			</div>
		</div>
		{{/ITEMS}}
	</div>

</script>
