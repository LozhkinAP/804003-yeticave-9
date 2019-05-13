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

function TimeRate($data){
    $timeStRate = $data;
    $RateDate = strtotime($timeStRate);
    $InitDay = strtotime('now 00:00:00');
    $CurrentData = strtotime('now');
    $Delta = $CurrentData - $RateDate;

    if (($CurrentData - $InitDay + 24*3600) < $Delta) {
        $Time = strstr($timeStRate, ' ', true).'в '.strstr($timeStRate, ' ', false);
    } else if (($CurrentData - $InitDay) < $Delta) {
        $Time = 'Вчера, в '.strstr($timeStRate, ' ', false);
    } else if (floor($Delta/3600) == 0) {
        $Time = floor($Delta/60);
        $Time = $Time.' '.get_noun_plural_form($Time, 'минута', 'минуты', 'минут').' назад';
    } else if(floor($Delta/3600) > 0) {
        $Time = floor($Delta/3600);
        $Time = $Time.' '.get_noun_plural_form($Time, 'час', 'часа', 'часов').' назад';        
    }

    return $Time;
}

function endSaleTimer($TimeEndOfLot) {

    $CurrentData = strtotime('now');
    $EndOfLot = strtotime($TimeEndOfLot);
    $Delta = $EndOfLot - $CurrentData;

	$Hours = floor($Delta/3600);
    $Minutes = floor(($Delta - $Hours*3600)/60);
    $Seconds = $Delta - $Hours*3600 - $Minutes*60;

    if($Seconds<10) {
        $Seconds = '0'.$Seconds;
    }
    if($Hours<10) {
        $Hours = '0'.$Hours;
    }
    if($Minutes<10) {
        $Minutes = '0'.$Minutes;
    }
    $timer = $Hours.':'.$Minutes.':'.$Seconds;
    return $timer;
}

function endSaleTimerHour($TimeEndOfLot){

    $CurrentData = strtotime('now');
    $EndOfLot = strtotime($TimeEndOfLot);
    $Delta = $EndOfLot - $CurrentData;

    $Hours = floor($Delta/3600);
	$Class = '';
	if($Hours<1){
		$Class = 'timer--finishing';
	}
    return $Class;
}

function CheckUrl(){
    $page_url = $_SERVER['REQUEST_URI'];
    $main_class;
    if($page_url == "/" || $page_url == "/index.php" ) {
        $main_class = "container";
    }
    echo $main_class;
}
?>