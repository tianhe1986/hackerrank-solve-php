<?php
function superReducedString($s) {
    
    $n = strlen($s);
    
    // 用栈处理
    $stack = [];
    $nowIndex = -1;
    
    for ($i = 0; $i < $n; $i++) {
        // 如果当前字母与栈顶字母不相同，则入栈
        if ($nowIndex < 0 || $stack[$nowIndex] != $s[$i]) {
            $stack[++$nowIndex] = $s[$i];
        } else { // 否则弹栈
            $nowIndex--;
        }
    }
    
    $result = '';
    
    // 将栈中字母组合输出
    for ($i = 0; $i <= $nowIndex; $i++) {
        $result .= $stack[$i];
    }
    
    return empty($result) ? 'Empty String' : $result;
}
