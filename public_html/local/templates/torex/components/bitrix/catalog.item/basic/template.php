<?php

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @var string $templateFolder
 * @var CBitrixComponent $component
 */

$this->setFrameMode(true);
if (isset($arResult['ITEM']))
{
	$item = $arResult['ITEM'];

	    ?>
	    <div class="viewed_item">
	    <div class="viewed_item-container">
  <div class="viewed_item-image">
      
                <a href="<?=$item["DETAIL_PAGE_URL"]?>">
                    <img border="0" src="<?=CFile::GetPath($item["PROPERTIES"]["MAIN_PHOTO_R"]["VALUE"])?>" alt="" title="">
                </a>
            </div>
            <div class="viewed_item-desc">
                
                <div class="item-desc_props">
                    <div class="purpose">
                        <span><?=$item["PROPERTIES"]["PURPOSE"]["VALUE"];?></span>
                    </div>
                </div>
                <div class="item-desc_title">
                    <a href="<?=$item["DETAIL_PAGE_URL"]?>"><?=$item["NAME"]?></a>
                </div>
            </div>

        </div>
		</div>

	<?php
}
