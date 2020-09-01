<?php
function unboundedKnapsack($k, $arr) {
    $n = count($arr);
    // dp[i][j]表示只从 0 - i中物品中挑选， 容积为j时能够取得的最大值
    // 则 dp[i][j] = max(dp[i-1][j], arr[i] + dp[i][j - arr[i]])
    $dp = [];
    
    // 只取0号物品，所有容积下能够取得的最大值
    for ($i = 0; $i <= $k; $i++) {
        $dp[0][$i] = $i - $i % $arr[0];
    }
    
    dynamic($dp, $arr, $n - 1, $k);
    return $dp[$n-1][$k];
}

function dynamic(&$dp, &$arr, $i, $j)
{
    if (isset($dp[$i][$j])) {
        return $dp[$i][$j];
    }
    
    // 不选 vs 选一个继续迭代
    $result = dynamic($dp, $arr, $i - 1, $j);
    
    if ($arr[$i] <= $j) {
        $temp = $arr[$i] + dynamic($dp, $arr, $i, $j - $arr[$i]);
        if ($temp > $result) {
            $result = $temp;
        }
    }
    
    return $dp[$i][$j] = $result;
}
