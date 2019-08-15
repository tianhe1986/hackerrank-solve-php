<?php
function pairs($k, $arr) {
    $flagArr = []; // 需要检查的值
    $result = 0;
    foreach ($arr as $i) {
        if ($i > $k) { // 需要检查 i - k是否在原数组中
            $flagArr[$i - $k] = true;
        }
    }
    
    foreach ($arr as $i) {
        if (isset($flagArr[$i])) {
            $result++;
        }
    }
    
    return $result;
}
