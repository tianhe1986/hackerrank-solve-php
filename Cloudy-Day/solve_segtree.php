<?php
function maximumPeople($p, $x, $y, $r) {
    $townNum = count($p);
    $cloudNum = count($y);
    
    // 坐标和人气map
    $posMap = [];
    
    for ($i = 0; $i < $townNum; $i++) {
        // 如果多个镇子坐标相同，可以合并
        if ( ! isset($posMap[$x[$i]])) {
            $posMap[$x[$i]] = 0;
        }
        
        $posMap[$x[$i]] += $p[$i];
    }
    
    // 将镇按坐标排序
    ksort($posMap, SORT_NUMERIC);
    
    // 真正需要处理的town列表
    $townArr = [];
    foreach ($posMap as $k => $v) {
        $townArr[] = [$k, $v];
    }
    $townNum = count($townArr);
    
    // town对应的线段树
    $segTree = [];
    
    // 遍历云，查找起止点，并处理被云覆盖的次数
    for ($i = 0; $i < $cloudNum; $i++) {
        // 覆盖起点镇坐标
        $beginIndex = bSearch($townArr, $y[$i] - $r[$i], $townNum, true);
        // 覆盖终点镇坐标
        $endIndex = bSearch($townArr, $y[$i] + $r[$i], $townNum, false);

        // 有覆盖，则更新
        if (($beginIndex <= $endIndex) && ($endIndex >= 0 || $beginIndex < $townNum)) {
            updateSegTree($segTree, $beginIndex, $endIndex, $i, 1, 0, $townNum - 1);
        }
    }
    
    // 用来保存本来就是晴天的人数
    $result = 0;
    
    // 移除一朵云能够增加的最大值
    $maxRemoveAddNum = 0;
    $cloudAddNum = array_fill(0, $cloudNum, 0);
    
    for ($i = 0; $i < $townNum; $i++) {
        $searchResult = querySegTree($segTree, $i, 1, 0, $townNum - 1);
        if (empty($searchResult)) { // 没有被云覆盖
            $result += $townArr[$i][1];
        } else if ($searchResult[0] == 1) { // 被一朵云覆盖
            $cloudAddNum[$searchResult[1]] += $townArr[$i][1];
            if ($cloudAddNum[$searchResult[1]] > $maxRemoveAddNum) {
                $maxRemoveAddNum = $cloudAddNum[$searchResult[1]];
            }
        }
    }
    
    return $result + $maxRemoveAddNum;
}

function bSearch(&$arr, $value, $total, $isGt)
{
    $low = 0;
    $high = $total - 1;
    
    while ($low <= $high) {
        $middle = intval(($low + $high)/2);
        if ($arr[$middle][0] == $value) {
            return $middle;
        } else if ($arr[$middle][0] < $value) {
            $low = $middle + 1;
        } else {
            $high = $middle - 1;
        }
    }
    
    return $isGt ? $low : $high;
}

// 只有单个查询
function querySegTree(&$segTree, $index, $root, $left, $right)
{
    // 当前已经超了2个了，直接返回
    if (isset($segTree[$root]) && $segTree[$root][0] > 1) {
        return $segTree[$root];
    }
    
    // 到达该节点了
    if ($left == $right) {
        return isset($segTree[$root]) ? $segTree[$root] : null;
    }
    
    // 下推
    pushDown($segTree, $root);
    
    // 继续查询对应的区间
    $mid = intval(($left + $right)/2);
    $leftRoot = $root << 1;
    $rightRoot = $leftRoot | 1;
    
    if ($mid >= $index) { // 在左边
        return querySegTree($segTree, $index, $leftRoot, $left, $mid);
    } else {
        return querySegTree($segTree, $index, $rightRoot, $mid + 1, $right);
    }
}

// 更新
function updateSegTree(&$segTree, $rangeLeft, $rangeRight, $value, $root, $left, $right)
{
    // 整个区间已经被两朵或以上云覆盖了，无需再继续处理
    if (isset($segTree[$root]) && $segTree[$root][0] > 1) {
        return;
    }
    
    // 如果整个包含在区间内， 设置flag， 更新当前值
    if ($left >= $rangeLeft && $right <= $rangeRight) {
        if (empty($segTree[$root])) {
            $segTree[$root] = [1, $value];
        } else {
            $segTree[$root][0]++;
        }
        return;
    }
    
    // 先下推现有的
    pushDown($segTree, $root);
    
    // 继续处理左右子区间
    $mid = intval(($left + $right)/2);
    $leftIndex = $root << 1;
    $rightIndex = $leftIndex | 1;
    // 继续递归处理左右区间
    if ($mid >= $rangeLeft) {
        updateSegTree($segTree, $rangeLeft, $rangeRight, $value, $leftIndex, $left, $mid);
    }
    if ($mid < $rangeRight) {
        updateSegTree($segTree, $rangeLeft, $rangeRight, $value, $rightIndex, $mid + 1, $right);
    }
}

// 下推
function pushDown(&$segTree, $root)
{
    // 什么也不用做
    if (empty($segTree[$root])) {
        return;
    }
    
    $leftIndex = $root << 1;
    $rightIndex = $leftIndex | 1;
    
    if ( ! isset($segTree[$leftIndex])) {
        $segTree[$leftIndex] = $segTree[$root];
    } else {
        $segTree[$leftIndex][0] += $segTree[$root][0];
    }
    
    if ( ! isset($segTree[$rightIndex])) {
        $segTree[$rightIndex] = $segTree[$root];
    } else {
        $segTree[$rightIndex][0] += $segTree[$root][0];
    }
    
    unset($segTree[$root]);
}
