<?php
function travelAroundTheWorld($a, $b, $c) {
    $beginIndex = null;
    $n = count($b);
    
    // 预处理，并找到一个会减少油量的城市
    for ($i = 0; $i < $n; $i++) {
        if ($a[$i] > $c) { // 多加也没用
            $a[$i] = $c;
        }
        if ($b[$i] > $c) { // what?
            return 0;
        }
        if ($a[$i] < $b[$i]) {
            $beginIndex = $i;
        }
    }
    
    if ($beginIndex === null) { // 没有会减少油量的城市？随便开吧
        return $n;
    }
    
    // 往前，计算需要多少油才能渡过下个会减少油量的城市
    $needMap = [];
    
    // 用来计数，看是否已经完成循环
    $count = 0; 
    
    // 第一个
    $i = $beginIndex;
    $needMap[$i] = $b[$i] - $a[$i];
    $nowLack = true;
    $nowLackIndex = $i;
    $i--;
    if ($i < 0) {
        $i = $n - 1;
    }
    $count++;
    
    
    while (true) {
        $nowDiff = $a[$i] - $b[$i];
        if ($nowLack) { // 现在已经缺了，看前一个缺多少，往里补
            if ($nowLackIndex == $i) { // 转了一圈了
                return 0;
            }
            $pre = $needMap[($i + 1) % $n];
            if ($nowDiff >= $pre) { // 补足了，不缺
                $needMap[$i] = 0;
                $nowLack = false;
            } else {
                $needMap[$i] = $pre - $nowDiff;
                if ($needMap[$i] > $c - $a[$i]) { // 补满也过不了
                    return 0;
                }
            }
        } else {
            if ($count >= $n) { // 已经一轮了，可以处理了
                break;
            }
            // 看这个是不是缺
            if ($nowDiff < 0) { // 缺
                $needMap[$i] = -$nowDiff;
                $nowLack = true;
                $nowLackIndex = $i;
            } else {
                $needMap[$i] = 0;
            }
        }

        $i--;
        if ($i < 0) {
            $i = $n - 1;
        }
        $count++;
    }
    
    $result = 0;
    for ($i = 0; $i < $n; $i++) {
        if ($needMap[$i] == 0) {
            $result++;
        }
    }
    
    return $result;
}