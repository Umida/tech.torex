<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $item
 * @var array $actualItem
 * @var array $minOffer
 * @var array $itemIds
 * @var array $price
 * @var array $measureRatio
 * @var bool $haveOffers
 * @var bool $showSubscribe
 * @var array $morePhoto
 * @var bool $showSlider
 * @var bool $itemHasDetailUrl
 * @var string $imgTitle
 * @var string $productTitle
 * @var string $buttonSizeClass
 * @var string $discountPositionClass
 * @var string $labelPositionClass
 * @var CatalogSectionComponent $component
 */
//global $delaydBasketItems;
$product_url = $item['DETAIL_PAGE_URL'].$actualItem["CODE"].'/';
global $delaydBasketItems;
$wishaction ="add";
if (is_array($delaydBasketItems) && in_array($item["ID"], $delaydBasketItems)){ $wishclass=" in_wishlist"; $wishaction ="delete"; }
?>

<div class="prods-line_item-container">
   
  <div class="prods-line_item-image">
       <button class="fav-del-btn" data-productid="<?=array_search($item['ID'], $delaydBasketItems)?>"><i data-svg="close"></i>
					</button>
                <a href="<?=$product_url?>">
                    <img border="0" src="<?=CFile::GetPath($item["PROPERTIES"]["MAIN_PHOTO_R"]["VALUE"])?>" alt="" title="">
                </a>
            </div>
           
       <div class="prods-line_item-desc" id="<?=$itemIds['PROP_DIV']?>">
                
                <div class="item-desc_props">
                    <div class="purpose">
                        <span><?=$item["PROPERTIES"]["PURPOSE"]["VALUE"];?></span>
                    </div>
                     <div class="stock <?=$actualItem["PROPERTIES"]["STATUS"]["VALUE_XML_ID"];?>">
                         <i data-svg="check"></i>
                        <span><?=$actualItem["PROPERTIES"]["STATUS"]["VALUE"];?></span>
                    </div>
                    
                </div>
                <div class="item-desc_title">
                    <a href="<?=$product_url?>"><h3 class="product-item-title"><?=$item["NAME"]?></h3></a>
                </div>
                
                <div class="item-desc_sku">
                    <span><?=$item['DISPLAY_PROPERTIES']["COLOROUTSIDE"]['DISPLAY_VALUE'];?> / <?=$item['DISPLAY_PROPERTIES']["COLORINSIDE"]['DISPLAY_VALUE'];?></span>
                </div>
            </div>
                       <div class="prods-line_item-btn">
                	<div class="prods-line_item-price">
										<?php
										$priceclass='';
										if (!empty($actualItem["PROPERTIES"]["OLD_PRICE"]["VALUE"])){
										    $priceclass=' sale_price';
										}
										
										if (!empty($price))
										{
											if ($arParams['PRODUCT_DISPLAY_MODE'] === 'N')
											{
												$unit = $measureRatio !== 1
													? "{$measureRatio} {$minOffer['ITEM_MEASURE']['TITLE']}"
													: $minOffer['ITEM_MEASURE']['TITLE']
												;
												echo Loc::getMessage(
													'CT_BCI_TPL_MESS_PRICE_SIMPLE_MODE',
													[
														'#PRICE#' => $price['PRINT_RATIO_PRICE'],
														'#UNIT#' => $unit,
													]
												);
											}
											else
											{?>
												<span  id="<?=$itemIds['PRICE']; ?>" class="price<?=$priceclass?>"><?=$price['PRINT_RATIO_PRICE']?></span>
											<?}
											if (!empty($actualItem["PROPERTIES"]["OLD_PRICE"]["VALUE"])){?>
											   <span  class="old_price"><?=CurrencyFormat($actualItem["PROPERTIES"]["OLD_PRICE"]["VALUE"], 'RUB')?></span>
										    <?}
										}
										?>
									</div>
										<div  id="<?=$itemIds['BASKET_ACTIONS']?>" class="catalog-section-btns-block" data-entity="buttons-block">
						
						<button class="add-to-cart catalog-section-item-buy-btn" id="<?=$itemIds['BUY_LINK']?>"><i data-svg="cart"></i>
						</button>
					<button class="catalog-section-item-comp-btn" id="<?=$itemIds['COMPARE_LINK']?>"><i data-svg="comp"></i>
					</button>
					</div>

							</div>

</div>