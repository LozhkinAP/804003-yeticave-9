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

?>