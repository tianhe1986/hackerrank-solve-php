<?php
function decentNumber($n) {
    // 让5的数量尽可能多，因此通过调整3占据的数量，来让5占据的数量可以被3整除
    $modMap = [
        0 => 0,
        1 => 10,
        2 => 5
    ];
    
    // 需要有多少个3来进行调整
    $a = $modMap[$n % 3];
    
    // 剩余的是5的数量
    $b = $n - $a;
    if ($b < 0) { //调整失败
        echo "-1" . "\n";
    } else {
        echo str_repeat('5', $b).str_repeat('3', $a)."\n";
    }
}
