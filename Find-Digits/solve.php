<?php
function findDigits($n) {
    $temp = $n; //用于迭代找出每一位数字
    $result = 0;
    $flagArr = [ 0 => false, 1 => true]; //用于缓存每一位数字是否可被整除
    
    while ($temp > 0) {
        $mod = $temp % 10;
        $temp = ($temp - $mod) / 10;
        
        if ( ! isset($flagArr[$mod])) {
            $flagArr[$mod] = ($n % $mod == 0);
        }
        
        if ($flagArr[$mod]) {
            $result++;
        }
    }
    
    return $result;
}
