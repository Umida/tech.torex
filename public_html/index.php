<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Входные двери Torex — Купить с доставкой и установкой от официального дилера Торэкс в Москве, Санкт-Петербурге, Калуге, Архангельске и Калининграде");
$APPLICATION->SetPageProperty("keywords", "торекс, торэкс, torex, torex москва, интернет-магазин дверей torex, двери torex, купить torex, купить torex в москве, torex московская область");
$APPLICATION->SetPageProperty('description', 'Воспользуйтесь выгодным предложением и купите входные двери от официального дилера Торэкс в Москве, Санкт-Петербурге, Калуге, Архангельске и Калининграде по выгодной цене. У нас представлен весь модельный ряд дверей Torex.');
?><?
	global $bannerFilter;
	$bannerFilter = array('PROPERTY_CITY' => $userCity["cityXML"],'PROPERTY_BANNERTYPE_VALUE'=>'Основной');
$APPLICATION->IncludeComponent(
			"bitrix:news.list",
			"banners",
			array(
				"IBLOCK_TYPE" => "news",
				"IBLOCK_ID" => "4",
				"SORT_BY1" => "ACTIVE_FROM",
				"SORT_ORDER1" => "DESC",
				"SORT_BY2" => "SORT",
				"SORT_ORDER2" => "ASC",
				"FILTER_NAME" => "bannerFilter",
				"CHECK_DATES" => "Y",
				"DETAIL_URL" => "",
				"AJAX_MODE" => "N",
				"AJAX_OPTION_SHADOW" => "Y",
				"AJAX_OPTION_JUMP" => "N",
				"AJAX_OPTION_STYLE" => "Y",
				"AJAX_OPTION_HISTORY" => "N",
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "36000000",
				"CACHE_FILTER" => "N",
				"CACHE_GROUPS" => "Y",
				"PREVIEW_TRUNCATE_LEN" => "120",
				"ACTIVE_DATE_FORMAT" => "d.m.Y",
				"DISPLAY_PANEL" => "N",
				"SET_TITLE" => "N",
				"SET_STATUS_404" => "N",
				"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
				"ADD_SECTIONS_CHAIN" => "N",
				"HIDE_LINK_WHEN_NO_DETAIL" => "N",
				"PARENT_SECTION" => "",
				"PARENT_SECTION_CODE" => "",
				"DISPLAY_NAME" => "Y",
				"DISPLAY_TOP_PAGER" => "N",
				"DISPLAY_BOTTOM_PAGER" => "N",
				"PAGER_SHOW_ALWAYS" => "N",
				"PAGER_TEMPLATE" => "",
				"PROPERTY_CODE" => array(
				    0 => "banner_title",
				    1 => "",
				),
				"FIELD_CODE" => array("DETAIL_PICTURE"),
				"PAGER_DESC_NUMBERING" => "N",
				"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000000",
				"PAGER_SHOW_ALL" => "N",
				"AJAX_OPTION_ADDITIONAL" => "",
				"COMPONENT_TEMPLATE" => "flat",
				"SET_BROWSER_TITLE" => "N",
				"SET_META_KEYWORDS" => "N",
				"SET_META_DESCRIPTION" => "N",
				"SET_LAST_MODIFIED" => "N",
				"INCLUDE_SUBSECTIONS" => "Y",
				"DISPLAY_DATE" => "Y",
				"DISPLAY_PICTURE" => "Y",
				"DISPLAY_PREVIEW_TEXT" => "Y",
				"MEDIA_PROPERTY" => "",
				"SEARCH_PAGE" => "/search/",
				"USE_RATING" => "N",
				"USE_SHARE" => "N",
				"PAGER_TITLE" => "Новости",
				"PAGER_BASE_LINK_ENABLE" => "N",
				"SHOW_404" => "N",
				"MESSAGE_404" => "",
				"TEMPLATE_THEME" => "site",
			),
			false
		);
		?> <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_RECURSIVE" => "N",
		"AREA_FILE_SHOW" => "sect",
		"AREA_FILE_SUFFIX" => "products",
		"CITY" => $userCity,
		"EDIT_MODE" => "html"
	),
false,
Array(
	'HIDE_ICONS' => 'Y'
)
);?>
<div class="crisper">
	 <?
	$bannerFilter = array('PROPERTY_CITY' => $userCity["cityXML"],'PROPERTY_TYPE_VALUE'=>'Меню');
