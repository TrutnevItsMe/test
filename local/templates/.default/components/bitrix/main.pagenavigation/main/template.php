<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @global $arResult
 * @global $APPLICATION
 */

$this->setFrameMode(true); ?>

<? if ($arResult["PAGE_COUNT"] > 1): ?>
	<?

	$cntItemBetweenCurPage = 3; // count numbers left and right from cur page

	if ($arResult["CURRENT_PAGE"] == 1) {
		$bPrevDisabled = true;
	} elseif ($arResult["CURRENT_PAGE"] < $arResult["PAGE_COUNT"]) {
		$bPrevDisabled = false;
	}

	if ($arResult["CURRENT_PAGE"] == $arResult["PAGE_COUNT"]) {
		$bNextDisabled = true;
	} else {
		$bNextDisabled = false;
	}

	$cntToStartPage = $arResult["CURRENT_PAGE"] - $arResult["START_PAGE"];
	$cntToEndPage = $arResult["END_PAGE"] - $arResult["CURRENT_PAGE"];

	if ($cntToStartPage <= $cntItemBetweenCurPage)
	{
		$showPrevDots = false;
		$startPage = $arResult["START_PAGE"];
	}
	else
	{
		$showPrevDots = true;
		$startPage = $arResult["CURRENT_PAGE"] - $cntItemBetweenCurPage;
	}

	if ($cntToEndPage <= $cntItemBetweenCurPage)
	{
		$showAfterDots = false;
		$finishPage = $arResult["END_PAGE"];
	}
	else
	{
		$showAfterDots = true;
		$finishPage = $arResult["END_PAGE"] - $cntItemBetweenCurPage;
	}

	?>

	<div class="module-pagination">
		<div class="nums">
			<ul class="flex-direction-nav">
				<?php if(!$bPrevDisabled):?>
					<?php $url = $APPLICATION->GetCurPageParam($arResult["ID"]."=page-".($arResult["CURRENT_PAGE"] - 1), [$arResult["ID"]]);?>
					<li class="flex-nav-prev "><a href="<?=$url?>" class="flex-prev pagination-item"></a></li>
				<?php endif;?>

				<?php if(!$bNextDisabled):?>
					<?php $url = $APPLICATION->GetCurPageParam($arResult["ID"]."=page-".($arResult["CURRENT_PAGE"] + 1), [$arResult["ID"]]);?>
					<li class="flex-nav-next "><a href="<?=$url?>" class="flex-next pagination-item"></a></li>
				<?php endif;?>
			</ul>

			<?php if($showPrevDots):?>
				<?php $url = $APPLICATION->GetCurPageParam($arResult["ID"]."=page-".$arResult["START_PAGE"], [$arResult["ID"]]);?>
				<a href="<?=$url?>" class="dark_link pagination-item"><?=$arResult["START_PAGE"]?></a>
				<span class="point_sep"></span>
			<?php endif;?>

			<?php for($i = $startPage; $i <= $finishPage; ++$i):?>
				<?if ($i == $arResult["CURRENT_PAGE"]):?>
					<span class="cur"><?=$i?></span>
					<?continue;?>
				<?php endif;?>

				<?php $url = $APPLICATION->GetCurPageParam($arResult["ID"]."=page-".$i, [$arResult["ID"]]);?>

				<a href="<?=$url?>" class="dark_link pagination-item"><?=$i?></a>
			<?php endfor;?>

			<?php if($showAfterDots):?>
				<?php $url = $APPLICATION->GetCurPageParam($arResult["ID"]."=page-".$arResult["END_PAGE"], [$arResult["ID"]]);?>
				<a href="<?=$url?>" class="dark_link pagination-item"><?=$arResult["END_PAGE"]?></a>
				<span class="point_sep"></span>
			<?php endif;?>
		</div>
	</div>
<? endif; ?>