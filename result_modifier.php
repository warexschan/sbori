<?php require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");

$arSelect = Array("ID", "PROPERTY_KUDA", "PROPERTY_SUM", "DETAIL_TEXT", "PROPERTY_KUDA", "DATE_CREATE", "ACTIVE_FROM");
$arFilter = Array("IBLOCK_ID"=>28, "ACTIVE"=>"Y", "PROPERTY_STATUS"=>"succeeded"); //
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
while($ob = $res->GetNextElement())
{
 $arFields = $ob->GetFields();

 if($arResult["ID"] == $arFields["PROPERTY_KUDA_VALUE"]){
    $mass_sum_sbor[$arFields["PROPERTY_KUDA_VALUE"]] += ceil($arFields["PROPERTY_SUM_VALUE"]);

	if($arFields["ACTIVE_FROM"] != ""){
		$arFields["DATE_CREATE"] = $arFields["ACTIVE_FROM"];
	}

	$key_date = date("YmdHis", strtotime($arFields["DATE_CREATE"]));

    $mass_money_sbor[$key_date] = array(
      "DATE" => date("d.m.Y H:i", strtotime($arFields["DATE_CREATE"])),
      "SUM" => $arFields["PROPERTY_SUM_VALUE"],
      "TEXT" => $arFields["DETAIL_TEXT"],
    );

 }
}
$arResult["MONEY"] = array_reverse($mass_money_sbor);
krsort($mass_money_sbor);
reset($mass_money_sbor);
$arResult["MONEY"] = $mass_money_sbor;
/*
echo "<div class='none warexs' style='display:none'>";
  echo "<pre>";
  print_r($mass_money_sbor);
  echo "</pre>";
echo "</div>";
*/
   if(array_key_exists($arResult["ID"],$mass_sum_sbor)){
      $pr = (int) $arResult["PROPERTIES"]["SUM"]["VALUE"] / 100;
      $arResult["SUM_PROCH_VIEW"] = round((int) $mass_sum_sbor[$arResult["ID"]] / $pr, 1);
      $arResult["SUM_PROCH"] = ($arResult["SUM_PROCH_VIEW"] > 100?100:$arResult["SUM_PROCH_VIEW"]);
      $arResult["SUM"] = $mass_sum_sbor[$arResult["ID"]];
   }else{
      $arResult["SUM"] = 0;
      $arResult["SUM_PROCH"] = 0;
      $arResult["SUM_PROCH_VIEW"] = 0;
   }

   $arResult["OSTALOS"] = ceil($arResult["PROPERTIES"]["SUM"]["VALUE"] - $arResult["SUM"]);
   $arResult["OSTALOS"] = ($arResult["OSTALOS"] < 0?0:$arResult["OSTALOS"]);



 ?>
