<?php
function pokerNim($k, $c) {
    $value = 0;
    
    // 跟k无关，直接异或
    foreach ($c as $i) {
        $value ^= $i;
    }
    
    return $value != 0 ? 'First' : 'Second';
}
