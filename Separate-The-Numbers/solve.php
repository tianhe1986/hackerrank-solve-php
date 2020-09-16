<?php
function separateNumbers($s) {
    // 首位就是0，那没啥搞头
    if ($s[0] == '0') {
        echo "NO\n";
        return;
    }
    
    $n = strlen($s);
    $maxFirstLen = $n >> 1;
    
    // 根据首个数字的位数，从小到大依次查找
    for ($i = 1; $i <= $maxFirstLen; $i++) {
        $startValue = substr($s, 0, $i);
        $value = $startValue;
        $start = $i;
        
        $isOK = true;
        while ($start < $n) {
            // 每次值加1，用字符串匹配的方式查找
            $value = (intval($value) + 1) ."";
            $len = strlen($value);
            if (substr($s, $start, $len) != $value) {
                $isOK = false;
                break;
            }
            
            $start += $len;
        }
        
        if ($isOK) {
            echo "YES {$startValue}\n";
            return;
        }
    }
    
    echo "NO\n";
    return;
}
