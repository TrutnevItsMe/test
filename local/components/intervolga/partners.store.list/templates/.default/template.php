<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @global $arResult
 * @global $arParams
 */

?>

<div class="d-flex mt-5" style="height: 200px">
	<section class="w-20" style="background-color: blue;">
		<div id="filter" class="w-75 h-50 mt-5 mr-3 float-right" style="background-color: black;"></div>
	</section>
	<section class="w-80">
		<div id="map" class="w-85 h-10 ml-5 mt-5" style="background-color: black;"></div>

		<div class="w-25 h-30 d-inline-block m-3 mt-5 ml-5 item-wrap"></div>
		<div class="w-25 h-30 d-inline-block m-3 mt-5 ml-5 item-wrap"></div>
		<div class="w-25 h-30 d-inline-block m-3 mt-5 ml-5 item-wrap"></div>
	</section>
</div>

<?php echo $arResult["NAV_STRING"];?>

	<pre>
	<? var_dump($arResult) ?>
</pre>
<?php