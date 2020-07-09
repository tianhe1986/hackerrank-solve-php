<?php
function toys($w) {
    $flagMap = [];
    
    // 记录每个重量是否出现
    foreach ($w as $price) {
        $flagMap[$price] = true;
    }
    
    $num = 0;
    $end = -1;
    
    for ($i = 0; $i <= 10000; $i++) {
        if ( ! isset($flagMap[$i])) { // 未出现的，路过
            continue;
        }
        
        if ($i > $end) { // 此重量超出了当前覆盖的范围，则需要重新花钱购买，并更新范围
            $num++;
            $end = $i + 4;
        }
    }
    
    return $num;
}
