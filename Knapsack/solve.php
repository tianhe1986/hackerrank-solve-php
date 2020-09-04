<?php
function unboundedKnapsack($k, $arr) {
    $n = count($arr);
    
    // dp[i]表示容积为i的背包，能够取得的最大容积。
    $dp = array_fill(0, $k + 1, 0);
    
    // 最小到大排序，便于容积超出时直接遍历下个容积
    sort($arr);
    
    for ($i = 0; $i <= $k; $i++) {
        foreach ($arr as $c) {
            if ($c > $i) { // 容积超了
                break;
            }
            
            $temp = $c + $dp[$i - $c];
            if ($temp > $dp[$i]) { // 尝试找更大的组合
                $dp[$i] = $temp;
            }
        }
    }
    
    return $dp[$k];
}