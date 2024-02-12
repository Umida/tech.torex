<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Catalog\ProductTable;

global $delaydBasketItems;
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

$this->setFrameMode(true);


$currencyList = '';

if (!empty($arResult['CURRENCIES'])) {
    $templateLibrary[] = 'currency';
    $currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}

$haveOffers = !empty($arResult['OFFERS']);

$templateData = [
    'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
    'TEMPLATE_LIBRARY' => $templateLibrary,
    'CURRENCIES' => $currencyList,
    'ITEM' => [
        'ID' => $arResult['ID'],
        'IBLOCK_ID' => $arResult['IBLOCK_ID'],
    ],
];
if ($haveOffers) {
    $templateData['ITEM']['OFFERS_SELECTED'] = $arResult['OFFERS_SELECTED'];
    $templateData['ITEM']['JS_OFFERS'] = $arResult['JS_OFFERS'];
}
unset($currencyList, $templateLibrary);
$mainId = $this->GetEditAreaId($arResult['ID']);
$itemIds = array(
    'ID' => $mainId,
    'DISCOUNT_PERCENT_ID' => $mainId . '_dsc_pict',
    'STICKER_ID' => $mainId . '_sticker',
    'BIG_SLIDER_ID' => $mainId . '_big_slider',
    'BIG_IMG_CONT_ID' => $mainId . '_bigimg_cont',
    'SLIDER_CONT_ID' => $mainId . '_slider_cont',
    'OLD_PRICE_ID' => $mainId . '_old_price',
    'PRICE_ID' => $mainId . '_price',
    'DISCOUNT_PRICE_ID' => $mainId . '_price_discount',
    'PRICE_TOTAL' => $mainId . '_price_total',
    'SLIDER_CONT_OF_ID' => $mainId . '_slider_cont_',
    'QUANTITY_ID' => $mainId . '_quantity',
    'QUANTITY_DOWN_ID' => $mainId . '_quant_down',
    'QUANTITY_UP_ID' => $mainId . '_quant_up',
    'QUANTITY_MEASURE' => $mainId . '_quant_measure',
    'QUANTITY_LIMIT' => $mainId . '_quant_limit',
    'BUY_LINK' => $mainId . '_buy_link',
    'ADD_BASKET_LINK' => $mainId . '_add_basket_link',
    'BASKET_ACTIONS_ID' => $mainId . '_basket_actions',
    'NOT_AVAILABLE_MESS' => $mainId . '_not_avail',
    'COMPARE_LINK' => $mainId . '_compare_link',
    'TREE_ID' => $haveOffers && !empty($arResult['OFFERS_PROP']) ? $mainId . '_skudiv' : null,
    'DISPLAY_PROP_DIV' => $mainId . '_sku_prop',
    'DESCRIPTION_ID' => $mainId . '_description',
    'DISPLAY_MAIN_PROP_DIV' => $mainId . '_main_sku_prop',
    'OFFER_GROUP' => $mainId . '_set_group_',
    'BASKET_PROP_DIV' => $mainId . '_basket_prop',
    'SUBSCRIBE_LINK' => $mainId . '_subscribe',
    'TABS_ID' => $mainId . '_tabs',
    'TAB_CONTAINERS_ID' => $mainId . '_tab_containers',
    'SMALL_CARD_PANEL_ID' => $mainId . '_small_card_panel',
    'TABS_PANEL_ID' => $mainId . '_tabs_panel'
);
$obName = $templateData['JS_OBJ'] = 'ob' . preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);
$name = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])
    ? $arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
    : $arResult['NAME'];
$title = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'])
    ? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE']
    : $arResult['NAME'];
$alt = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'])
    ? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT']
    : $arResult['NAME'];

if ($haveOffers) {
    $actualItem = $arResult['OFFERS'][$arResult['OFFERS_SELECTED']] ?? reset($arResult['OFFERS']);
    $showSliderControls = false;

    foreach ($arResult['OFFERS'] as $offer) {
        if ($offer['MORE_PHOTO_COUNT'] > 1) {
            $showSliderControls = true;
            break;
        }
    }
} else {
    $actualItem = $arResult;
    $showSliderControls = $arResult['MORE_PHOTO_COUNT'] > 1;
}


$skuProps = array();
$old_price='';
$price = $actualItem['ITEM_PRICES'][$actualItem['ITEM_PRICE_SELECTED']];


    
$showBuyBtn = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION']);
$buyButtonClassName = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-primary' : 'btn-link';
$showAddBtn = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION']);
$showButtonClassName = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-primary' : 'btn-link';
$showSubscribe = $arParams['PRODUCT_SUBSCRIPTION'] === 'Y' && ($arResult['PRODUCT']['SUBSCRIBE'] === 'Y' || $haveOffers);

$arParams['MESS_BTN_BUY'] = $arParams['MESS_BTN_BUY'] ?: Loc::getMessage('CT_BCE_CATALOG_BUY');
$arParams['MESS_BTN_ADD_TO_BASKET'] = $arParams['MESS_BTN_ADD_TO_BASKET'] ?: Loc::getMessage('CT_BCE_CATALOG_ADD');

