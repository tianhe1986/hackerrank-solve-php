<?php
function taskScheduling($d, $m)
{
    // 保存三元组， [deadline, 需要时间， 到目前为止所需总时间]
    static $list = [];
    
    // 当前最大延时时间对应的索引
    static $nowMaxIndex = null;
    
    // 当前最大延时时间
    static $nowMaxValue = null;
    
    // 用于处理新的任务deadline比当前最大延时时间对应项小的情况
    static $globalAdd = 0;
    
    // 二分查找找到一个合适的插入位置
    $index = binarySearch($list, $d);
    
    if (isset($nowMaxIndex) && $index <= $nowMaxIndex && ($nowMaxValue + $globalAdd) > 0) { // 新的任务deadline比当前最大延时时间对应项小的情况
        $globalAdd += $m;
        $result = $nowMaxValue + $globalAdd;
        return $result > 0 ? $result : 0;
    }
    
    // 插入，依次处理后面的
    if (isset($list[$index]) && $list[$index][0] == $d) {
        $list[$index][1] += $m;
    } else {
        for ($i = count($list); $i > $index; $i--) {
            $list[$i] = $list[$i - 1];
        }
        $list[$index] = [$d, $m, 0];
    }

    for ($i = $index, $end = count($list) - 1; $i <= $end; $i++) {
        $list[$i][2] = ($i == 0 ? $list[$i][1] : $list[$i][1] + $list[$i - 1][2]);
        
        $diff = $list[$i][2] - $list[$i][0];
        if ( null === $nowMaxValue || $diff > $nowMaxValue) {
            $nowMaxValue = $diff;
            $nowMaxIndex = $i;
        }
    }

    // 返回
    $result = $nowMaxValue + $globalAdd;
    return $result > 0 ? $result : 0;
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
