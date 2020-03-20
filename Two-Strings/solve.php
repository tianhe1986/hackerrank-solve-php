<?php
function twoStrings($s1, $s2)
{
    // 先遍历短的，再遍历长的
    $len1 = strlen($s1);
    $len2 = strlen($s2);
    
    $min = $max = null;
    if ($len1 < $len2) {
        $min = $s1;
        $max = $s2;
    } else {
        $min = $s2;
        $max = $s1;
    }
        
    $minLen = strlen($min);
    $maxLen = strlen($max);
    
    // 记录字符是否出现过
    $flagArr = [];
    for ($i = 0; $i < $minLen; $i++) {
        $flagArr[$min[$i]] = true;
    }
    
    // 出现过相同字符，则有公共子串
    for ($i = 0; $i < $maxLen; $i++) {
        if (isset($flagArr[$max[$i]])) {
            return 'YES';
        }
    }
    
    return 'NO';
}
