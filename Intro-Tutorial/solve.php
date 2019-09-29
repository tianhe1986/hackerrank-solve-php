<?php
function introTutorial($V, $arr) {
    //二分查找，也没啥说的
    $low = 0;
    $high = count($arr) - 1;
    while ($low <= $high) {
        $mid = intval(($low + $high)/2); //始终拿中点比较
        if ($arr[$mid] == $V) { //中点就是要找的值
            return $mid;
        } else if ($arr[$mid] < $V) { // 在大的一半里继续找
            $low = $mid + 1;
        } else { // 在小的一半里继续找
            $high = $mid - 1;
        }
    }
}
