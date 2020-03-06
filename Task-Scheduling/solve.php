<?php
function taskScheduling($d, $m)
{
    // 保存二元组, [deadline， 往前已经占用的时间]
    static $list = [];
    
    // 当前最大超出时间
    static $maxExceed = 0;
    
    // 二分查找找到一个合适的位置
    $index = binarySearch($list, $d);
    
    // trick，如果当前deadline已被占用，直接累加，理论上应该是先插入再前推，可以减少一次前推删除操作
    if (isset($list[$index]) && ($list[$index][0] - $list[$index][1] <= $d)) {
        $list[$index][1] += $m;
    } else { // 否则，插入
        array_splice($list, $index, 0, [[$d, $m]]);
    }
    
    
    // 不断前推，直到与前一个之间没有填充满，或是到头为止
    $deleteCount = 0;
    for ($processIndex = $index - 1; $processIndex >= 0; $processIndex--) {
        if ($list[$index][0] - $list[$index][1] > $list[$processIndex][0]) {
            break;
        }
        
        $list[$index][1] += $list[$processIndex][1];
        $deleteCount++;
    }
    
    // 删除以上遍历过程中填充满的项
    if ($deleteCount > 0) {
        array_splice($list, $index - $deleteCount, $deleteCount);
    }
    
    // 处理第一项，更新超出时间
    if ($list[0][1] > $list[0][0]) {
        $maxExceed += ($list[0][1] - $list[0][0]);
        $list[0][1] = $list[0][0];
    }
    
    return $maxExceed;
}

function binarySearch(&$list, $value)
{
    $low = 0;
    $high = count($list) - 1;
    while ($low <= $high) {
        $middle = intval(($low + $high) / 2);
        
        if ($list[$middle][0] == $value) {
            return $middle;
        } else if ($list[$middle][0] < $value) {
            $low = $middle + 1;
        } else {
            $high = $middle - 1;
        }
    }
    
    return $low;
}
