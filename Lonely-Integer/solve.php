<?php
function lonelyinteger($a) {
    $result = 0;
    
    // 不要怂，就是异或莽
    foreach ($a as $i) {
        $result ^= $i;
    }
    
    return $result;
}
