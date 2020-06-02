<?php
function arrayMerging($m) {
    $mod = 1000000007;
    
    $permutationMap = [];
    $n = count($m);
    
    // 用于缓存C(n, i)的结果
    for ($i = 1; $i <= $n; $i++) {
        $permutationMap[$i][0] = 1;
        $index = 1;
        for ($j = $i; $j >= 1; $j--) {
            $permutationMap[$i][$index] = ($j * $permutationMap[$i][$index - 1]) % $mod;
            $index++;
        }
    }

    $cache = [];
    for ($i = 1; $i <= $n; $i++) {
        $cache[$i][$n] = 1;
        $cache[1][$i] = 1;
    }
    
    // 是否比前一个要大，用于处理单调递增的判断
    $bigFlag = [];
    for ($i = 2; $i <= $n; $i++) {
        $bigFlag[$i] = ($m[$i - 1] > $m[$i - 2]);
    }
    $bigFlag[$n + 1] = false;
    
    // 第一轮位置直接固定，因此dynamic处理不要从0开始。
    $result = 1;
    for ($i = 2; $i <= $n; $i++) {
        if ($bigFlag[$i]){
            $result = ($result + dynamic($cache, $i, $i, $permutationMap, $m, $n, $mod, $bigFlag)) % $mod;
        } else {
            break;
        }
    }
    
    return $result;
}

function dynamic(&$cache, $nowSize, $nowBeginIndex, &$permutationMap, &$m, $n, $mod, &$bigFlag)
{
    
    if (isset($cache[$nowSize][$nowBeginIndex])) {
        return $cache[$nowSize][$nowBeginIndex];
    }
    
    $result = $permutationMap[$nowSize][1];
    for ($i = 2; $i <= $nowSize; $i++) {
        // 从nowSize中选i个任意排列, 再继续求后面的
        $next = $nowBeginIndex + $i;
        if ($bigFlag[$next]) {
            $result = ($result + $permutationMap[$nowSize][$i] * dynamic($cache, $i, $next, $permutationMap, $m, $n, $mod, $bigFlag)) % $mod;
        } else { // 不是单调递增，就没得选了
            break;
        }
    }

    return $cache[$nowSize][$nowBeginIndex] = $result;
}