$arParams['MESS_BTN_COMPARE'] = $arParams['MESS_BTN_COMPARE'] ?: Loc::getMessage('CT_BCE_CATALOG_COMPARE');
$arParams['MESS_PRICE_RANGES_TITLE'] = $arParams['MESS_PRICE_RANGES_TITLE'] ?: Loc::getMessage('CT_BCE_CATALOG_PRICE_RANGES_TITLE');
$arParams['MESS_DESCRIPTION_TAB'] = $arParams['MESS_DESCRIPTION_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_DESCRIPTION_TAB');
$arParams['MESS_PROPERTIES_TAB'] = $arParams['MESS_PROPERTIES_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_PROPERTIES_TAB');
$arParams['MESS_COMMENTS_TAB'] = $arParams['MESS_COMMENTS_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_COMMENTS_TAB');
$arParams['MESS_SHOW_MAX_QUANTITY'] = $arParams['MESS_SHOW_MAX_QUANTITY'] ?: Loc::getMessage('CT_BCE_CATALOG_SHOW_MAX_QUANTITY');
$arParams['MESS_RELATIVE_QUANTITY_MANY'] = $arParams['MESS_RELATIVE_QUANTITY_MANY'] ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['MESS_RELATIVE_QUANTITY_FEW'] = $arParams['MESS_RELATIVE_QUANTITY_FEW'] ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_FEW');

$wishclass = "";
if (is_array($delaydBasketItems) && in_array($arResult["ID"], $delaydBasketItems)) {
    $wishclass = " in_wishlist";
}

$showOffersBlock = $haveOffers && !empty($arResult['OFFERS_PROP']);

