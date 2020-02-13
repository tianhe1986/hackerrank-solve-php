<?php
function kmp($x) {
    $arr = [];
    
    // 出现数量最少的字母
    $minNumIndex = null;
    $minNum = 1000005;
    
    // 所有可用字母中，最小的
    $minAlphaIndex = null;
    for ($i = 0; $i < 26; $i++) {
        if ($x[$i] == 0) {
            continue;
        }
        
        if (null === $minAlphaIndex) {
            $minAlphaIndex = $i;
        }
        
        if ($x[$i] < $minNum) {
            $minNum = $x[$i];
            $minNumIndex = $i;
        }
        
        $arr[] = [$i, $x[$i]];
    }
    
    $beginOrd = ord('a');
    
    // 只有一个字母，那没啥可说的
    $count = count($arr);
    if (1 == $count) {
        return str_repeat(chr($beginOrd + $arr[0][0]), $arr[0][1]);
    }
    
    $result = '';
    // 如果数量最小的跟所有字母中最小的是同一个，数量最小的字母放一个在头部， 然后一个最小的字母，一个次小的字母，不断交替，直到最小的字母数量用尽为止
    if ($minAlphaIndex == $minNumIndex) {
        $firstChar = chr($beginOrd + $arr[0][0]);
        $secondChar = chr($beginOrd + $arr[1][0]);
        
        // 放一个在头部
        $result .= $firstChar;
        
        // 最小和次小的交替
        $result .= str_repeat($firstChar.$secondChar, $arr[0][1] - 1);
        
        // 次小的剩余量
        $result .= str_repeat($secondChar, $arr[1][1] - $arr[0][1] + 1);
        
        // 后面的按序摆上
        for ($i = 2; $i < $count; $i++) {
            $item = $arr[$i];
            $char = chr($beginOrd + $item[0]);
            $result .= str_repeat($char, $item[1]);
        }
    } else { // 如果不同，数量最小的字母放一个在头部，然后剩下的全按顺序排
        $result .= chr($beginOrd + $minNumIndex);
        
        for ($i = 0; $i < $count; $i++) {
            $item = $arr[$i];
            $char = chr($beginOrd + $item[0]);
            $result .= str_repeat($char, ($item[0] == $minNumIndex ? $item[1] - 1 : $item[1]));
        }
    }
    
    return $result;
}
