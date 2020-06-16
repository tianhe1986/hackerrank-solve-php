<?php
function maximumToys($prices, $k) {
    $result = 0;
    
    // 排序，按价格从低到高进行购买
    sort($prices);
    foreach ($prices as $price) {
        if ($k < $price) {
            break;
        }
        
        $result++;
        $k -= $price;
    }
    
    return $result;
}
