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

<div class="mfeedback">
    <h2>Ваша заявка принята</h2>
    <span>Мы перезвоним вам в&nbsp;течение 10 минут</span><br/>
    <button type="button" data-dismiss="modal" aria-label="Close" class="header__redbtn-style">Вернуться в каталог</button>
</div>
<style>
    .mfeedback {
        padding: 60px;
        text-align: center;
    }
    .mfeedback h2{
        font-size: 30px;
        margin-bottom: 12px;
    }
    .mfeedback button{
        margin-top: 60px;
        border-radius: 2px;
        border-radius: 5px;
        margin-left: 0;
        font-size: 14px;
    }
</style>