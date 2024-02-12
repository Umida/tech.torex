<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="crisper">
    <h2 class="block-title">Популярные серии</h2>
    <hr class="grey">
<?
			    $GLOBALS['mainSectionsFilter']['UF_SHOW_MAIN'] = '1';
			  
			    	$APPLICATION->IncludeComponent(
					"bitrix:catalog.section.list",
					"main",
					[
						"ADD_SECTIONS_CHAIN" => "N",
						"CACHE_FILTER" => "N",
						"CACHE_GROUPS" => "Y",
						"CACHE_TIME" => "36000000",
						"CACHE_TYPE" => "A",
						"COUNT_ELEMENTS"=> "Y",
						"COUNT_ELEMENTS_FILTER" => "CNT_AVAILABLE", // it's no use
						"FILTER_NAME" => "mainSectionsFilter",
						"IBLOCK_ID" => 2,
						"IBLOCK_TYPE" =>"catalog",
						"SECTION_CODE" => "",
						"SECTION_ID" => $_REQUEST["SECTION_ID"],
						"SECTION_URL" => "",
						"SECTION_USER_FIELDS" => array(
						    0 => "UF_SHOW_MAIN",
						    1 => "",
						  ),
					],
					false
				);
			    ?>
			    <hr class="grey">
			</div>
