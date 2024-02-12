<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

CJSCore::Init(array("fx"));
global $userCity;
global $USER;

$curPage = $APPLICATION->GetCurPage(true);
$userCity = getgeoposition();
?><!DOCTYPE html>
<html xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>">
<head>
	<title><?$APPLICATION->ShowTitle()?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width">
	<link rel="icon" type="image/x-icon" href="/images/svg/favicon.svg">
	<? $APPLICATION->ShowHead(); ?>
</head>
<body>
<div id="panel"><? $APPLICATION->ShowPanel(); ?></div>
	  <div class="header-top">
	      <div class="crisper">
	          <div class="header-top__section">
	     <div class="header-top__region">
	         <?$APPLICATION->IncludeComponent("bitrix:highloadblock.view","city",Array(
		"BLOCK_ID" => 8,
		"ROW_ID" => $userCity["cityId"],
	)
);?>
	     </div>
	     <div class="header-top__menu">
	         	<?$APPLICATION->IncludeComponent(
						"bitrix:menu",
						"top_horizontal",
						array(
							"ROOT_MENU_TYPE" => "top",
							"MENU_CACHE_TYPE" => "A",
							"MENU_CACHE_TIME" => "36000000",
							"MENU_CACHE_USE_GROUPS" => "Y",
							"MENU_THEME" => "site",
							"CACHE_SELECTED_ITEMS" => "N",
							"MENU_CACHE_GET_VARS" => array(),
							"MAX_LEVEL" => "3",
							"CHILD_MENU_TYPE" => "left",
							"USE_EXT" => "Y",
							"DELAY" => "N",
							"ALLOW_MULTI_SELECT" => "N"
						),
						false
					);?>
	     </div>
	     <div class="header-top__personal">
	         <?php
	         if ($USER->IsAuthorized()) {?>
	         <a href="/personal/"><i data-svg="profile"></i><span>Личный кабинет</span></a>
	         <?php
	         } else {?>
	         <a href="/auth/"><i data-svg="profile"></i><span>Войти</span></a>
	         <?php
	         } ?>
	     </div>
	     </div>
				
	  </div>  
	    </div>
	    <header>
	        <div class="crisper">
	        <div class="header-catalog__top">
	            
	            <div class="header-catalog__logo">
	                 <?if($curPage != SITE_DIR . "index.php"):?>
	                     <a href="/" class="torex"><div class="logo"></div></a>
	                 <?else:?>
	                     <a href="#" class="torex"><div class="logo"></div></a>
	                 <? endif;?>
	            </div>
	            <div class="header-catalog__search">
	                <div class="main-catalog__btn" data-widget="CatalogMenu"><i data-svg="catalog"></i><span>Все двери</span></div>
	                	<?$APPLICATION->IncludeComponent(
							"bitrix:search.title",
							"main",
							array(
								"NUM_CATEGORIES" => "1",
								"TOP_COUNT" => "5",
								"CHECK_DATES" => "N",
								"SHOW_OTHERS" => "N",
								"PAGE" => SITE_DIR."catalog/",
								"CATEGORY_0_TITLE" => GetMessage("SEARCH_GOODS") ,
								"CATEGORY_0" => array(
									0 => "iblock_catalog",
								),
								"CATEGORY_0_iblock_catalog" => array(
									0 => "all",
								),
								"CATEGORY_OTHERS_TITLE" => GetMessage("SEARCH_OTHER"),
								"SHOW_INPUT" => "Y",
								"INPUT_ID" => "title-search-input",
								"CONTAINER_ID" => "search",
								"PRICE_CODE" => array(
									0 => "BASE",
								),
								"SHOW_PREVIEW" => "Y",
								"PREVIEW_WIDTH" => "75",
								"PREVIEW_HEIGHT" => "75",
								"CONVERT_CURRENCY" => "Y"
							),
							false
						);?>
	            </div>
	            <div class="header-catalog__btns-block">
	               
 <?$APPLICATION->IncludeComponent(
										"bitrix:main.include",
										"",
										array(
											"AREA_FILE_SHOW" => "file",
											"PATH" => SITE_DIR."include/favorite.php"
										),
										false
									);?>
	              
	               
	                	                <?$APPLICATION->IncludeComponent(
	"bitrix:catalog.compare.list", 
	"main", 
	array(
		"IBLOCK_TYPE" => "catalog", 
		"IBLOCK_ID" => "2", 
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"DETAIL_URL" => "#SECTION_CODE#",
		"COMPARE_URL" => "/compare",
		"NAME" => "",
		"AJAX_OPTION_ADDITIONAL" => ""
	),
false
);?>
	               <?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", "header", array(
	                   "PATH_TO_BASKET" => SITE_DIR."cart/",
	                   "PATH_TO_PERSONAL" => SITE_DIR."personal/",
	                   "SHOW_PERSONAL_LINK" => "N"
	                   ),
	                   false,
	                   Array('')
	                   );
	               ?>
	            </div>
	            <div class="header-catalog__contacts">
	                <a class="header-contacts__phone">
	                    
	                <?$APPLICATION->IncludeComponent(
										"bitrix:main.include",
										"",
										array(
											"AREA_FILE_SHOW" => "file",
											"PATH" => SITE_DIR."include/telephone.php"
										),
										false
									);?>
									</a>
									
									<span class="header-contacts__schedule">
									<?$APPLICATION->IncludeComponent(
										"bitrix:main.include",
										"",
										array(
											"AREA_FILE_SHOW" => "file",
											"PATH" => SITE_DIR."include/schedule.php"
										),
										false
									);?>
									</span>
	            </div>
	          </div>
	        <div class="header-catalog__menu">
	            <div class="catalog__menu-main">
	                		<?$APPLICATION->IncludeComponent(
						"bitrix:menu",
						"catalog_horizontal",
						array(
							"ROOT_MENU_TYPE" => "catalog",
							"MENU_CACHE_TYPE" => "A",
							"MENU_CACHE_TIME" => "36000000",
							"MENU_CACHE_USE_GROUPS" => "Y",
							"MENU_THEME" => "site",
							"CACHE_SELECTED_ITEMS" => "N",
							"MENU_CACHE_GET_VARS" => array(),
							"MAX_LEVEL" => "3",
							"CHILD_MENU_TYPE" => "left",
							"USE_EXT" => "Y",
							"DELAY" => "N",
							"ALLOW_MULTI_SELECT" => "N"
						),
						false
					);?>
				</div>
	           <div class="catalog__menu-btn">
	               <button data-toggle="modal" data-template="magnet" data-target="#formModal">
                         <span>Бесплатный замер</span>
                     </button>
                </div>
	        </div>  
	        </div>
	        
	        
		<div class="crisper">
			<?if ($curPage != SITE_DIR."index.php"):?>
					<div id="navigation">
						<?$APPLICATION->IncludeComponent(
							"bitrix:breadcrumb",
							"universal",
							array(
								"START_FROM" => "0",
								"PATH" => "",
								"SITE_ID" => "-"
							),
							false,
							Array('HIDE_ICONS' => 'Y')
						);?>
				</div>
			<?endif?>
		</div>
	</header>
	<div class="workarea">


				