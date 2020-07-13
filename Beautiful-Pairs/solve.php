<?php
function beautifulPairs($a, $b) {
    $result = 0;
    
    $left = array_fill(0, 1001, 0);
    $right = array_fill(0, 1001, 0);
    
    // 数组长度
    $total = count($a);
    
    foreach ($a as $num) {
        $left[$num]++;
    }
    
    foreach ($b as $num) {
        $right[$num]++;
    }
    
    for ($i = 1; $i <= 1000; $i++) {
        if ($left[$i] > $right[$i]) {
            $result += $right[$i];
        }  else {
            $result += $left[$i];
        }
    }
    
    if ($result == $total) { // 全部配对了，只能拆一对
        $result--;
    } else { // 多凑一对
        $result++;
    }
    
    return $result;
}
