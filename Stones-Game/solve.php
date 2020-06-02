<?php
function half($n) {
    // n是奇数，直接把第一堆拿走就好
    if ($n % 2 == 1) {
        return 1;
    }
    
    // 最后一堆的 Grundy值 xor 1
    $need = (intval(floor(log($n, 2))) + 1) ^ 1;

    
    // 最高位为1，后面为0，是需要做减法的值a
    // 快速计算就是 log2取整，再乘方回来
    $a = intval(pow(2, intval(floor(log($need, 2)))));
    
    // need异或要做减法的值，就是要减到的值b
    $b = $need ^ $a;
    
    // 如果两者相差1，a对应最小值的一半，即b对应的最小值
    if ($a == $b + 1) {
        return intval(pow(2, $b - 1));
    }

    // 否则，a对应的最小值-b对应的最大值
    return intval(pow(2, $a - 1)) - intval(pow(2, $b)) + 1;
}
