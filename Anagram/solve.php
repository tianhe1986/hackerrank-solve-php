<?php
function anagram($s) {
    $len = strlen($s);
    
    if ($len % 2 == 1) { // 奇数，不可能
        return -1;
    }
    
    // 前后半段出现字符计数
    $frontArr = [];
    $afterArr = [];
    
    for ($i = 0, $j = $len - 1; $i <= $j; $i++, $j--) {
        if ( ! isset($frontArr[$s[$i]])) {
            $frontArr[$s[$i]] = 1;
        } else {
            $frontArr[$s[$i]]++;
        }
        
        if ( ! isset($afterArr[$s[$j]])) {
            $afterArr[$s[$j]] = 1;
        } else {
            $afterArr[$s[$j]]++;
        }
    }
    
    // 以frontArr遍历，计算多出来的次数之和即可
    $result = 0;
    foreach ($frontArr as $letter => $num) {
        if ( ! isset($afterArr[$letter])) {
            $result += $num;
        } else if ($num > $afterArr[$letter]) {
            $result += $num - $afterArr[$letter];
        }
    }
    
    return $result;
}
