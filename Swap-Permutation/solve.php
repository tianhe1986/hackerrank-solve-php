<?php
function swapPermutation($n, $k) {
    return [adjacentSwap($n, $k), fullSwap($n, $k)];
}

function adjacentSwap($n, $k)
{
    // 相邻的交换
    
    // 每次交换，逆序数变化1，因此针对逆序数作动态规划
    $mod = 1000000007;
    $result = 0;
    $cache = [];
    for ($t = $k; $t >= 0; $t -= 2) { // 确保奇偶性正确
        $result = ($result + adjacentDynamic($n - 1, $t, $cache)) % $mod;
    }

    return $result;
}

function adjacentDynamic($i, $j, &$cache)
{
    $mod = 1000000007;
    // 前i个，共j组逆序，总共有多少种不同的组合
    if (isset($cache[$i][$j])) {
        return $cache[$i][$j];
    }

    if (0 == $j) { // 没有逆序，自然只有一种可能
        return $cache[$i][$j] = 1;
    }
    
    if ($i == 0) { // 刚开始，不可能有多个逆序
        return $cache[$i][$j] = 0;
    }
    
    /*$result = 0;
    for ($t = 0; $t <= $j; $t++) {
        // 前i - 1个共有 j - t个逆序，本位数字放在对应的唯一位置
        if ($t >= $i + 1) { // 最多只可能增加 i + 1个逆序
            break;
        }
        $result = ($result + adjacentDynamic($i - 1, $j - $t)) % $mod;
    }*/
    
    $result = ($j >= $i + 1 ? (adjacentDynamic($i, $j - 1, $cache) + adjacentDynamic($i - 1, $j, $cache) - adjacentDynamic($i - 1, $j - 1 - $i, $cache))  % $mod  : (adjacentDynamic($i, $j - 1, $cache) + adjacentDynamic($i - 1, $j, $cache))  % $mod );
    
    return $cache[$i][$j] = $result;
}

function fullSwap($n, $k)
{
    // 全交换
    $mod = 1000000007;
    $result = 0;
    $cache = [];
    
    for ($t = $k; $t >= 0; $t--) {
        $result = ($result + fullDynamic($n - 1, $t, $cache)) % $mod;
    }

    return $result;
}

function fullDynamic($i, $j, &$cache)
{
    // 前i + 1个元素，共j次交换，总共有多少种不同的组合
    
    static $cache = [];
    $mod = 1000000007;

    if (isset($cache[$i][$j])) {
        return $cache[$i][$j];
    }
    
    if (0 == $j) { // 没有交换，自然只有一种可能
        return $cache[$i][$j] = 1;
    }
    
    if (0 == $i) { // 刚开始，没法交换
        return $cache[$i][$j] = 0;
    }
    
    
    // 要么跟前面任一元素交换，要么不交换
    $result = (fullDynamic($i - 1, $j, $cache) + $i * fullDynamic($i - 1, $j - 1, $cache)) % $mod;
    
    return $cache[$i][$j] = $result;
}
