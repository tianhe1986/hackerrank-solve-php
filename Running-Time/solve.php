<?php
function runningTime($arr) {
    $result = 0;
    $n = count($arr);
    
    for ($i = 1; $i < $n; $i++) { // 从前往后，对每个位置的元素进行遍历
        for ($j = $i; $j > 0; $j--) {
            if ($arr[$j] < $arr[$j - 1]) { // 比前一个元素小，继续向前交换
                $temp = $arr[$j];
                $arr[$j] = $arr[$j - 1];
                $arr[$j - 1] = $temp;
                
                $result++;
            } else { // 已经到了应到的位置
                break;
            }
        }
    }
    
    return $result;
}