<?php
function superReducedString($s) {
    
    $n = strlen($s);
    
    $afterArr = [];
    
    // 默认指向后一个字母,-1作为真正的起点
    for ($i = -1; $i < $n; $i++) {
        $afterArr[$i] = $i + 1;
    }
    
    for ($i = $n - 2; $i >= 0; $i--) {
        if (isset($s[$afterArr[$i]]) && $s[$i] == $s[$afterArr[$i]]) { // 相邻的两个字母相同，移除
            // 前一个字母指向$afterArr[$i]的下个字母
            $afterArr[$i - 1] = $afterArr[$afterArr[$i]];
        }
    }
    
    $result = '';
    
    // 从头开始，根据指向的下个位置，依次遍历，拼接字符串
    for ($index = $afterArr[-1]; $index != $n; $index = $afterArr[$index]) {
        $result .= $s[$index];
    }
    
    return $result ? $result : 'Empty String';
}
