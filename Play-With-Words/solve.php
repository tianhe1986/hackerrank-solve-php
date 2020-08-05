<?php
function playWithWords($s) {
    $cache = [];
    $n = strlen($s);
    
    $result = 0;
    for ($i = 0; $i < $n - 1; $i++) {
        // 前半部分的最长回文长度 * 后半部分最长回文长度
        $temp = maxPalindromic($cache, $s, 0, $i) * maxPalindromic($cache, $s, $i + 1, $n - 1);
        if ($temp > $result) {
            $result = $temp;
        }
    }
    
    return $result;
}

function maxPalindromic(&$cache, $s, $start, $end)
{
    if (isset($cache[$start][$end])) {
        return $cache[$start][$end];
    }
    
    if ($start > $end) {
        return $cache[$start][$end] = 0;
    }
    
    if ($start == $end) { // 只有一个字符，就它自己了
        return $cache[$start][$end] = 1;
    }
    
    if ($s[$start] == $s[$end]) { // 首末相同，继续遍历中间部分
        return $cache[$start][$end] = 2 + maxPalindromic($cache, $s, $start + 1, $end - 1);
    }
    
    // 取舍弃首字母和舍弃尾字母中的更大值
    $result = maxPalindromic($cache, $s, $start + 1, $end);
    
    $temp = maxPalindromic($cache, $s, $start, $end - 1);
    
    if ($temp > $result) {
        $result = $temp;
    }
    
    return $cache[$start][$end] = $result;
}
