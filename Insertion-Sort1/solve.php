<?php
function insertionSort1($n, $arr) {
    //要比较的值
    $value = $arr[$n - 1];
    
    //从后向前遍历
    for ($i = $n - 2; $i >= 0; $i--) {
        if ($arr[$i] > $value) { //如果大于要插入的值，后移
            $arr[$i + 1] = $arr[$i];
            echo implode(" ", $arr)."\n";
        } else { // 找到了要插入的位置，插入并结束
            $arr[$i + 1] = $value;
            echo implode(" ", $arr)."\n";
            return;
        }
    }
    
    // 所有的元素都比要比较的值大，则插入到首位
    $arr[0] = $value;
    echo implode(" ", $arr)."\n";
}
