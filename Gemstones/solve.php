<?php
function gemstones($arr) {
    $n = count($arr);
    // 用于计数
    $numMap = array_fill(0, 26, 0);
    $initOrd = ord('a');
    
    foreach ($arr as $str) {
        // 该字符串中某个字符是否出现
        $tempNumArr = array_fill(0, 26, 0);
        
        for ($i = 0, $len = strlen($str); $i < $len; $i++) {
            $tempNumArr[ord($str[$i]) - $initOrd] = 1;
        }
        
        // 出现了，计数会加1
        foreach ($tempNumArr as $i => $value) {
            $numMap[$i] += $value;
        }
    }
    
    $result = 0;
    foreach ($numMap as $num) {
        if ($num == $n) { // 计数与n相同的，说明在全部字符串中出现了
            $result++;
        }
    }
    
    return $result;
}
