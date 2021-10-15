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


$day = "";
if($arResult["PROPERTIES"]["DATE_END"]["VALUE"]){
   $create = date("Y-m-d");
   $delete = date("Y-m-d", strtotime($arResult["PROPERTIES"]["DATE_END"]["VALUE"]));
   $remain = ( strtotime($delete) - strtotime ($create) ) / 86400;
 if($remain < 0){$remain = 0;}
	$remain .= " дн.";
   $date_end = date("d.m.Y", strtotime($arResult["PROPERTIES"]["DATE_END"]["VALUE"]));
}
$date_start = date("d.m.Y", strtotime($arResult["DATE_ACTIVE_FROM"]));

if($arResult["PROPERTIES"]["STATUS"]["VALUE"] == "Открыто"){
   $ostalos_1 = "<span class='small'>Осталось</span><span class='big'>{$remain}</span>";

   $ostalos_2 = "<span class='small'>Осталось</span><span class='big'>".number_format($arResult["OSTALOS"], 0, '', ' ')."</span>";

   $btn = "<a href='#form_oplata' class='progressbar__btn' >Пожертвовать</a>";
	$form_oplata = 1;
}else{
   $btn = "<a href=''style='pointer-events: none;' class='progressbar__btn' >Сбор завершен</a>";
	$form_oplata = 0;
}


if($GLOBALS["SITE_ID"] == "s1"){ // Храм
	$color = "blue";
}

if($GLOBALS["SITE_ID"] == "s2"){ // Школа
	$color = "red";
}

if($GLOBALS["SITE_ID"] == "c3"){ // Златоуст
	$color = "yellow";
}
?>
<div class="teacher-item collecting <?=$color?>">
  <div class="teacher-item__wrap">
  <div class="teacher-item__img"><div><img src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" alt=""></div>
<?=$btn?>
<div class="progressbar-wrap">
<div class="progressbar-wrap">
<?php if($arResult["PROPERTIES"]["VIEW_DATE"]["VALUE"] == "Да"){ ?>
          <div class="progressbar__bottom">
             <div class="progressbar__column">
               <span class="small">Начало</span>
               <span class="big"><?=$date_start?></span>
             </div>
             <div class="progressbar__column"><?=$ostalos_1?></div>
             <div class="progressbar__column">
             	<span class="small">Окончание</span>
             	<span class="big"><?=$date_end?></span>
             </div>
          </div>
<br/>
<?php } ?>
<?php if($arResult["PROPERTIES"]["VIEW_SUM"]["VALUE"] == "Да"){ ?>
               <div class="progressbar">
                  <span style="width: <?=$arResult["SUM_PROCH"]?>%" class="progress"></span><span class="number"><?=$arResult["SUM_PROCH_VIEW"]?>%</span>
               </div>
               <div class="progressbar__bottom">
                 <div class="progressbar__column">
                   <span class="small">Собрано</span>
                   <span class="big"><?=number_format($arResult["SUM"], 0, '', ' ')?></span>
                 </div>
                 <div class="progressbar__column"><?=$ostalos_2?></div>
                 <div class="progressbar__column">
                  <span class="small">Цель</span>
                  <span class="big"><?=number_format($arResult["PROPERTIES"]["SUM"]["VALUE"], 0, '', ' ')?></span>
                 </div>
               </div>
<?php } ?>

</div><!-- 3 -->
</div><!-- 2 -->
<?php
if($arResult["MONEY"] != ""){
  setlocale(LC_MONETARY, 'ru_RU');
  $kol = 0;
  foreach ($arResult["MONEY"] as $k => $v) {
     $kol++;
     if($kol == 7){
        $html_money .= "<div class='money_list_item mobile' onClick='money_list_item()'>Показать все пожертвования</div>";
        $html_money_hide = "<div class='money_list_item hide' onClick='money_list_item()'>Свернуть</div>";
     }
      if($v["TEXT"] != ""){
         $v["TEXT"] = "<div class='money_list_item_text'>".$v["TEXT"]."</div>";
      }
     $v["SUM"] = format_price($v["SUM"]);
     $html_money .= "<div class='money_list_item'>
        <div class='money_list_item_wrap'>
        <div class='money_list_item_time'>{$v["DATE"]}</div><div class='money_list_item_money'>{$v["SUM"]}</div>
        </div>
        {$v["TEXT"]}
     </div>";
  }
  echo "<div class='money_list_wrap'><div class='money_list_zag'>Сердечно благодарим наших жертвователей!</div>{$html_money}{$html_money_hide}</div>";
}

function format_price($value, $unit = 'руб.')
{
  if ($value > 0) {
     $value = number_format($value, 2, ',', ' ');
     $value = str_replace(',00', '', $value);

     if (!empty($unit)) {
        $value .= ' ' . $unit;
     }
  } else {
     $value = '0';
  }

  return $value;
}

?>
<script>
   function money_list_item(){
      $(".money_list_wrap .money_list_item").toggleClass("active");
      $('html, body').animate({
        scrollTop: $(".money_list_wrap").offset().top
       }, {
        duration: 200,   // по умолчанию «400»
        easing: "linear" // по умолчанию «swing»
       });
   }
</script>
</div><!-- 1 -->

  <div class="teacher-item__text-wrap">
    <h1 class="inner-title"><?=$arResult["NAME"];?></h1>
    <div><?=$arResult["DETAIL_TEXT"];?></div>
	 <?if(!empty($arResult["DISPLAY_PROPERTIES"]["MORE_PHOTO"]["DISPLAY_VALUE"])){?>
	 <div class="news_slider">
	 <?if(is_array($arResult["DISPLAY_PROPERTIES"]["MORE_PHOTO"]["DISPLAY_VALUE"])){?>
	 	<?foreach($arResult["DISPLAY_PROPERTIES"]["MORE_PHOTO"]["FILE_VALUE"] as $file){?>
	 		<div class="slide">
	 			<div class="slide_inner">
	 				<a data-fancybox="gallery" href="<?=$file["SRC"]?>">
	 					<div class="img" title="<?=$file["DESCRIPTION"]?>" style="background-image: url('<?=$file["SRC"]?>');"></div>
	 				</a>
	 			</div>
	 		</div>
	  	<? } ?>
	 		<?}else{?>
	 			<div class="slide">
	 				<div class="slide_inner">
	 					<a data-fancybox="gallery" href="<?=$arResult["DISPLAY_PROPERTIES"]["MORE_PHOTO"]["FILE_VALUE"]["SRC"]?>">
	 						<div class="img" title="<?=$arResult["DISPLAY_PROPERTIES"]["MORE_PHOTO"]["FILE_VALUE"]["DESCRIPTION"]?>" style="background-image: url('<?=$arResult["DISPLAY_PROPERTIES"]["MORE_PHOTO"]["FILE_VALUE"]["SRC"]?>');"></div>
	 					</a>
	 				</div>
	 			</div>
	  	<? } ?>
	 </div>
	 <?}?>
  </div>
</div>
<?php
if($form_oplata){
$APPLICATION->IncludeComponent(
 "bitrix:main.include",
 "",
 Array(
     "AREA_FILE_SHOW" => "file",
     "AREA_FILE_SUFFIX" => "inc",
     "EDIT_TEMPLATE" => "",
     "SBOR_ID" => $arResult["ID"],
     "PATH" => "/local/templates/.default/include/form_oplata.php"
 )
);
}
?>
<a href="/sbor/" class="btn btn--red teacher-item__btn">Назад</a>
</div><!-- teacher-item end -->
