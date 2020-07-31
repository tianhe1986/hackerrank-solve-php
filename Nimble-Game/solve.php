<?php
function nimbleGame($s) {
    $result = 0;
    
    foreach ($s as $i => $value) {
        // 如果是奇数， xor 计算nimber
        if ($value % 2 == 1) {
            $result ^= $i;
        }
    }
    
    return $result ? 'First' : 'Second';
}
