<?php
function bigSorting($unsorted) {
    usort($unsorted, function($a, $b) {
        // 当做数字来比较，而且没有以0开头的
        $lena = strlen($a);
        $lenb = strlen($b);
        
        $diff = $lena - $lenb;
        
        // 位数多的肯定比位数少的大
        if (0 != $diff) {
            return $diff;
        }
        
        // 一样长，从高到低比较每位数
        for ($i = 0; $i < $lena; $i++) {
            $diff = $a[$i] - $b[$i];
            if (0 != $diff) {
                return $diff;
            }
        }
        
        return 0;
    });
    
    return $unsorted;
}
