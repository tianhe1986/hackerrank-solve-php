<?php
function minimumLoss($price)
{
    $result = pow(10, 16);
    
    arsort($price, SORT_NUMERIC); //排序
    $beforeItem = []; // 用于记录前一个元素
    
    foreach ($price as $index => $value) {
        if ( ! empty($beforeItem)) {
            if ($beforeItem[0] < $index) { // 前一个元素在原数组中更靠前， 则进行比较
                $result = min($result, $beforeItem[1] - $value);
            }
        }
        $beforeItem = [$index, $value];
    }
    
    return $result;
}
