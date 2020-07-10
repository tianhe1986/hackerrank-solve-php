<?php
// 最长d + 1位，值为s的数量
function cnt(&$dp, $d, $s)
{
    if (isset($dp[$d][$s])) {
        return $dp[$d][$s];
    }
        
    // 最高位依次取0, 1, ... 9，累加上对应的d - 1位，s - i * (1 << d)的数量
    $dp[$d][$s] = 0;
    for ($i = 0; $i <= 9; $i++) {
        $remain = $s - $i * (1 << $d);
        if ($remain < 0) {
            continue;
        }
        $dp[$d][$s] += cnt($dp, $d - 1, $remain);
    }
    
    return $dp[$d][$s];
}

function decibinaryNumbers($x) {
    static $dp = null;
    static $c = null;
    
    if ($x == 1) {
        return 0;
    }
    
    $maxNum = 285501;
    $maxBitLoc = 18;
    if ($dp === null) { // 只有首次才初始化
        $dp = [];
        $c = [];
        
        $dp[-1][0] = 1;
        for ($i = 1; $i < $maxNum; $i++) {
            $dp[-1][$i] = 0;
        }
        
        // 每个值对应的数量，累加
        $c[0] = cnt($dp, $maxBitLoc, 0);
        for ($i = 1; $i < $maxNum; $i++) {
            $c[$i] = $c[$i - 1] + cnt($dp, $maxBitLoc, $i);
        }
    }
    
    // 二分查找此位置对应的值
    $low = 0;
    $high = $maxNum - 1;
    $value = null;
    while ($low <= $high) {
        $mid = ($low + $high) >> 1;
        if ($c[$mid] >= $x) {
            $value = $mid;
            $high = $mid - 1;
        } else {
            $low = $mid + 1;
        }
    }

    // value的所有数，从小到大的第g个值，就是需要的结果
    $g = $x - $c[$value - 1];
    $s = $value;

    // 对应的位数,第一个数量超过g的，就是结果
    $d = null;
    if ($g == 0) {
        $d = 0;
    } else {
        $low = 0;
        $high = $maxBitLoc;
        while ($low <= $high) {
            $mid = ($low + $high) >> 1;
            if ($dp[$mid][$s] >= $g) {
                $d = $mid;
                $high = $mid - 1;
            } else {
                $low = $mid + 1;
            }
        }
    }

    // 返回字符串
    $result = '';
    for (; $d >= 0; $d--) {
        if ($s == 0) {
            $result .= '0';
            continue;
        }
        $v = 0;
        // 决定当前位置应该选几，对应值才能大于等于g
        for ($i = 0; $i <= 9; $i++) {
            $next = $s - $i * (1 << $d);
            $temp = $dp[$d - 1][$next];
            $nextv = $v;
            $v += $temp;

            if ($v >= $g) {
                $result .= $i;
                // 更新s和g继续遍历
                $g -= $nextv;
                $s = $next;
                break;
            }
        }
    }
    
    return $result;
}