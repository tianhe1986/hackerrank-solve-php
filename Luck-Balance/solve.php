<?php
function luckBalance($k, $contests) {
    $n = count($contests);
    
    $result = 0;
    
    // 必须赢的数量
    $winNum = 0;
    foreach ($contests as $item) {
        if ($item[1] == 1) {
            $winNum++;
        }
    }
    $winNum -= $k;
    
    // 排序，重要且luck值小的排前面
    usort($contests, function ($a, $b){
        $diff = $b[1] - $a[1];
        return $diff != 0 ? $diff : $a[0] - $b[0];
    });
    
    for ($i = 0; $i < $winNum; $i++) { // 重要的考试中，幸运值最小的winNum个，通过
        $result -= $contests[$i][0];
    }
    
    for ($i = $winNum; $i < $n; $i++) { // 剩下的全部挂掉即可
        $result += $contests[$i][0];
    }
    
    return $result;
}