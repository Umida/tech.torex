<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


	</div><!--end .workarea-->

	<footer>
	    <div class="crisper">
	        <div class="footer-top">
	            <div>
	                 <div class="h3"><a href="/catalog/">Фирменный салон Torex</a></div>
	                 <div>
	                     <? $APPLICATION->IncludeComponent(
                                "bitrix:menu",
                                "bottom_child",
                                array(
                                    "ALLOW_MULTI_SELECT" => "N",
                                    "CHILD_MENU_TYPE" => "left",
                                    "DELAY" => "N",
                                    "MAX_LEVEL" => "2",
                                    "FILTER_CITY" => $userCity["cityNAME"],
                                    "MENU_CACHE_GET_VARS" => array(),
                                    "MENU_CACHE_TIME" => "36000000",
                                    "MENU_CACHE_TYPE" => "A",
                                    "MENU_CACHE_USE_GROUPS" => "Y",
                                    "ROOT_MENU_TYPE" => "bottom_catalog",
                                    "USE_EXT" => "N",
                                    "COMPONENT_TEMPLATE" => "main"
                                ),
                                false
                            ); ?>
              
	                 </div>
	            </div>
	            <div>
	                <div class="h3"><a href="/catalog/">Серии</a></div>
	                <div class="two-columns">
	                    <? $APPLICATION->IncludeComponent(
                                "bitrix:menu",
                                "bottom_child",
                                array(
                                    "ALLOW_MULTI_SELECT" => "N",
                                    "CHILD_MENU_TYPE" => "left",
                                    "DELAY" => "N",
                                    "MAX_LEVEL" => "2",
                                    "FILTER_CITY" => $userCity['cityNAME'],
                                    "MENU_CACHE_GET_VARS" => array(),
                                    "MENU_CACHE_TIME" => "36000000",
                                    "MENU_CACHE_TYPE" => "A",
                                    "MENU_CACHE_USE_GROUPS" => "Y",
                                    "ROOT_MENU_TYPE" => "bottom_series",
                                    "USE_EXT" => "N",
                                    "COMPONENT_TEMPLATE" => "main"
                                ),
                                false
                            ); ?>
	                </div>
	            </div>
	            <div>
	                <div class="h3"><a href="/about/">О компании</a></div>
	                       <div>
	                    <? $APPLICATION->IncludeComponent(
                                "bitrix:menu",
                                "bottom_child",
                                array(
                                    "ALLOW_MULTI_SELECT" => "N",
                                    "CHILD_MENU_TYPE" => "left",
                                    "DELAY" => "N",
                                    "MAX_LEVEL" => "2",
                                    "FILTER_CITY" => $userCity['cityNAME'],
                                    "MENU_CACHE_GET_VARS" => array(),
                                    "MENU_CACHE_TIME" => "36000000",
                                    "MENU_CACHE_TYPE" => "A",
                                    "MENU_CACHE_USE_GROUPS" => "Y",
                                    "ROOT_MENU_TYPE" => "bottom_about",
                                    "USE_EXT" => "N",
                                    "COMPONENT_TEMPLATE" => "main"
                                ),
                                false
                            ); ?>
	                </div>
	            </div>
	            <div>
	                <div class="h3"><a href="/about/">Покупателям</a></div>
	                       <div>
	                    <? $APPLICATION->IncludeComponent(
                                "bitrix:menu",
                                "bottom_child",
                                array(
                                    "ALLOW_MULTI_SELECT" => "N",
                                    "CHILD_MENU_TYPE" => "left",
                                    "DELAY" => "N",
                                    "MAX_LEVEL" => "2",
                                    "FILTER_CITY" => $userCity['cityNAME'],
                                    "MENU_CACHE_GET_VARS" => array(),
                                    "MENU_CACHE_TIME" => "36000000",
                                    "MENU_CACHE_TYPE" => "A",
                                    "MENU_CACHE_USE_GROUPS" => "Y",
                                    "ROOT_MENU_TYPE" => "bottom_customer",
                                    "USE_EXT" => "N",
                                    "COMPONENT_TEMPLATE" => "main"
                                ),
                                false
                            ); ?>
	                </div>
	            </div>
	            <div class="footer_last-column">
	                <div class="h3"><a href="/about/">Контакты</a></div>
	                <div class="footer_white-column">
	                    <?	$contactsFilter = array('PROPERTY_79' => $userCity["cityXML"],'PROPERTY_81_VALUE'=>'Контакты. Футер');
	                    $APPLICATION->IncludeComponent(
                "bitrix:news.list",
                "footer.contacts",
                Array(
                    "CACHE_FILTER" => "Y",
                    "CACHE_GROUPS" => "Y",
                    "CACHE_TIME" => "36000000",
                    "CACHE_TYPE" => "A",
                    "SET_BROWSER_TITLE" => "N",
                    "SET_LAST_MODIFIED" => "N",
                    "SET_META_DESCRIPTION" => "N",
                    "SET_META_KEYWORDS" => "N",
                    "SET_TITLE" => "N",
                    "INCLUDE_IBLOCK_INTO_CHAIN"=>"N",
                    "IBLOCK_ID" => 8,
                    "FILTER_NAME" => "contactsFilter"
                )
            );?>
	                </div>
	            </div>
	                    
	       </div>
	   <hr class="dark-grey">
        <div class="footer-bottom">
            <div>
                <div class="footer-bottom_copyright">
                    <div>
                   <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array(
								"AREA_FILE_SHOW" => "file",
								"PATH" => SITE_DIR."include/copyright.php"
							), false);?>
                    </div>
                    <div>
                         <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array(
								"AREA_FILE_SHOW" => "file",
								"PATH" => SITE_DIR."include/privacy.php"
							), false);?>
                    </div>
                </div>
                <div class="personal-text">
                    <? $APPLICATION->IncludeComponent(
								"bitrix:main.include",
								"",
								array(
									"AREA_FILE_SHOW" => "file",
									"PATH" => SITE_DIR."include/personal.php"
								),
								false
							);?>
                </div>
            </div>
            <div class="footer_last-column">
                 <? $APPLICATION->IncludeComponent(
                      "bitrix:main.feedback",
                      "subscribe-footer",
                      array(
                          "EMAIL_TO" => "",
                          "EVENT_MESSAGE_ID" => array("52"),
                          "OK_TEXT" => "Спасибо, Ваше сообщение принято!",
                          "CLIENT_CITY" => $userCity["cityNAME"],
                          "REQUIRED_FIELDS" => array("NAME"),
                          "AJAX_MODE" => "Y",
                          "AJAX_OPTION_JUMP" => "Y",
                          "AJAX_OPTION_STYLE" => "Y",
                          "AJAX_OPTION_HISTORY" => "Y",
                          "AJAX_OPTION_ADDITIONAL" => "",
                          "USE_CAPTCHA" => "N"
                      )
                  ); ?>
            </div>
        </div>
        </div>
        
	
	</footer>
<? 
\Bitrix\Main\UI\Extension::load('ui.bootstrap4');
use Bitrix\Main\Page\Asset;
Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/template.js");
Asset::getInstance()->addCss("/libs/js-slick/slick.css");
Asset::getInstance()->addCss("/libs/js-slick/slick-theme.css");
Asset::getInstance()->addJs("/libs/js-slick/slick.min.js");
?>

<script>
	BX.ready(function(){
		var upButton = document.querySelector('[data-role="eshopUpButton"]');
		BX.bind(upButton, "click", function(){
			var windowScroll = BX.GetWindowScrollPos();
			(new BX.easing({
				duration : 500,
				start : { scroll : windowScroll.scrollTop },
				finish : { scroll : 0 },
				transition : BX.easing.makeEaseOut(BX.easing.transitions.quart),
				step : function(state){
					window.scrollTo(0, state.scroll);
				},
				complete: function() {
				}
			})).animate();
		})
	});
</script>

</body>
</html>