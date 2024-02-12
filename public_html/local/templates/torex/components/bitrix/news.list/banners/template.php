<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
$this->addExternalJs("/bitrix/components/bitrix/advertising.banner/templates/bootstrap_v4/bxcarousel.js");

?>

<div id="carousel-<?= $arResult['ID'] ?>" class="main-slider carousel slide carousel-fade" data-interval="false"
     data-wrap="true" data-pause="true" data-keyboard="true" data-ride="carousel">
    <ol class="carousel-indicators">
        <? $i = 0; ?>
        <? while ($i < count($arResult["ITEMS"])): ?>
            <li data-target="#carousel-<?= $arResult['ID'] ?>"
                data-slide-to="<?= $i ?>" <? if ($i == 0) echo 'class="active"';
            $i++ ?>></li>
        <? endwhile; ?>
    </ol>
    <? foreach ($arResult["ITEMS"] as $ind => $arItem):
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <div class="carousel-item <? if ($ind == 0) echo 'active'; ?>" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                <img src="<?= $arItem["DETAIL_PICTURE"]["SRC"] ?>" alt="" title="">
                <div class="banner-content">
                    <div class="banner-title"><span style="color:<?=$arItem["PROPERTIES"]['title_color']['VALUE']?>;"><?=$arItem["NAME"] ?></span></div>
                    <? if ($arItem["PROPERTIES"]['banner_aftertitle']):?>
                        <div class="banner-text-block"><span style="color:<?=$arItem["PROPERTIES"]['banner_aftertitle_color']['VALUE']?>;"><?=$arItem["PROPERTIES"]['banner_aftertitle']['VALUE']?></span></div>
                    <?endif;?>
                    <? if ($arItem["PROPERTIES"]['btn_text']):?>
                        <div class="btn-container">
                            <a class="banner-btn btn" href="<?=$arItem["PROPERTIES"]['banner_link']['VALUE']?>" title="" target="_self"
                           style="background-color: <?=$arItem["PROPERTIES"]['btn_bg_color']['VALUE']?>;color:<?=$arItem["PROPERTIES"]['btn_text_color']['VALUE']?>"><?=$arItem["PROPERTIES"]['btn_text']['VALUE']?></a>
                        </div>
                    <?endif;?>
                </div>
        </div>
        
    <? endforeach; ?>

    <a href="#carousel-<?= $arResult['ID'] ?>" class="carousel-control-prev" role="button" data-slide="prev">
        <span class="control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a href="#carousel-<?= $arResult['ID'] ?>" class="carousel-control-next" role="button" data-slide="next">
        <span class="control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>

    <script>
        BX("carousel-<?=$arResult['ID']?>").addEventListener("slid.bs.carousel", function (e) {
            var item = e.detail.curSlide.querySelector('.play-caption');
            if (!!item) {
                item.style.display = 'none';
                item.style.left = '-100%';
                item.style.opacity = 0;
            }
        }, false);
        BX("carousel-<?=$arResult['ID']?>").addEventListener("slide.bs.carousel", function (e) {
            var nodeToFixFont = e.target && e.target.querySelector(
                '.carousel-item.active .bx-advertisingbanner-text-title[data-fixfont="false"]'
            );
            if (BX.type.isDomNode(nodeToFixFont)) {
                nodeToFixFont.setAttribute('data-fixfont', 'true');
                BX.FixFontSize.init({
                    objList: [{
                        node: nodeToFixFont,
                        smallestValue: 10
                    }],
                    onAdaptiveResize: true
                });
            }

            var item = e.detail.curSlide.querySelector('.play-caption');
            if (!!item) {
                var duration = item.getAttribute('data-duration') || 500,
                    delay = item.getAttribute('data-delay') || 0;

                setTimeout(function () {
                    item.style.display = '';
                    var easing = new BX.easing({
                        duration: duration,
                        start: {left: -100, opacity: 0},
                        finish: {left: 0, opacity: 100},
                        transition: BX.easing.transitions.quart,
                        step: function (state) {
                            item.style.opacity = state.opacity / 100;
                            item.style.left = state.left + '%';
                        },
                        complete: function () {
                        }
                    });
                    easing.animate();
                }, delay);
            }
        }, false);
        BX.ready(function () {
            var tag = document.createElement('script');
            tag.src = "https://www.youtube.com/iframe_api";
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
        });

        function mutePlayer(e) {
            e.target.mute();
        }

        function loopPlayer(e) {
            if (e.data === YT.PlayerState.ENDED)
                e.target.playVideo();
        }

        function onYouTubePlayerAPIReady() {
            if (typeof yt_player !== 'undefined') {
                for (var i in yt_player) {
                    window[yt_player[i].id] = new YT.Player(
                        yt_player[i].id, {
                            events: {
                                'onStateChange': loopPlayer
                            }
                        }
                    );
                    if (yt_player[i].mute == true)
                        window[yt_player[i].id].addEventListener('onReady', mutePlayer);
                }
                delete yt_player;
            }
        }
    </script>
</div>
