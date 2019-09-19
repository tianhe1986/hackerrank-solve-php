<?php

// F函数的计算结果
$globalMultipleMap = new SplFixedArray(100001);
for ($i = 1; $i <= 100000; $i++) {
    $temp = 1;
    $globalMultipleMap[$i] = new SplFixedArray(27);
    for ($j = 1; $j <= 26; $j++) {
        $temp = ($temp * $i) % 1000000007;
        $globalMultipleMap[$i][$j] = $temp;
    }
}

// 进一步计算G函数
for ($j = 1; $j <= 26; $j++) {
    $globalMultipleMap[0][$j] = 0;
    $temp = $globalMultipleMap[1][$j];
    for ($i = 2; $i <= 100000; $i++) {
        $globalMultipleMap[$i][$j] = ($temp + $globalMultipleMap[$i][$j]) % 1000000007;
        $temp = $globalMultipleMap[$i][$j];
    }
}

$s = 'aabbcceefg';
$len = count($s);
//计算每个位置及之后， 每个字母第一次出现的位置。
$letterPlaceArr = [];
$letterPlaceArr[$len - 1][ord($s[$len - 1]) - 96] = $len - 1;
for ($i = $len - 2; $i >= 0; $i--) {
    //从后向前递推
    //　假设对于 >= i +１的情况，所有字母首次出现的位置已经计算出来了
    //  则对于 >=i的情况， 其他字母首次出现的位置不变， i当前位置对应的字母设为首次出现的位置
    $letterPlaceArr[$i] = $letterPlaceArr[$i + 1];
    $letterPlaceArr[$i][ord($s[$i]) - 96] = $i;
    //按出现位置排序
    asort($letterPlaceArr[$i]);
}