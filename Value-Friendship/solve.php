<?php
function valueOfFriendsship($n, $friendships) {
    // 缓存单个及累加数量
    static $singleCache = [];
    static $accuCache = [];
    if (empty($singleCache)) {
        $singleCache[2] = $accuCache[2] = 2;
        for ($i = 3; $i <= 100000; $i++) {
            $temp = $i * ($i - 1);
            $singleCache[$i] = $temp;
            $accuCache[$i] = $accuCache[$i - 1] + $temp;
        }
    }
    
    $m = count($friendships);
    
    // 父节点数组
    $parentArr = [];
    
    // 当前组内数量
    $groupNumArr = [];
    
    foreach ($friendships as $item) {
        $group1 = getParent($parentArr, $item[0]);
        $group2 = getParent($parentArr, $item[1]);
        if ($group1 != $group2) { // 合并成同一组
            $groupNumArr[$group1] = (isset($groupNumArr[$group1]) ? $groupNumArr[$group1] : 1) + (isset($groupNumArr[$group2]) ? $groupNumArr[$group2] : 1);
            unset($groupNumArr[$group2]);
            $parentArr[$group2] = $group1;
        }
    }
    
    $result = 0;
    
    // 排序，从大到小遍历
    sort($groupNumArr);
    
    $remainNum = $m;
    for ($i = count($groupNumArr) - 1; $i >= 0; $i--) {
        $value = $groupNumArr[$i];
        $remainNum -= ($value - 1);
        // 增加对应的贡献量
        $result += $accuCache[$value] + $remainNum * $singleCache[$value];
    }

    return $result;
}

function getParent(&$parentArr, $index)
{
    return ! isset($parentArr[$index]) ? $index : getParent($parentArr, $parentArr[$index]);
}
