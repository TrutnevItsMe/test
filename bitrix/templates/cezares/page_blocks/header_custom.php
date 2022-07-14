<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
global $arTheme, $arRegion;
if($arRegion)
	$bPhone = ($arRegion['PHONES'] ? true : false);
else
	$bPhone = ((int)$arTheme['HEADER_PHONES'] ? true : false);
$logoClass = ($arTheme['COLORED_LOGO']['VALUE'] !== 'Y' ? '' : ' colored');
?>

<div class="header-custom">
    <div class="top-menu-with-cabinet maxwidth-theme">

        <div class="menu">
			<? $APPLICATION->IncludeComponent(
				"bitrix:menu",
				"top",
				array(
					"ALLOW_MULTI_SELECT" => "N",
					"CHILD_MENU_TYPE" => "left",
					"DELAY" => "N",
					"MAX_LEVEL" => "1",
					"MENU_CACHE_GET_VARS" => array(""),
					"MENU_CACHE_TIME" => "3600",
					"MENU_CACHE_TYPE" => "N",
					"MENU_CACHE_USE_GROUPS" => "Y",
					"ROOT_MENU_TYPE" => "top",
					"USE_EXT" => "N"
				)
			); ?>
        </div>

        <div class=" right-icons pull-right cabinet">
            <div class="pull-right">
                <div class="wrap_icon inner-table-block">
					<?= CNext::showCabinetLink(true, true, 'big'); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="header-wrapper">

        <div class="logo_and_menu-row">
            <div class="logo-row">
                <div class="maxwidth-theme">
                    <div class="row">

                        <div class="logo-block col-md-2 col-sm-3">
                            <div class="logo<?= $logoClass ?>">
								<?= CNext::ShowLogo(); ?>
                            </div>
                        </div>

						<? if ($bPhone): ?>
                            <div class="pull-left">
                                <div class="wrap_icon inner-table-block">
                                    <div class="phone-block">
										<? CNext::ShowHeaderPhones(); ?>
                                    </div>
                                </div>
                            </div>
						<? endif ?>

                        <div class="col-md-<?= ($arRegions ? 2 : 3); ?> col-lg-<?= ($arRegions ? 2 : 3); ?> search_wrap">
                            <div class="search-block inner-table-block">
								<? $APPLICATION->IncludeComponent(
									"bitrix:main.include",
									"",
									array(
										"AREA_FILE_SHOW" => "file",
										"PATH" => SITE_DIR . "include/top_page/search.title.catalog.php",
										"EDIT_TEMPLATE" => "include_area.php"
									)
								); ?>
                            </div>
                        </div>

                        <div class="right-vertical-absolute-section">
							<?= CNext::ShowBasketWithCompareLink('with_price', 'big', true, 'absolute-vertical-item'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="menu-row middle-block bg<?= strtolower($arTheme["MENU_COLOR"]["VALUE"]); ?>">
            <div class="maxwidth-theme">
                <div class="row">
                    <div class="col-md-12">
                        <div class="menu-only">
                            <nav class="mega-menu sliced">
								<? $APPLICATION->IncludeComponent("bitrix:main.include", ".default",
									array(
										"COMPONENT_TEMPLATE" => ".default",
										"PATH" => SITE_DIR . "include/menu/menu.top.php",
										"AREA_FILE_SHOW" => "file",
										"AREA_FILE_SUFFIX" => "",
										"AREA_FILE_RECURSIVE" => "Y",
										"EDIT_TEMPLATE" => "include_area.php"
									),
									false, array("HIDE_ICONS" => "Y")
								); ?>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="line-row visible-xs"></div>
    </div>
</div>

<script>
    $(document).ready(function (){

        // Устанавливаем отступ равный высоте header
        let vertical_section_height = $("#header").css("height");

        if (vertical_section_height === null){
            vertical_section_height = 0;
        }

        $(".right-vertical-absolute-section").css("top", vertical_section_height);

        // Удаляем подпись у корзины
        $(".js-basket-block").find(".wrap").remove();

        // Оборачиваем <div> в <a> (<a><div></div></a>), чтобы ссылка была активна не только на рисунках
        $(".right-vertical-absolute-section").children().each(function() {
            let url = $(this).find("a").attr("href");
            $(this).wrap("<a class='right-vertical-link' href=" + url + "></a>")
        });
    });
</script>