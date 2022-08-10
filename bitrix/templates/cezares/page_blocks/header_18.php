<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
global $arTheme, $arRegion;
if($arRegion)
	$bPhone = ($arRegion['PHONES'] ? true : false);
else
	$bPhone = ((int)$arTheme['HEADER_PHONES'] ? true : false);
$logoClass = ($arTheme['COLORED_LOGO']['VALUE'] !== 'Y' ? '' : ' colored');
?>

<div class="top-menu-with-cabinet maxwidth-theme" >
    <div class="menu">
		<?$APPLICATION->IncludeComponent(
			"bitrix:menu",
			"top",
			Array(
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
		);?>
    </div>

    <div class="right-icons pull-right cabinet">
        <div class="pull-right">
            <div class="wrap_icon inner-table-block">
				<?= CNext::showCabinetLink(true, true, 'big'); ?>
            </div>
        </div>
    </div>
</div>

<div class="header-v4 header-wrapper">

    <div class="logo_and_menu-row">
        <div class="logo-row">
            <div class="maxwidth-theme">
                <div class="row">

					<?if($bPhone):?>
                        <div class="pull-left">
                            <div class="wrap_icon inner-table-block">
                                <div class="phone-block">
									<?CNext::ShowHeaderPhones();?>
                                </div>
                            </div>
                        </div>
					<?endif?>

                    <div class="logo-block col-md-2 col-sm-3">
                        <div class="logo<?=$logoClass?>">
							<?=CNext::ShowLogo();?>
                        </div>
                    </div>


                    <div class="col-md-<?=($arRegions ? 2 : 3);?> col-lg-<?=($arRegions ? 2 : 3);?> search_wrap">
                        <div class="search-block inner-table-block">
							<?$APPLICATION->IncludeComponent(
								"bitrix:main.include",
								"",
								Array(
									"AREA_FILE_SHOW" => "file",
									"PATH" => SITE_DIR."include/top_page/search.title.catalog.php",
									"EDIT_TEMPLATE" => "include_area.php"
								)
							);?>
                        </div>
                    </div>

                    <div class="right-vertical-absolute-section">
						<?=CNext::ShowBasketWithCompareLink('with_price', 'big', true, 'absolute-vertical-item');?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="menu-row middle-block bg<?=strtolower($arTheme["MENU_COLOR"]["VALUE"]);?>">
        <div class="maxwidth-theme">
            <div class="row">
                <div class="col-md-12">
                    <div class="menu-only">
                        <nav class="mega-menu sliced">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
								array(
									"COMPONENT_TEMPLATE" => ".default",
									"PATH" => SITE_DIR."include/menu/menu.top.php",
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "",
									"AREA_FILE_RECURSIVE" => "Y",
									"EDIT_TEMPLATE" => "include_area.php"
								),
								false, array("HIDE_ICONS" => "Y")
							);?>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="line-row visible-xs"></div>
</div>

<style>

    .right-vertical-absolute-section{
        position: fixed;
        right: 0px;
        border-radius: 10px;
    }
    .absolute-vertical-item{
        width: 70px;
        height: 70px;
        /*background-color: black;*/
        border-bottom: solid #bbb 2px;
        align-content: center;
        text-align: center;
        background-color: #555;
    }
    .absolute-vertical-item > a, .basket-link{
        top: 25%;
        padding: 0px !important;
    }
    .right-vertical-link:hover .absolute-vertical-item{
        background-color: #5e6978
    }
    .absolute-vertical-item svg > path{
        fill: white !important;
    }
    #bx_incl_area_3{
        padding-top: 25%;
    }
    .search-input{
        width: 400px;
    }
    .top-menu-with-cabinet > .menu{
        margin-top: 20px;
    }
    .top-menu-with-cabinet{
        border-style: solid;
        border-width: 0px;
        border-color: #e5e5e5;
        border-bottom-width: 1px;
    }
    .top-menu-with-cabinet > .cabinet{
        margin-top: -20px;
        z-index: 12;
    }
</style>

<script>
    $(document).ready(function (){
        let vertical_section_height = $("#header").css("height");

        if (vertical_section_height === null){
            vertical_section_height = 0;
        }

        $(".right-vertical-absolute-section").css("top", vertical_section_height);

        $(".js-basket-block").find(".wrap").remove();

        $(".right-vertical-absolute-section").find(">:first-child").css("border-radius", "10px 10px 0px 0px");
        $(".right-vertical-absolute-section").find(">:last-child").css("border-radius", "0px 0px 10px 10px");

        $(".right-vertical-absolute-section").children().each(function() {
            let url = $(this).find("a").attr("href");
            $(this).wrap("<a class='right-vertical-link' href=" + url + "></a>")
        });
    });
</script>