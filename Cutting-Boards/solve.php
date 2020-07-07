<?php
function boardCutting($costy, $costx) {
    $mod = 1000000007;
    
    // 行块数
    $rowPartNum = 1;
    
    // 列块数
    $columnPartNum = 1;
    
    $arr = [];
    
    // 0表示按行切割
    foreach ($costy as $t) {
        $arr[] = [$t, 0];
    }
    
    // 1表示按列切割
    foreach ($costx as $t) {
        $arr[] = [$t, 1];
    }
    
    // 按费用从大到小排序
    usort($arr, function($a, $b){
        return $b[0] - $a[0];
    });
    
    // 按行切割，费用增加 此费用 * 列块数， 切割后行块数 + 1
    // 按列切割，费用增加 此费用 * 行块数， 切割后列块数 + 1
    $result = 0;
    foreach ($arr as $item) {
        if (0 == $item[1]) {
            $result = ($result + $item[0] * $columnPartNum) % $mod;
            $rowPartNum++;
        } else {
            $result = ($result + $item[0] * $rowPartNum) % $mod;
            $columnPartNum++;
        }
    }

    return $result;
}