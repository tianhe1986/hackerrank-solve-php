<?php
function maximizingXor($l, $r) {
    $temp = $l ^ $r;
    
    // 其实储存的是最高位 + 1
    $num = 0;
    
    // 找最高位的1是哪一位，不断右移，当右移会导致变成0时，就是最高位了
    while ($temp) {
        $num++;
        $temp = $temp >> 1;
    }
    
    // i个1组成的二进制数，其实就是 2的i次方 - 1
    return (1 << $num) - 1;
}