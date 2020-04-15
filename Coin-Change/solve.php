<?php
function getWays($n, $c) {
    // 将硬币排序
    sort($c);

    return dynamic($c, $n, count($c) - 1);
}

function dynamic(&$c, $need, $maxIndex)
{
    static $cache = [];
    
    if ($need == 0) { // 没有其他选择了
        return 1;
    }
    
    // 缓存命中，直接返回
    if (isset($cache[$need][$maxIndex])) {
        return $cache[$need][$maxIndex];
    }
    
    $result = 0;
    $nowCoin = $c[$maxIndex];
    if ($maxIndex == 0) { // 只能用最小的硬币了
        if ($need % $nowCoin == 0) { // 能整除，才有一种解法
            $result = 1;
        }
    } else {
        $nextIndex = $maxIndex - 1;
        $tempNeed = $need;
        while ($tempNeed >= 0) { // 依次取0，1，2，...个当前最大值硬币
            $result += dynamic($c, $tempNeed, $nextIndex);
            $tempNeed -= $nowCoin;
        }
    }
    
    return $cache[$need][$maxIndex] = $result;
}
