<?php
function initPrice($initPrice)
{
    $initPrice = ceil($initPrice);

    if ($initPrice >= 1000) {
        $initPrice = number_format($initPrice, 0, '', ' ');
        $initPrice .= ' ₽';
    }

    return $initPrice;
}

function esc($str){
	$data = htmlspecialchars($str);
	return $data;
}

function endSaleTimer(){
    $LastDay = strtotime('now 00:00:00')+60*60*24;
    $CurrentData = strtotime('now');
    $Delta = $LastDay - $CurrentData;

	$Hours = floor($Delta/3600);
    $Minutes = floor(($Delta - $Hours*3600)/60);
    if($Hours<10){
    	if($Minutes<10){
    		$timer = '0'.$Hours.':'.'0'.$Minutes;
    	}
    	else{
    		$timer = '0'.$Hours.':'.$Minutes;
    	}
    }
    else{
    	if($Minutes<10){
    		$timer = $Hours.':'.'0'.$Minutes;
    	}
    	else{
    		$timer = $Hours.':'.$Minutes;
    	}
    }

    return $timer;
}

function endSaleTimerHour(){
    $LastDay = strtotime('now 00:00:00')+60*60*24;
    $CurrentData = strtotime('now');
    $Delta = $LastDay - $CurrentData;

	$Hours = floor($Delta/3600);
	$Class = '';
	if($Hours<1){
		$Class = 'timer--finishing';
	}
    return $Class;
}

?>