$showPropsBlock = !empty($mainBlockProperties) || $arResult['SHOW_OFFERS_PROPS'];
$showBlockWithOffersAndProps = $showOffersBlock || $showPropsBlock;
$itemProps = $arResult['DISPLAY_PROPERTIES'];
//print_r($arResult['DISPLAY_PROPERTIES']);
//print_r($arResult['OFFERS']);
$offerTitle = "Входная дверь " . mb_strtolower($itemProps['PURPOSE']['VALUE']) . " " . $itemProps['VENDOR']['DISPLAY_VALUE'] . " " . $name;
?>
    <div class="bx-catalog-element" id="<?= $itemIds['ID'] ?>" itemscope itemtype="http://schema.org/Product">
        <h1 class="product-title"><?= $offerTitle; ?></h1>
        <div class="product-main_block">
            <div class="product-main_left">
                <div class="product-main_left_icons">
                    <? if ($arResult["REVIEWS_COUNT"] > 0): ?>
                        <div class="product-main_reviews">
                            <div class="rating">
                                <i data-svg="star"<?=(($arResult['REVIEWS_TOTAL_SCORE'] >= 1)?' data-view="active"':'')?>></i>
                                <i data-svg="star"<?=(($arResult['REVIEWS_TOTAL_SCORE'] >= 2)?' data-view="active"':'')?>></i>
                                <i data-svg="star"<?=(($arResult['REVIEWS_TOTAL_SCORE'] >= 3)?' data-view="active"':'')?>></i>
                                <i data-svg="star"<?=(($arResult['REVIEWS_TOTAL_SCORE'] >= 4)?' data-view="active"':'')?>></i>
                                <i data-svg="star"<?=(($arResult['REVIEWS_TOTAL_SCORE'] >= 5)?' data-view="active"':'')?>></i>
                            </div>
                            <span><?= $arResult["REVIEWS_COUNT"]; ?> <?= getNoun($arResult["REVIEWS_COUNT"], "отзыв", "отзыва", "отзывов") ?></span>
                        </div>
                    <?endif; ?>
                    <button data-productid="<?= $arResult["ID"] ?>" class="wishbtn<?= $wishclass ?>"
                            onclick="El2wish('<?= ($wishclass == '' ? 'add' : 'delete') ?>','<?= ($wishclass == '' ? $arResult["ID"] : array_search($arResult["ID"], $delaydBasketItems)) ?>','<?= $priceOffer['PRICE_TYPE_ID'] ?>','<?= $priceOffer['PRINT_RATIO_PRICE'] ?>','<?= $arResult["NAME"] ?>','<?= $arResult["DETAIL_PAGE_URL"] ?>',this)">
                        <i data-svg="product-heart"></i><span><?= ($wishclass == '' ? 'В избранное' : 'В избранном') ?></span>
                    </button>
                    <? if ($arParams['DISPLAY_COMPARE']) {
                        ?>
                        <button id="<?= $itemIds['COMPARE_LINK'] ?>"><i data-svg="product-comp"></i>К сравнению</button>

                        <?php
                    } ?></div>


                <div class="product-main_left_sku">
                    <div class="main-photo" style="background-image:url(<?=CFile::GetPath($arResult["PROPERTIES"]["MAIN_PHOTO_R"]["VALUE"])?>)"></div>
                    <div class="sku-props_container">
                        <? if ($arResult['MODEL']['PREVIEW'] || $arResult['PREVIEW_TEXT']):
                            $maxLength = 195;
                            $descText = ($arResult['PREVIEW_TEXT'] ? html_entity_decode($arResult['PREVIEW_TEXT']) : html_entity_decode($arResult['MODEL']['PREVIEW'])); ?>
                            <div class="description-block">
                                <div class="product-scu-title"><span>Описание</span></div>
                                <div class="short-description<?= ((strlen($descText) > $maxLength + 5) ? ' height_hidden' : '') ?>">
                                    <?= $descText; ?>
                                </div>
                                <? if (strlen($descText) > $maxLength + 5):?>
                                    <div class="description-more">Подробнее</div>
                                <?endif; ?>
                            </div>
                        <?endif; ?>
                        <div id="<?= $itemIds['TREE_ID'] ?>">
                            <?php
                            foreach ($arResult['SKU_PROPS'] as $skuProperty) {
                                if (!isset($arResult['OFFERS_PROP'][$skuProperty['CODE']]))
                                    continue;

                                $skuPropertyValues = $skuProperty['VALUES'];
                                $tmpArray = array();
                                foreach ($skuPropertyValues as $valueId => $valueData) {
                                    $tmpArray[$valueId] = $valueData['VALUE'];
                                }
                                asort($tmpArray);
                                $sortedSkuPropertyValues = array();
                                foreach ($tmpArray as $valueId => $value) {
                                    $sortedSkuPropertyValues[$valueId] = $skuPropertyValues[$valueId];
                                }

                                $propertyId = $skuProperty['ID'];
                                $skuProps[] = array(
                                    'ID' => $propertyId,
                                    'SHOW_MODE' => $skuProperty['SHOW_MODE'],
                                    'VALUES' => $skuProperty['VALUES'],
                                    'VALUES_COUNT' => $skuProperty['VALUES_COUNT']
                                );

                                ?>
                                <div data-entity="sku-line-block">
                                    <div class="product-scu-title"><span><?= htmlspecialcharsEx($skuProperty['NAME']) ?></span></div>
                                    <div class="product-scu-container">
                                                <ul class="product-scu-item-list <?= strtolower($skuProperty['CODE']) . '_field' ?>">
                                                    <?php
                                                    asort($skuProperty['VALUES']);
                                                    $propcount = 0;
                                                    foreach ($skuProperty['VALUES'] as &$value):
                                                        $value['NAME'] = htmlspecialcharsbx($value['NAME']);
                                                        ++$propcount;
                                                        if ($value['ID']):
                                                            ?>
                                                            <li class="product-item-scu-item-text-container<?= ($propcount > 6) ? ' hidden' : '' ?>"
                                                                title="<?= $value['NAME'] ?>"
                                                                data-treevalue="<?= $propertyId ?>_<?= $value['ID'] ?>"
                                                                data-onevalue="<?= $value['ID'] ?>">
                                                                <div class="product-scu-item-text-block">
                                                                    <div class="product-scu-item-text"><?= $value['NAME'] ?></div>
                                                                </div>
                                                            </li>

                                                        <?php
                                                        endif;
                                                    endforeach;
                                                    ?>
                                                </ul>
                                                <? if ($propcount > 6):?>
                                                    <span class="show-more_btn" data-block=".cml2_size_field">Показать еще</span>
                                                <?endif ?>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <div>
                            <div class="product-scu-title"><span>Цвет</span></div>
                            <div class="product-scu-container color_field">
                                <?=$arResult['DISPLAY_PROPERTIES']['COLOROUTSIDE']['DISPLAY_VALUE']?>/<?=$arResult['DISPLAY_PROPERTIES']['COLORINSIDE']['DISPLAY_VALUE']?>
                            </div>
                            <?if($arResult['other']):?>
                            <div class="other-colors">
                                <a href="#" class="current" style="background-image:url(<?=CFile::GetPath($arResult["PROPERTIES"]["MAIN_PHOTO_R"]["VALUE"])?>)"></a>
                                <?foreach ($arResult['other'] as &$other):?>
                                <a href="<?=$other['URL']?>" style="background-image:url(<?=$other['PICT']?>)"></a>
                            <?endforeach;?>
                            </div>
                            <?endif;?>
                        </div>
                         <div>
                            <div class="product-scu-title"><span>Характеристики</span></div>
                            <ul class="product-scu-container props_field">
                                <li class="specification-line">
                                    <div class="specification-line_left"><span>Цвет снаружи</span></div>
                                    <span class="specification-line_right"><?=$arResult['DISPLAY_PROPERTIES']['COLOROUTSIDE']['DISPLAY_VALUE']?></span>
                                </li>
                                <li class="specification-line">
                                    <div class="specification-line_left"><span>Цвет внутри</span></div>
                                    <span class="specification-line_right"><?=$arResult['DISPLAY_PROPERTIES']['COLORINSIDE']['DISPLAY_VALUE']?></span>
                                </li>
                                <li class="specification-line">
                                    <div class="specification-line_left"><span>Внешняя отделка</span></div>
                                    <span class="specification-line_right"><?=$arResult['DISPLAY_PROPERTIES']['DESIGNOUTSIDE']['DISPLAY_VALUE']?></span>
                                </li>
                                <li class="specification-line">
                                    <div class="specification-line_left"><span>Внутренняя отделка</span></div>
                                    <span class="specification-line_right"><?=$arResult['DISPLAY_PROPERTIES']['DESIGNINSIDE']['DISPLAY_VALUE']?></span>
                                </li>
                            </ul>
                            <a href="#empty" class="feature-btn" data-scroll="feature">Все характеристики</a>
                        </div>
                    </div>
                </div>


            </div>
            
            
            <div class="product-main_right">
                <div class="product-main_right_icons">
                    <div class="product-top-properties" id="<?= $itemIds['DISPLAY_PROP_DIV'] ?>"></div>
                </div>
                <div class="product-sale_block">
                    <div class="product-price_block">
                        <div class="product-item-detail-price-current"
                             id="<?= $itemIds['PRICE_ID'] ?>"><?= $price['PRINT_RATIO_PRICE'] ?>
                        </div>
                         <?php
                        $priceOffer = $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']];
                            ?>
                            <div class="product-item-detail-price-old"><?=( $old_price) ?></div>
                            <?php
                        
                        ?>
                        </div>
                        <div data-entity="main-button-container">
                                <? if (empty($arResult['PROPERTIES']['defect_id']['VALUE']) && $price['PRICE'] > 0): ?>
                                <div class="credit_block">
                                    <a class="credit-btn" data-toggle="modal" data-target="#formModal" data-template="credit"><span class="credit-month"><?= number_format(($price['PRICE'] / 6), 0, '', ' ') ?></span><span>x 6 месяцев в рассрочку</span></a>
                                </div>   
                                  <? endif ?>   
                                  
                            <div id="<?= $itemIds['BASKET_ACTIONS_ID'] ?>"
                                 style="display: <?= ($actualItem['CAN_BUY'] ? '' : 'none') ?>;">

                                <a class="brand-btn btn product-item-detail-buy-button"
                                   id="<?= $itemIds['BUY_LINK'] ?>"
                                   href="javascript:void(0);">
                                    <i data-svg="cart"></i>Добавить в корзину
                                </a>

                            </div>
                        </div>
                        <div class="store-btn"><a href="/stores/"><i data-svg="stores"></i>Наши шоурумы</a></div>
                </div>       
                <div class="btn product-item-detail-oneclick-button">
                    <a>Купить в 1 клик</a>
                </div>
                <div class="second-button-container">
                    <div><a><i data-svg="swap"></i><span>Вызвать<br>замерщика</span></a></div>
                    <div><a><i data-svg="expert"></i><span>Вызвать<br>эксперта</span></a></div>
                </div>
            </div>
        </div>
                             <section class="element-detail" data-screen="feature" data-view="active">
    <div class="crisper">
    <div class="element-detail__nav">
        <div class="element-detail__nav-title">
            <div><span class="" data-active="0">Описание</span></div>
              <?if ($arResult['MODEL']['model_pics']):?>
                <div><span data-active="1" class="">Фото</span></div>    
            <?endif;?>
            <div><span data-active="2" class="active">Характеристики</span></div>
            <div><span data-active="3" class="">Замковая система</span></div>
            <?if ($arResult['REVIEWS']):?>
                <div><span data-active="4" class="">Отзывы</span></div>    
            <?endif;?>
            <div><span data-active="5" class="">Доставка и оплата</span></div>
            <div><span data-active="6" class="">Документы</span></div>
            <div><span data-active="7" class="">Гарантия</span></div>
            <div><span data-active="8" class="">Возврат</span></div>
            
        </div>
    </div>
    <div class="element-detail__nav-info">
        <div class="element-detail__item"><?=$arResult['MODEL']['DESC'];?></div>
        <div class="element-detail__item">
               <?if ($arResult['MODEL']['model_pics']):?>
               <div class="row">
                                                <? foreach ($arResult['MODEL']['model_pics'] as $pics):?>
                                                <div class="col-lg-4"><div class="foto-element">
                                                    <img src="<?=$pics?>" alt=""></div></div>
                                                 <? endforeach; ?>
								
								</div>
								
                                             <?endif;?>
        </div>
        <div class="element-detail__item active"><?=$mainproperty;?></div>
        <div class="element-detail__item"><?=$lockproperty;?></div>
        <div class="element-detail__item">
               <?if ($arResult['REVIEWS']):?>
               <div class="row reviews_block">
                    <? foreach ($arResult['REVIEWS'] as $review):?>
                        <div class="review__container js-review-block">
											<div class="review__author">
												<div class="review__author-name m-b-xs"><?=$review['PROPERTIES']['client']['VALUE']?></div>
												<div class="review__author-date  m-b-s"><?=$review['PROPERTIES']['score_date']['VALUE']?></div>
												<div class="review__author-series m-b-xs">Серия двери: <span class="review__author-city_black"><?=$review['PROPERTIES']['model']['VALUE']?></span></div>
												<div class="review__author-use">Срок использования: <span class="review__author-city_black"><?=$review['PROPERTIES']['period']['VALUE']?></span></div>
											</div>
											<div class="review__feedback">
												<div class="review__feedback-title m-b-xs"><?=$review['TITLE']?></div>
												<div class="review__feedback-text">
												    <div class="part_hide_text"><?=$review['TEXT']?></div>
												    <a href="<?=$review['DETAIL_PAGE_URL']?>" target="_blank" class="part_hide_button">Посмотреть отзыв</a>
												</div>
											</div>
											<div class="review__rating scores">
												<div class="review__rating-heading m-b-xs">Общее впечатление</div>
													<div class="rating">
					     
						<i data-svg="star"<? if($review["PROPERTIES"]['score']['VALUE']>=1){ echo ' data-view="active"';}?>></i>
						<i data-svg="star"<? if($review["PROPERTIES"]['score']['VALUE']>=2){ echo ' data-view="active"';}?>></i>
						<i data-svg="star"<? if($review["PROPERTIES"]['score']['VALUE']>=3){ echo ' data-view="active"';}?>></i>
						<i data-svg="star"<? if($review["PROPERTIES"]['score']['VALUE']>=4){ echo ' data-view="active"';}?>></i>
						<i data-svg="star"<? if($review["PROPERTIES"]['score']['VALUE']>=5){ echo ' data-view="active"';}?>></i>
					</div>
												<div class="review__rating-stars m-b-s">
													<span class="review__rating-star"></span><span class="review__rating-star"></span><span class="review__rating-star"></span><span class="review__rating-star"></span><span class="review__rating-star"></span>												</div>
												<div class="review__rating-installation m-b-xs">
													<span class="review__rating-title">Качество монтажа</span>
													
														<div class="score" data-view="<?=$review["PROPERTIES"]['score_montage']['VALUE']?>"></div>
													
												</div>
												<div class="review__rating-service m-b-xs">
													<span class="review__rating-title">Обслуживание в салоне</span>
														<div class="score" data-view="<?=$review["PROPERTIES"]['score_service']['VALUE']?>"></div>
												
												</div>
												<div class="review__rating-product">
													<span class="review__rating-title">Качество двери</span>
													<div class="score" data-view="<?=$review["PROPERTIES"]['score_door']['VALUE']?>"></div>
												
												</div>
											</div>
										</div>    
                    <? endforeach; ?>
                </div>
                <?endif;?>
        </div>
         
        <div class="element-detail__item"><?$APPLICATION->IncludeComponent(
                                                "custom:info.list",
                                                "product.card",
                                                array(
                                                    "CACHE_FILTER" => "Y",
                                                    "CACHE_GROUPS" => "Y",
                                                    "CACHE_TIME" => "36000000",
                                                    "CACHE_TYPE" => "A",
                                                    "IBLOCK_ID" => 37,
                                                    "INFO_TYPE" => "Оплата и доставка",
                                                    "INFO_CITY" => $arParams['FILTER_CITY']
                                                )
                                            );?></div>
        <div class="element-detail__item">
            <div class="document-wrap">
                 <? foreach ($arResult['MODEL']['DOCS']['LINK'] as $key=>$doc):?>
                    <a href="<?=$doc;?>" class="document-container" target="_blank" download="">
										<span class="document-container__title-wrap">
											<span class="document-container__title"><?=$arResult['MODEL']['DOCS']['DESC'][$key]?></span>
										</span>
									</a>
                    <? endforeach; ?>
																	
																	
																</div>
            
        </div>
        
        <div class="element-detail__item"><?$APPLICATION->IncludeComponent(
                                                "custom:info.list",
                                                "product.card",
                                                array(
                                                    "CACHE_FILTER" => "Y",
                                                    "CACHE_GROUPS" => "Y",
                                                    "CACHE_TIME" => "36000000",
                                                    "CACHE_TYPE" => "A",
                                                    "IBLOCK_ID" => 37,
                                                    "INFO_TYPE" => "Гарантия",
                                                    "INFO_CITY" => $arParams['FILTER_CITY']
                                                )
                                            );?></div>
        <div class="element-detail__item"><?$APPLICATION->IncludeComponent(
                                                "custom:info.list",
                                                "product.card",
                                                array(
                                                    "CACHE_FILTER" => "Y",
                                                    "CACHE_GROUPS" => "Y",
                                                    "CACHE_TIME" => "36000000",
                                                    "CACHE_TYPE" => "A",
                                                    "IBLOCK_ID" => 37,
                                                    "INFO_TYPE" => "Возврат товара",
                                                    "INFO_CITY" => $arParams['FILTER_CITY']
                                                )
                                            );?></div>
                                         
    </div>

    </div>
</section>

  

        <meta itemprop="name" content="<?= $name ?>"/>
        <meta itemprop="category" content="<?= $arResult['CATEGORY_PATH'] ?>"/>
        <meta itemprop="id" content="<?= $arResult['ID'] ?>"/>
        <?php
        if ($haveOffers) {
            foreach ($arResult['JS_OFFERS'] as $offer) {
                $currentOffersList = array();

                if (!empty($offer['TREE']) && is_array($offer['TREE'])) {
                    foreach ($offer['TREE'] as $propName => $skuId) {
                        $propId = (int)substr($propName, 5);

                        foreach ($skuProps as $prop) {
                            if ($prop['ID'] == $propId) {
                                foreach ($prop['VALUES'] as $propId => $propValue) {
                                    if ($propId == $skuId) {
                                        $currentOffersList[] = $propValue['NAME'];
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }

                $offerPrice = $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']];
                ?>
                <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
			<meta itemprop="sku" content="<?= htmlspecialcharsbx(implode('/', $currentOffersList)) ?>"/>
			<meta itemprop="price" content="<?= $offerPrice['RATIO_PRICE'] ?>"/>
			<meta itemprop="priceCurrency" content="<?= $offerPrice['CURRENCY'] ?>"/>
			<link itemprop="availability"
                  href="http://schema.org/<?= ($offer['CAN_BUY'] ? 'InStock' : 'OutOfStock') ?>"/>
		</span>
                <?php
            }

            unset($offerPrice, $currentOffersList);
        } else {
            ?>
            <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
		<meta itemprop="price" content="<?= $price['RATIO_PRICE'] ?>"/>
		<meta itemprop="priceCurrency" content="<?= $price['CURRENCY'] ?>"/>
		<link itemprop="availability"
              href="http://schema.org/<?= ($actualItem['CAN_BUY'] ? 'InStock' : 'OutOfStock') ?>"/>
	</span>
            <?php
        }
        ?>
        <?php
        if ($haveOffers) {
            $offerIds = array();
            $offerCodes = array();

            $useRatio = $arParams['USE_RATIO_IN_RANGES'] === 'Y';

            foreach ($arResult['JS_OFFERS'] as $ind => &$jsOffer) {
                $offerIds[] = (int)$jsOffer['ID'];
                $offerCodes[] = $jsOffer['CODE'];
                $fullOffer = $arResult['OFFERS'][$ind];
                $measureName = $fullOffer['ITEM_MEASURE']['TITLE'];
          
                $strAllProps = '';
                $strMainProps = '';
                $old_price='';

                if ($arResult['SHOW_OFFERS_PROPS']) {
                    if (!empty($jsOffer['DISPLAY_PROPERTIES'])) {
                        foreach ($jsOffer['DISPLAY_PROPERTIES'] as $property) {
                            if ($property['CODE'] == 'STATUS' || $property['CODE'] == 'ARTICLE'):
                                $current = '<div class="product-item-detail-properties-item">';
                                if ($property['CODE'] == 'ARTICLE'):
                                    $current .= '<span class="product-item-detail-properties-name">Артикул: </span>';
                                elseif ($property['CODE'] == 'STATUS'):
                                    $current .= '<div class="stock ' . ($property['VALUE']== 'В наличии'?'instock':'onorder') . '"><i><svg width="7" height="5" viewBox="0 0 7 5" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M1 2.54231L2.63385 4L6 1" stroke="white" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
</svg></i>';
                                endif;

                                $current .= '<span class="product-item-detail-properties-value">' . (
                                    is_array($property['VALUE'])
                                        ? implode(' / ', $property['VALUE'])
                                        : $property['VALUE']
                                    ) . '</span></div>';
                                if ($property['CODE'] == 'STATUS'):
                                    $current .= '</div>';
                                endif;
                                $strAllProps .= $current;

                                if (isset($arParams['MAIN_BLOCK_OFFERS_PROPERTY_CODE'][$property['CODE']])) {
                                    $strMainProps .= $current;
                                }
                            endif;
                            if ($property['CODE'] == 'OLD_PRICE'):
                                $old_price =  CurrencyFormat($property['VALUE'], 'RUB');
                            endif;
                        }

                        unset($current);
                    }
                }



                $jsOffer['DISPLAY_PROPERTIES'] = $strAllProps;
                $jsOffer['DISPLAY_PROPERTIES_MAIN_BLOCK'] = $strMainProps;
                $jsOffer['OLD_PRICE'] = $old_price;
            }

            $templateData['OFFER_IDS'] = $offerIds;
            $templateData['OFFER_CODES'] = $offerCodes;
            unset($jsOffer, $strAllProps, $strMainProps, $strPriceRanges, $strPriceRangesRatio, $useRatio);

            $jsParams = array(
                'CONFIG' => array(
                    'USE_CATALOG' => $arResult['CATALOG'],
                    'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
                    'SHOW_PRICE' => true,
                    'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'] === 'Y',
                    'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'] === 'Y',
                    'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
                    'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
                    'SHOW_SKU_PROPS' => $arResult['SHOW_OFFERS_PROPS'],
                    'OFFER_GROUP' => $arResult['OFFER_GROUP'],
                    'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
                    'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
                    'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'] === 'Y',
                    'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
                    'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
                    'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
                    'USE_STICKERS' => true,
                    'USE_SUBSCRIBE' => $showSubscribe,
                    'SHOW_SLIDER' => $arParams['SHOW_SLIDER'],
                    'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
                    'ALT' => $alt,
                    'TITLE' => $title,
                    'MAGNIFIER_ZOOM_PERCENT' => 200,
                    'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
                    'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
                    'BRAND_PROPERTY' => !empty($arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']])
                        ? $arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']]['DISPLAY_VALUE']
                        : null,
                    'SHOW_SKU_DESCRIPTION' => $arParams['SHOW_SKU_DESCRIPTION'],
                    'DISPLAY_PREVIEW_TEXT_MODE' => $arParams['DISPLAY_PREVIEW_TEXT_MODE']
                ),
                'PRODUCT_TYPE' => $arResult['PRODUCT']['TYPE'],
                'VISUAL' => $itemIds,
                'DEFAULT_PICTURE' => array(
                    'PREVIEW_PICTURE' => $arResult['DEFAULT_PICTURE'],
                    'DETAIL_PICTURE' => $arResult['DEFAULT_PICTURE']
                ),
                'PRODUCT' => array(
                    'ID' => $arResult['ID'],
                    'ACTIVE' => $arResult['ACTIVE'],
                    'NAME' => $arResult['~NAME'],
                    'CATEGORY' => $arResult['CATEGORY_PATH'],
                    'DETAIL_TEXT' => $arResult['DETAIL_TEXT'],
                    'DETAIL_TEXT_TYPE' => $arResult['DETAIL_TEXT_TYPE'],
                    'PREVIEW_TEXT' => $arResult['PREVIEW_TEXT'],
                    'PREVIEW_TEXT_TYPE' => $arResult['PREVIEW_TEXT_TYPE']
                ),
                'BASKET' => array(
                    'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
                    'BASKET_URL' => $arParams['BASKET_URL'],
                    'SKU_PROPS' => $arResult['OFFERS_PROP_CODES'],
                    'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
                    'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
                ),
                'OFFERS' => $arResult['JS_OFFERS'],
                'OFFER_SELECTED' => $arResult['OFFERS_SELECTED'],
                'TREE_PROPS' => $skuProps
            );
        } else {
            $emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
            if ($arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y' && !$emptyProductProperties) {
                ?>
                <div id="<?= $itemIds['BASKET_PROP_DIV'] ?>" style="display: none;">
                    <?php
                    if (!empty($arResult['PRODUCT_PROPERTIES_FILL'])) {
                        foreach ($arResult['PRODUCT_PROPERTIES_FILL'] as $propId => $propInfo) {
                            ?>
                            <input type="hidden" name="<?= $arParams['PRODUCT_PROPS_VARIABLE'] ?>[<?= $propId ?>]"
                                   value="<?= htmlspecialcharsbx($propInfo['ID']) ?>">
                            <?php
                            unset($arResult['PRODUCT_PROPERTIES'][$propId]);
                        }
                    }

                    $emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
                    if (!$emptyProductProperties) {
                        ?>
                        <table>
                            <?php
                            foreach ($arResult['PRODUCT_PROPERTIES'] as $propId => $propInfo) {
                                ?>
                                <tr>
                                    <td><?= $arResult['PROPERTIES'][$propId]['NAME'] ?></td>
                                    <td>
                                        <?php
                                        if (
                                            $arResult['PROPERTIES'][$propId]['PROPERTY_TYPE'] === 'L'
                                            && $arResult['PROPERTIES'][$propId]['LIST_TYPE'] === 'C'
                                        ) {
                                            foreach ($propInfo['VALUES'] as $valueId => $value) {
                                                ?>
                                                <label>
                                                    <input type="radio"
                                                           name="<?= $arParams['PRODUCT_PROPS_VARIABLE'] ?>[<?= $propId ?>]"
                                                           value="<?= $valueId ?>" <?= ($valueId == $propInfo['SELECTED'] ? '"checked"' : '') ?>>
                                                    <?= $value ?>
                                                </label>
                                                <br>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <select name="<?= $arParams['PRODUCT_PROPS_VARIABLE'] ?>[<?= $propId ?>]">
                                                <?php
                                                foreach ($propInfo['VALUES'] as $valueId => $value) {
                                                    ?>
                                                    <option value="<?= $valueId ?>" <?= ($valueId == $propInfo['SELECTED'] ? '"selected"' : '') ?>>
                                                        <?= $value ?>
                                                    </option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }

            $jsParams = array(
                'CONFIG' => array(
                    'USE_CATALOG' => $arResult['CATALOG'],
                    'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
                    'SHOW_PRICE' => !empty($arResult['ITEM_PRICES']),
                    'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'] === 'Y',
                    'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'] === 'Y',
                    'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
                    'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
                    'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
                    'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
                    'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'] === 'Y',
                    'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
                    'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
                    'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
                    'USE_STICKERS' => true,
                    'USE_SUBSCRIBE' => $showSubscribe,
                    'SHOW_SLIDER' => $arParams['SHOW_SLIDER'],
                    'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
                    'ALT' => $alt,
                    'TITLE' => $title,
                    'MAGNIFIER_ZOOM_PERCENT' => 200,
                    'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
                    'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
                    'BRAND_PROPERTY' => !empty($arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']])
                        ? $arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']]['DISPLAY_VALUE']
                        : null
                ),
                'VISUAL' => $itemIds,
                'PRODUCT_TYPE' => $arResult['PRODUCT']['TYPE'],
                'PRODUCT' => array(
                    'ID' => $arResult['ID'],
                    'ACTIVE' => $arResult['ACTIVE'],
                    'PICT' => reset($arResult['MORE_PHOTO']),
                    'NAME' => $arResult['~NAME'],
                    'SUBSCRIPTION' => true,
                    'ITEM_PRICE_MODE' => $arResult['ITEM_PRICE_MODE'],
                    'ITEM_PRICES' => $arResult['ITEM_PRICES'],
                    'ITEM_PRICE_SELECTED' => $arResult['ITEM_PRICE_SELECTED'],
                    'ITEM_QUANTITY_RANGES' => $arResult['ITEM_QUANTITY_RANGES'],
                    'ITEM_QUANTITY_RANGE_SELECTED' => $arResult['ITEM_QUANTITY_RANGE_SELECTED'],
                    'ITEM_MEASURE_RATIOS' => $arResult['ITEM_MEASURE_RATIOS'],
                    'ITEM_MEASURE_RATIO_SELECTED' => $arResult['ITEM_MEASURE_RATIO_SELECTED'],
                    'SLIDER_COUNT' => $arResult['MORE_PHOTO_COUNT'],
                    'SLIDER' => $arResult['MORE_PHOTO'],
                    'CAN_BUY' => $arResult['CAN_BUY'],
                    'CHECK_QUANTITY' => $arResult['CHECK_QUANTITY'],
                    'QUANTITY_FLOAT' => is_float($arResult['ITEM_MEASURE_RATIOS'][$arResult['ITEM_MEASURE_RATIO_SELECTED']]['RATIO']),
                    'MAX_QUANTITY' => $arResult['PRODUCT']['QUANTITY'],
                    'STEP_QUANTITY' => $arResult['ITEM_MEASURE_RATIOS'][$arResult['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'],
                    'CATEGORY' => $arResult['CATEGORY_PATH']
                ),
                'BASKET' => array(
                    'ADD_PROPS' => $arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y',
                    'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
                    'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
                    'EMPTY_PROPS' => $emptyProductProperties,
                    'BASKET_URL' => $arParams['BASKET_URL'],
                    'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
                    'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
                )
            );
            unset($emptyProductProperties);
        }

        if ($arParams['DISPLAY_COMPARE']) {
            $jsParams['COMPARE'] = array(
                'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
                'COMPARE_DELETE_URL_TEMPLATE' => $arResult['~COMPARE_DELETE_URL_TEMPLATE'],
                'COMPARE_PATH' => $arParams['COMPARE_PATH']
            );
        }

        $jsParams["IS_FACEBOOK_CONVERSION_CUSTOMIZE_PRODUCT_EVENT_ENABLED"] =
            $arResult["IS_FACEBOOK_CONVERSION_CUSTOMIZE_PRODUCT_EVENT_ENABLED"];
        ?>
    </div>
    <script>
        BX.message({
            ECONOMY_INFO_MESSAGE: '<?=GetMessageJS('CT_BCE_CATALOG_ECONOMY_INFO2')?>',
            TITLE_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_ERROR')?>',
            TITLE_BASKET_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_BASKET_PROPS')?>',
            BASKET_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_BASKET_UNKNOWN_ERROR')?>',
            BTN_SEND_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_SEND_PROPS')?>',
            BTN_MESSAGE_DETAIL_BASKET_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_BASKET_REDIRECT')?>',
            BTN_MESSAGE_CLOSE: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE')?>',
            BTN_MESSAGE_DETAIL_CLOSE_POPUP: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE_POPUP')?>',
            TITLE_SUCCESSFUL: '<?=GetMessageJS('CT_BCE_CATALOG_ADD_TO_BASKET_OK')?>',
            COMPARE_MESSAGE_OK: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_OK')?>',
            COMPARE_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_UNKNOWN_ERROR')?>',
            COMPARE_TITLE: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_TITLE')?>',
            BTN_MESSAGE_COMPARE_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT')?>',
            PRODUCT_GIFT_LABEL: '<?=GetMessageJS('CT_BCE_CATALOG_PRODUCT_GIFT_LABEL')?>',
            PRICE_TOTAL_PREFIX: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_PRICE_TOTAL_PREFIX')?>',
            RELATIVE_QUANTITY_MANY: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_MANY'])?>',
            RELATIVE_QUANTITY_FEW: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_FEW'])?>',
            SITE_ID: '<?=CUtil::JSEscape($component->getSiteId())?>'
        });

        var <?=$obName?> =
        new JCCatalogElement(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
    </script>
<?php
unset($actualItem, $itemIds, $jsParams);
