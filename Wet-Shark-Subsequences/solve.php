<?php
function twoSubsequences($x, $r, $s) {
    $mod = 1000000007;
    $n = count($x);
    
    $dynamics = [];
    
    // 初始，和为0，长度为0，有且仅有一种可能
    $dynamics[0][0] = 1;
    
    $a = ($r + $s) / 2;
    $b = ($r - $s) / 2;
    
    for ($i = 0; $i < $n; $i++) {
        // newValue = value + $x[$i]，也就是以$x[$i]为结尾的和为newValue的数量
        // 从大到小遍历，若反过来遍历，处理大值时，前面的小值可能已经被覆盖掉了
        // 最大只需要处理到$a
        for ($value = $a - $x[$i], $newValue = $a; $value >= 0; $value--, $newValue--) {
            if ( ! isset($dynamics[$value])) {
                continue;
            }
            foreach ($dynamics[$value] as $len => $count) {
                if ( ! isset($dynamics[$newValue][$len + 1])) {
                    $dynamics[$newValue][$len + 1] = $count;
                } else {
                    $dynamics[$newValue][$len + 1] = ($dynamics[$newValue][$len + 1] + $count) % $mod;
                }
            }
        }
    }

    $result = 0;
    for ($i = 1; $i <= $n; $i++) {
        if ( ! isset($dynamics[$a][$i]) || ! isset($dynamics[$b][$i])) { // 没有长度为$i且满足条件的任一方，则跳过
            continue;
        }
        
        // 双方任选1个进行组合，增加$dynamics[$a][$i] * $dynamics[$b][$i]
        $temp = ($dynamics[$a][$i] * $dynamics[$b][$i]) % $mod;
        $result = ($result + $temp) % $mod;
    }
    
    return $result;
}