<?php
function insertionSort2($n, $arr) {
    for ($i = 1; $i < $n; $i++) {
        $temp = $arr[$i];
        for ($j = $i - 1; $j >= 0; $j--) {
            if ($arr[$j] > $temp) { // 还未到正确的位置，继续向前
                $arr[$j + 1] = $arr[$j];
            } else { // 到了正确的位置， break
                break;
            }
        }
        
        // 插入正确位置
        $arr[$j + 1] = $temp;
        
        echo implode(" ", $arr) . "\n";
    }
}
