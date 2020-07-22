<?php
// 前i个物品，当前剩余容量为j，能够得到的最大价值
function dynamic(&$bagArr, &$arr, $i, $j)
{
    if (isset($bagArr[$i][$j])) {
        return $bagArr[$i][$j];
    }
    
    if ($i == 0) {
        return $arr[$i] <= $j ? $arr[$i] : 0;
    }
    
    // 当前不取 vs 当前取
    $result = dynamic($bagArr, $arr, $i - 1, $j);
    
    if ($arr[$i] <= $j) { // 能取，则尝试取
        $temp = $arr[$i] + dynamic($bagArr, $arr, $i - 1, $j - $arr[$i]);
        if ($temp > $result) {
            $result = $temp;
        }
    }
    
    return $bagArr[$i][$j] = $result;
}

function indianJob($g, $arr) {
    $total = 0;

    foreach ($arr as $t) {
        $total += $t;
    }
    
    if ($total > 2 * $g) { // 完全不够
        return 'NO';
    }
    
    if ($total <= $g) { // 完全够了
        return 'YES';
    }
    
    // 求解背包问题，必须能达到一半的时间
    $half = $total % 2 ? ($total + 1) / 2 : $total / 2;
    
    $n = count($arr);
    
    // 容量和价值相等，能达到的最大价值
    $bagArr = [];
    
    return dynamic($bagArr, $arr, $n - 1, $g) >= $half ? 'YES' : 'NO';
}