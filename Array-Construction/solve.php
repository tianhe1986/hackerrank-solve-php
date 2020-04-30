<?php
function arrayConstruction($n, $s, $k) {
    $result = [-1 => 0];
    
    $diffArr = [-1 => 0];
    
    $cache = [];
    $t = process($cache, $result, $diffArr, $n, $s, $k, $n);
    unset($result[-1]);
    return $t ? $result : [-1];
}

function process(&$cache, &$valueArr, &$diffArr, $remainNum, $remainSum, $remainDiff, $totalNum)
{
    // 最后一项了
    if ($remainNum == 1) {
        $diff = $diffArr[$totalNum - 2] + ($totalNum - 1) * ($remainSum - $valueArr[$totalNum - 2]);
        if ($diff == $remainDiff) {
            $valueArr[$totalNum - 1] = $remainSum;
            return true;
        } else {
            return false;
        }
    }
    
    $preIndex = $totalNum - $remainNum - 1;
    // 可能的最大值和可能的最小值
    $minPossible = $valueArr[$preIndex];
    $maxPossible = intval($remainSum/$remainNum);
    
    // 有缓存，直接用
    if (isset($cache[$remainNum][$remainSum][$remainDiff])) {
        return false;
    }
    
    $cache[$remainNum][$remainSum][$remainDiff] = true;
    
    // 分支定界, 可能的最大diff和最小diff
    
    // 最小diff, 平均分布
    $greatNum = $remainSum % $remainNum;
    $minDiff = 0;
    $temp = ($diffArr[$preIndex] + ($preIndex + 1) * ($maxPossible - $minPossible));
    $minDiff += ($remainNum - $greatNum) * $temp;
    $minDiff += $greatNum * ($temp + ($preIndex + 1 + $remainNum - $greatNum));
    
    // 最大diff，前面取最小，剩余最后一项
    $maxDiff = 0;
    $maxDiff += ($remainNum - 1) * $diffArr[$preIndex];
    $maxDiff += $diffArr[$preIndex] + ($totalNum - 1) * ($remainSum - $remainNum * $minPossible);
    
    if ($remainDiff < $minDiff || $remainDiff > $maxDiff) {
        return false;
    }
    
    // 从最小值到最大值遍历
    for ($next = $minPossible; $next <= $maxPossible; $next++) {
        $valueArr[$preIndex + 1] = $next;
        $diffArr[$preIndex + 1] = $diffArr[$preIndex] + ($preIndex + 1) * ($next - $minPossible);
        $tempResult = process($cache, $valueArr, $diffArr, $remainNum - 1, $remainSum - $next, $remainDiff - $diffArr[$preIndex + 1], $totalNum);
        if ($tempResult) {
            return true;
        }
    }
    
    return false;
}