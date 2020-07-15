<?php
function chocolateInBox($arr) {
    $g = 0;
    
    // 计算grundy异或值
    foreach ($arr as $num) {
        $g = $g ^ $num;
    }
    
    // 根本赢不了一郎
    if ($g == 0) {
        return 0;
    }

    // 最高位的1
    $move = -1;
    while ($g > 0) {
        $move++;
        $g = $g >> 1;
    }
    
    $need = 1 << $move;
    
    // 对应最高位为1，则可以通过拿取巧克力改变需要的g值
    $result = 0;
    foreach ($arr as $num) {
        if ($num & $need) {
            $result++;
        }
    }
    
    return $result;
}
