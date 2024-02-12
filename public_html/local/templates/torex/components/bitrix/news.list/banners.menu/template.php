<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="banners-list">
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<div class="banners-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
	    <div class="banner_text">
	        <div class="banner_text__title"><?echo $arItem["NAME"]?></div>
	        <div class="banner_text__desc"><?echo $arItem["PREVIEW_TEXT"];?></div>
	        <? if ($arItem["PROPERTIES"]["LINK"]["VALUE"]):?>
	            <a class="banner_text__link" data-svg="circle-arrow"> href="<?=$arItem["PROPERTIES"]["LINK"]["VALUE"];?>"></a>
	        <? endif;?>
	    </div>
	    <div class="banner_picture">
	        <img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>" title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>">
	    </div>
	</div>
<?endforeach;?>
</div>
