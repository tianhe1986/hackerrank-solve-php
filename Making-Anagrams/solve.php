<?php
function makingAnagrams($s1, $s2) {
    $start = ord('a');
    
    $a = array_fill(0, 26, 0);
    $b = array_fill(0, 26, 0);
    
    $n1 = strlen($s1);
    $n2 = strlen($s2);
    
    // 统计各字符串中各字母出现次数
    for ($i = 0; $i < $n1; $i++) {
        $a[ord($s1[$i]) - $start]++;
    }
    
    for ($i = 0; $i < $n2; $i++) {
        $b[ord($s2[$i]) - $start]++;
    }
    
    $result = 0;
    for ($i = 0; $i < 26; $i++) {
        // 累加上每个字母出现次数之差
        $result += abs($a[$i] - $b[$i]);
    }
    
    return $result;
}
