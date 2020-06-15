<?php
function xorAndSum($a, $b) {
    // 初始化gmp值
    $t1 = gmp_init($a, 2);
    $t2 = gmp_init($b, 2);
    
    $result = gmp_xor($t1, $t2);
    $mod = gmp_init(1000000007);
    // 就是莽！
    for ($i = 1; $i <= 314159; $i++) {
        $t2 = gmp_mul($t2, 2);
        $temp = gmp_xor($t1, $t2);
        $result = gmp_mod(gmp_add($result, $temp), $mod);
    }
    
    return intval(gmp_strval($result, 10));

}
