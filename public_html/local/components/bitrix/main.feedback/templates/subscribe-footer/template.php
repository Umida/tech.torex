<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
/**
 * Bitrix vars
 *
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @global CMain $APPLICATION
 * @global CUser $USER
 */
?>


	<form action="<?=POST_FORM_ACTION_URI?>" method="POST">
		<?=bitrix_sessid_post()?>
			<?if(!empty($arResult["ERROR_MESSAGE"])) {
				foreach($arResult["ERROR_MESSAGE"] as $v) ShowError($v);
			}
			if(strlen($arResult["OK_MESSAGE"]) > 0) {?>
				<div class="h3 mf-ok-text"><?=$arResult["OK_MESSAGE"]?></div>
			<?}
			else{?>
			<div class="form-title">Подписка на рассылку</div>
			<div class="form-flex-group">
				<input type="email" name="user_ml" required="" class="bottom-border" placeholder="Введите e-mail" />
				<?if(isset($arResult["OK_MESSAGE"]) && strlen($arResult["OK_MESSAGE"]) > 0):?>
				<input type="submit" class="image-btn" name="submit" value="" disabled="">
				<?else:?>
				<input type="submit" class="image-btn" name="submit" value="">
				<?endif;?>
			</div>
            <?}?>
	
	</form>