$APPLICATION->IncludeComponent(
			"bitrix:news.list",
			"banners.menu",
			array(
				"IBLOCK_TYPE" => "news",
				"IBLOCK_ID" => "5",
				"SORT_BY1" => "ACTIVE_FROM",
				"SORT_ORDER1" => "DESC",
				"SORT_BY2" => "SORT",
				"SORT_ORDER2" => "ASC",
				"FILTER_NAME" => "bannerFilter",
				"CHECK_DATES" => "Y",
				"DETAIL_URL" => "",
				"AJAX_MODE" => "N",
				"AJAX_OPTION_SHADOW" => "Y",
				"AJAX_OPTION_JUMP" => "N",
				"AJAX_OPTION_STYLE" => "Y",
				"AJAX_OPTION_HISTORY" => "N",
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "36000000",
				"CACHE_FILTER" => "N",
				"CACHE_GROUPS" => "Y",
				"PREVIEW_TRUNCATE_LEN" => "120",
				"ACTIVE_DATE_FORMAT" => "d.m.Y",
				"DISPLAY_PANEL" => "N",
				"SET_TITLE" => "N",
				"SET_STATUS_404" => "N",
				"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
				"ADD_SECTIONS_CHAIN" => "N",
				"HIDE_LINK_WHEN_NO_DETAIL" => "N",
				"PARENT_SECTION" => "",
				"PARENT_SECTION_CODE" => "",
				"DISPLAY_NAME" => "Y",
				"DISPLAY_TOP_PAGER" => "N",
				"DISPLAY_BOTTOM_PAGER" => "N",
				"PAGER_SHOW_ALWAYS" => "N",
				"PAGER_TEMPLATE" => "",
				"PROPERTY_CODE" => array(
				    0 => "banner_title",
				    1 => "",
				),
				"FIELD_CODE" => array("DETAIL_PICTURE"),
				"PAGER_DESC_NUMBERING" => "N",
				"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000000",
				"PAGER_SHOW_ALL" => "N",
				"AJAX_OPTION_ADDITIONAL" => "",
				"COMPONENT_TEMPLATE" => "flat",
				"SET_BROWSER_TITLE" => "N",
				"SET_META_KEYWORDS" => "N",
				"SET_META_DESCRIPTION" => "N",
				"SET_LAST_MODIFIED" => "N",
				"INCLUDE_SUBSECTIONS" => "Y",
				"DISPLAY_DATE" => "Y",
				"DISPLAY_PICTURE" => "Y",
				"DISPLAY_PREVIEW_TEXT" => "Y",
				"MEDIA_PROPERTY" => "",
				"SEARCH_PAGE" => "/search/",
				"USE_RATING" => "N",
				"USE_SHARE" => "N",
				"PAGER_TITLE" => "Новости",
				"PAGER_BASE_LINK_ENABLE" => "N",
				"SHOW_404" => "N",
				"MESSAGE_404" => "",
				"TEMPLATE_THEME" => "site",
			),
			false
		);
		?>
</div>
 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_RECURSIVE" => "N",
		"AREA_FILE_SHOW" => "sect",
		"AREA_FILE_SUFFIX" => "cats",
		"CITY" => $userCity,
		"EDIT_MODE" => "html"
	),
false,
Array(
	'HIDE_ICONS' => 'Y'
)
);?>
<div class="crisper">
	 <?
	$bannerFilter = array('PROPERTY_CITY' => $userCity["cityXML"],'PROPERTY_BANNERTYPE_VALUE'=>'Вспомогательный');
$APPLICATION->IncludeComponent(
			"bitrix:news.list",
			"banners.second",
			array(
				"IBLOCK_TYPE" => "news",
				"IBLOCK_ID" => "4",
				"SORT_BY1" => "ACTIVE_FROM",
				"SORT_ORDER1" => "DESC",
				"SORT_BY2" => "SORT",
				"SORT_ORDER2" => "ASC",
				"FILTER_NAME" => "bannerFilter",
				"CHECK_DATES" => "Y",
				"DETAIL_URL" => "",
				"AJAX_MODE" => "N",
				"AJAX_OPTION_SHADOW" => "Y",
				"AJAX_OPTION_JUMP" => "N",
				"AJAX_OPTION_STYLE" => "Y",
				"AJAX_OPTION_HISTORY" => "N",
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "36000000",
				"CACHE_FILTER" => "N",
				"CACHE_GROUPS" => "Y",
				"PREVIEW_TRUNCATE_LEN" => "120",
				"ACTIVE_DATE_FORMAT" => "d.m.Y",
				"DISPLAY_PANEL" => "N",
				"SET_TITLE" => "N",
				"SET_STATUS_404" => "N",
				"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
				"ADD_SECTIONS_CHAIN" => "N",
				"HIDE_LINK_WHEN_NO_DETAIL" => "N",
				"PARENT_SECTION" => "",
				"PARENT_SECTION_CODE" => "",
				"DISPLAY_NAME" => "Y",
				"DISPLAY_TOP_PAGER" => "N",
				"DISPLAY_BOTTOM_PAGER" => "N",
				"PAGER_SHOW_ALWAYS" => "N",
				"PAGER_TEMPLATE" => "",
				"PROPERTY_CODE" => array(
				    0 => "banner_title",
				    1 => "",
				),
				"FIELD_CODE" => array("DETAIL_PICTURE"),
				"PAGER_DESC_NUMBERING" => "N",
				"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000000",
				"PAGER_SHOW_ALL" => "N",
				"AJAX_OPTION_ADDITIONAL" => "",
				"COMPONENT_TEMPLATE" => "flat",
				"SET_BROWSER_TITLE" => "N",
				"SET_META_KEYWORDS" => "N",
				"SET_META_DESCRIPTION" => "N",
				"SET_LAST_MODIFIED" => "N",
				"INCLUDE_SUBSECTIONS" => "Y",
				"DISPLAY_DATE" => "Y",
				"DISPLAY_PICTURE" => "Y",
				"DISPLAY_PREVIEW_TEXT" => "Y",
				"MEDIA_PROPERTY" => "",
				"SEARCH_PAGE" => "/search/",
				"USE_RATING" => "N",
				"USE_SHARE" => "N",
				"PAGER_TITLE" => "Новости",
				"PAGER_BASE_LINK_ENABLE" => "N",
				"SHOW_404" => "N",
				"MESSAGE_404" => "",
				"TEMPLATE_THEME" => "site",
			),
			false
		);
		?>
