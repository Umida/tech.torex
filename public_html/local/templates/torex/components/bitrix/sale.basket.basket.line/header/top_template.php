<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/**
 * @global array $arParams
 * @global CUser $USER
 * @global CMain $APPLICATION
 * @global string $cartId
 */
$compositeStub = (isset($arResult['COMPOSITE_STUB']) && $arResult['COMPOSITE_STUB'] == 'Y');
?>

		<?
		if (!$arResult["DISABLE_USE_BASKET"])
		{
			?>
			<a href="<?=$arParams['PATH_TO_BASKET']?>"><i data-svg="cart-header"></i></a>
			<?
		}

		if (!$compositeStub)
		{
			if ($arParams['SHOW_NUM_PRODUCTS'] == 'Y' && ($arResult['NUM_PRODUCTS'] > 0 || $arParams['SHOW_EMPTY_VALUES'] == 'Y'))
			{?>
				<div class="basket-line-count"><?=$arResult['NUM_PRODUCTS'];?></div>

			<?}
		}?>