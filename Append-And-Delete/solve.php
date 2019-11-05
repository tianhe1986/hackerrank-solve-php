<?php
function appendAndDelete($s, $t, $k) {
    $lens = strlen($s);
    $lent = strlen($t);
    
    if ($k >= ($lens + $lent)) { // 如果比两个字符串长度加起来还长，必定可以
        return 'Yes';
    }
    
    $minLen = $lens > $lent ? $lent : $lens;
    $commonPrefixLen = 0;
    for ($i = 0; $i < $minLen; $i++) {
        if ($s[$i] == $t[$i]) {
            $commonPrefixLen++;
        } else {
            break;
        }
    }
    
    // 至少需要移除和添加的个数
    $leastStep = $lens - $commonPrefixLen + $lent - $commonPrefixLen;
    if ($leastStep > $k) { // 步数不够，无法完成
        return 'No';
    }
    
    // 多余的步数必须为偶数
    return (($k - $leastStep) % 2 == 0) ? 'Yes' : 'No';
}