</div>
 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_RECURSIVE" => "N",
		"AREA_FILE_SHOW" => "sect",
		"AREA_FILE_SUFFIX" => "sections",
		"CITY" => $userCity,
		"EDIT_MODE" => "html"
	),
false,
Array(
	'HIDE_ICONS' => 'Y'
)
);?> <!--div class="crisper">
    <h2 class="block-title">Магазины Torex в вашем городе</h2>
    
</div-->
<div class="crisper">
	<h2 class="block-title">Наши преимущества</h2>
	<div class="advantages_block">
		<div class="second-text">
			<span><?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_RECURSIVE" => "N",
		"AREA_FILE_SHOW" => "file",
		"EDIT_MODE" => "html",
		"PATH" => SITE_DIR."include/advantages_text.php"
	),
false,
Array(
	'HIDE_ICONS' => 'Y'
)
);?> </span>
		</div>
		<div class="advantages_button">
			<a class="brand-btn btn" href="" title="" target="_self">Подробнее</a>
		</div>
	</div>
	 <?
	$bannerFilter = array('PROPERTY_CITY' => $userCity["cityXML"],'PROPERTY_TYPE_VALUE'=>'Преимущества');
$APPLICATION->IncludeComponent(
			"bitrix:news.list",
			"banners.advantages",
			array(
				"IBLOCK_TYPE" => "news",
				"IBLOCK_ID" => "5",
				"SORT_BY1" => "ACTIVE_FROM",
				"SORT_ORDER1" => "DESC",
				"SORT_BY2" => "SORT",
				"SORT_ORDER2" => "ASC",
				"FILTER_NAME" => "bannerFilter",
				"CHECK_DATES" => "Y",
				"DETAIL_URL" => "",
				"AJAX_MODE" => "N",
				"AJAX_OPTION_SHADOW" => "Y",
				"AJAX_OPTION_JUMP" => "N",
				"AJAX_OPTION_STYLE" => "Y",
				"AJAX_OPTION_HISTORY" => "N",
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "36000000",
				"CACHE_FILTER" => "N",
				"CACHE_GROUPS" => "Y",
				"PREVIEW_TRUNCATE_LEN" => "120",
				"ACTIVE_DATE_FORMAT" => "d.m.Y",
				"DISPLAY_PANEL" => "N",
				"SET_TITLE" => "N",
				"SET_STATUS_404" => "N",
				"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
				"ADD_SECTIONS_CHAIN" => "N",
				"HIDE_LINK_WHEN_NO_DETAIL" => "N",
				"PARENT_SECTION" => "",
				"PARENT_SECTION_CODE" => "",
				"DISPLAY_NAME" => "Y",
				"DISPLAY_TOP_PAGER" => "N",
				"DISPLAY_BOTTOM_PAGER" => "N",
				"PAGER_SHOW_ALWAYS" => "N",
				"PAGER_TEMPLATE" => "",
				"PROPERTY_CODE" => array(
				    0 => "banner_title",
				    1 => "",
				),
				"FIELD_CODE" => array("DETAIL_PICTURE"),
				"PAGER_DESC_NUMBERING" => "N",
				"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000000",
				"PAGER_SHOW_ALL" => "N",
				"AJAX_OPTION_ADDITIONAL" => "",
				"COMPONENT_TEMPLATE" => "flat",
				"SET_BROWSER_TITLE" => "N",
				"SET_META_KEYWORDS" => "N",
				"SET_META_DESCRIPTION" => "N",
				"SET_LAST_MODIFIED" => "N",
				"INCLUDE_SUBSECTIONS" => "Y",
				"DISPLAY_DATE" => "Y",
				"DISPLAY_PICTURE" => "Y",
				"DISPLAY_PREVIEW_TEXT" => "Y",
				"MEDIA_PROPERTY" => "",
				"SEARCH_PAGE" => "/search/",
				"USE_RATING" => "N",
				"USE_SHARE" => "N",
				"PAGER_TITLE" => "Новости",
				"PAGER_BASE_LINK_ENABLE" => "N",
				"SHOW_404" => "N",
				"MESSAGE_404" => "",
				"TEMPLATE_THEME" => "site",
			),
			false
		);
		?>
</div><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>