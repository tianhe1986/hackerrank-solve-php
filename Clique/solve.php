<?php
function clique($n, $m) {
    // 根据Turán's graph，二分查找，找出k
    $low = 1;
    $high = $n;
    
    while ($low <= $high) {
        $middle = intval(($low + $high) / 2);
        
        $mod = $n % $middle;
        $ceil = intval(ceil($n / $middle));
        $floor = intval(floor($n / $middle));
        
        // 如果最大的clique数量为 k， 求允许的边数最大值
        $value = $n * ($n - 1) / 2 - $mod * $ceil * ($ceil - 1) / 2 - ($middle - $mod) * $floor * ($floor - 1) / 2;
        
        if ($m == $value) { // 正好达到数量，直接返回
            return $middle;
        } else if ($m > $value) { // 边数超了，说明必然有更大的 clique
            $low = $middle + 1;
        } else { // 没有超，可能还能更小
            $high = $middle - 1;
        }
    }
    
    return $low;
}
