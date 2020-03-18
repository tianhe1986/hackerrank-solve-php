<?php
function alternate($s) {
    // 所有出现过的字母
    $alphaSet = [];
    
    $n = strlen($s);
    $beginLetter = ord('a');
    for ($i = 0; $i < $n; $i++) {
        $alphaSet[ord($s[$i]) - $beginLetter] = true;
    }
    
    $alphaList = array_keys($alphaSet);
    $alterMap = [];
    foreach ($alphaList as $v1) {
        foreach ($alphaList as $v2) {
            if ($v1 < $v2) {
                $alterMap[$v1][$v2] = [0, 0]; // 第一个值，类型，0表示初始化， 第二值，当前长度
            }
        }
    }
    
    for ($i = 0; $i < $n; $i++) {
        $nowLetter = ord($s[$i]) - $beginLetter;
        foreach ($alphaList as $letter) {
            if ($letter == $nowLetter) {
                continue;
            }
            
            // 当前字母是否更小
            $isNowSmall = $nowLetter < $letter;
            
            if ($isNowSmall) { // 当前更小， 检查之前是否是小的后面跟着大的， 即1
                if ($alterMap[$nowLetter][$letter][0] == 1 || $alterMap[$nowLetter][$letter][0] == 0) {
                    // 记录当前是大的后面跟着小的，数量 + 1
                    $alterMap[$nowLetter][$letter][0] = 2;
                    $alterMap[$nowLetter][$letter][1]++;
                } else { // 否则，无法组成满足条件的结果
                    $alterMap[$nowLetter][$letter][0] = -1;
                }
            } else { // 当前更大， 检查之前是否是大的后面跟着小的， 即2
                if ($alterMap[$letter][$nowLetter][0] == 2 || $alterMap[$letter][$nowLetter][0] == 0) {
                    // 记录当前是小的后面跟着大的，数量 + 1
                    $alterMap[$letter][$nowLetter][0] = 1;
                    $alterMap[$letter][$nowLetter][1]++;
                } else { // 否则，无法组成满足条件的结果
                    $alterMap[$letter][$nowLetter][0] = -1;
                }
            }
        }
    }
    
    $result = 0;
    
    // 遍历，找出最大的
    foreach ($alphaList as $v1) {
        foreach ($alphaList as $v2) {
            if ($v1 < $v2) {
                if ($alterMap[$v1][$v2][0] != -1 && $alterMap[$v1][$v2][1] > $result) {
                    $result = $alterMap[$v1][$v2][1];
                }
            }
        }
    }
    
    return $result;
}
