<?php
function beautifulBinaryString($b) {
    $result = 0;
    
    $n = strlen($b);
    
    // 状态 表示当前已匹配字符数
    $state = 0;
    
    for ($i = 0; $i < $n; $i++) {
        if ($b[$i] == '0') {
            if ($state == 2) { // 前面已经有01匹配上了， 更改为1，再继续下一个
                $result++;
                $state = 0;
            } else {  // 匹配上第一个0
                $state = 1;
            }
        } else {
            if ($state == 1) { // 前面已经匹配了一个0
                $state = 2;
            } else {
                $state = 0; // 重新开始
            }
        }
    }
    
    return $result;
}
