<?php
function xorAndSum($a, $b) {
    $lena = strlen($a);
    $lenb = strlen($b);
    
    // b左移能够达到的最长位数
    $totalLen = $lenb + 314159;
    
    $mod = 1000000007;
    
    // 缓存2的阶乘结果
    $facMap = [];
    $facMap[0] = 1;
    for ($i = 1; $i <= $totalLen; $i++) {
        $facMap[$i] = ($facMap[$i - 1] * 2) % $mod;
    }
    
    // 记录b所有位移后每一位上的1个数
    $sumArr = [];
    $index = $totalLen - 1;
    $sumArr[$index] = 1;
    $index--;
    // 前面部分，从前往后累加
    for ($i = 1; $i < $lenb; $i++) {
        $sumArr[$index] = $sumArr[$index + 1] + intval($b[$i]);
        $index--;
    }
    
    // 中间部分，全部一样
    for (; $index >= $lenb; $index--) {
        $sumArr[$index] = $sumArr[$index + 1];
    }
    
    // 后面部分，从后往前累加
    $sumArr[0] = intval($b[$lenb - 1]);
    $index = 1;
    for ($i = $lenb - 2; $i >= 0; $i--) {
        $sumArr[$index] = $sumArr[$index - 1] + intval($b[$i]);
        $index++;
    }
    
    $result = 0;
    $index = 0;
    // 对于a遍历，
    for ($i = $lena - 1; $i >= 0; $i--) {
        // 如果对应位是1，则加上(314160 - b所有位移后对应位1的数量) * 当前位对应的值
        if (intval($a[$i]) == 1) {
            $result = ($result + (314160 - $sumArr[$index]) * $facMap[$index]) % $mod;
        } else {
            // 如果对应位是0， 则加上b所有位移后对应位1的数量 * 当前位对应的值
            $result = ($result + $sumArr[$index] * $facMap[$index]) % $mod;
        }
        $index++;
    }
    
    // a前面补的0遍历
    for (; $index < $totalLen; $index++) {
        $result = ($result + $sumArr[$index] * $facMap[$index]) % $mod;
    }
    
    return $result;
}