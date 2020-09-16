<?php
function beautifulPermutations($arr) {
    static $facArr = [];
    $mod = 1000000007;
    
    if (empty($facArr)) { // 阶乘缓存
        $facArr[0] = 1;
        for ($i = 1; $i <= 2000; $i++) {
            $facArr[$i] = ($i * $facArr[$i - 1]) % $mod;
        }
    }
    // 统计出现两次的数字数量和出现一次的数字数量
    $twoNum = 0;
    $oneNum = 0;
    
    $flag = [];
    foreach ($arr as $num) {
        if (isset($flag[$num])) {
            $twoNum++;
            $oneNum--;
        } else {
            $flag[$num] = true;
            $oneNum++;
        }
    }
    
    return dynamic($twoNum, $oneNum, 0, $facArr);
}

function dynamic($twoNum, $oneNum, $hasPair, &$facArr)
{
    static $cache = [];
    if (isset($cache[$twoNum][$oneNum][$hasPair])) {
        return $cache[$twoNum][$oneNum][$hasPair];
    }
    
    if ($twoNum == 0 && $oneNum == 0) {
        return $cache[$twoNum][$oneNum][$hasPair] = 1;
    }
    
    $mod = 1000000007;
    $result = 0;
    
    if ($hasPair) { // 如果前一项是从出现两次的元素中选择的一项
        if ($twoNum == 0) {
            $result = ($oneNum - 1) * $facArr[$oneNum - 1] % $mod;
        } else { 
            // 从成对的里面选择一项
            $result += $twoNum * dynamic($twoNum - 1, $oneNum + 1, 1, $facArr) % $mod;
            // 单个的，有一个值不能选，
            $result += ($oneNum - 1) * dynamic($twoNum, $oneNum - 1, 0, $facArr) % $mod;
            $result = $result % $mod;
        }
    } else { // 任意选
        if ($twoNum == 0) {
            $result = $oneNum * $facArr[$oneNum - 1] % $mod;
        } else { // 从成对的里面选择一项
            $result += $twoNum * dynamic($twoNum - 1, $oneNum + 1, 1, $facArr) % $mod;
            if ($oneNum > 0) { // 从单个的里面选一项
                $result += $oneNum * dynamic($twoNum, $oneNum - 1, 0, $facArr) % $mod;
            }
            $result = $result % $mod;
        }
    }
    
    return $cache[$twoNum][$oneNum][$hasPair] = $result;
}
