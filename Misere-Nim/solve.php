<?php
function misereNim($s) {
    $result = 0;
    
    $count = 0;
    $sum = 0;
    
    foreach ($s as $num) {
        $count++;
        $sum += $num;
        
        $result ^= $num;
    }
    
    if ($count == $sum) { // 全是1，特殊处理
        return $count % 2 == 0 ? 'First' : 'Second';
    } else { // 否则，看Grundy值
        return $result ? 'First' : 'Second';
    }
}
