<?php
function similarPair($n, $k, $edges) {
    // 找root
    $hasParentMap = [];
    
    $connectMap = [];
    
    foreach ($edges as $edge) {
        $connectMap[$edge[0]][$edge[1]] = true;
        $hasParentMap[$edge[1]] = true;
    }
    
    $root = null;
    for ($i = 1; $i <= $n; $i++) {
        if ( ! isset($hasParentMap[$i])) {
            $root = $i;
            break;
        }
    }

    $result = 0;
    
    // 线段树
    $segTree = [];
    // 线段树lazy更新标记
    $lazyFlag = [];
    
    // 创建线段树，一开始都是0，什么也没有
    buildSegTree($segTree, $lazyFlag, 1, 1, $n);
    
    processSimilarPair($connectMap, $segTree, $lazyFlag, $result, $root, $k, $n);
    
    return $result;
}

function processSimilarPair(&$connectMap, &$segTree, &$lazyFlag, &$result, $nowNode, $k, $n)
{
    // 在线段树中查找当前范围数量，累加进结果
    $rangeLeft = $nowNode - $k;
    if ($rangeLeft < 1) {
        $rangeLeft = 1;
    }
    
    $rangeRight = $nowNode + $k;
    if ($rangeRight > $n) {
        $rangeRight = $n;
    }
    
    $result += querySegTree($segTree, $lazyFlag, $rangeLeft, $rangeRight, 1, 1, $n);
    
    // 如果有子节点
    if (isset($connectMap[$nowNode])) {
        // 将此节点更新进入线段树，继续遍历子节点
        updateSegTree($segTree, $lazyFlag, $nowNode, $nowNode, 1, 1, 1, $n);
        
        foreach ($connectMap[$nowNode] as $nextNode => $dummy) {
            processSimilarPair($connectMap, $segTree, $lazyFlag, $result, $nextNode, $k, $n);
        }
        
        // 将此节点从线段树中移除
        updateSegTree($segTree, $lazyFlag, $nowNode, $nowNode, -1, 1, 1, $n);
    }
}

function buildSegTree(&$segTree, &$lazyFlag, $root, $left, $right)
{
    if ($left == $right) { //叶节点
        $segTree[$root] = 0;
        $lazyFlag[$root] = 0;
        return;
    }

    $mid = ($left + $right) >> 1;
    $leftIndex = $root << 1;
    $rightIndex = $leftIndex + 1;
    // 继续递归处理左右区间
    buildSegTree($segTree, $lazyFlag, $leftIndex, $left, $mid);
    buildSegTree($segTree, $lazyFlag, $rightIndex, $mid + 1, $right);

    // 此节点存储的区间元素和为两子节点存储值之和，但这里就是0，就不相加了
    $segTree[$root] = 0;
    $lazyFlag[$root] = 0;
}

function querySegTree(&$segTree, &$lazyFlag, $rangeLeft, $rangeRight, $root, $left, $right)
{
    // 如果整个包含在区间内， 直接返回
    if ($left >= $rangeLeft && $right <= $rangeRight) {
        return $segTree[$root];
    }
    
    // 查询前，先下推现有flag
    pushDown($segTree, $lazyFlag, $root, $left, $right);
    
    $result = 0;
    
    // 继续查询左右子区间
    $mid = ($left + $right) >> 1;
    $leftIndex = $root << 1;
    $rightIndex = $leftIndex + 1;

    if ($mid >= $rangeLeft) { // 与左子节点有交集，继续查询
        $result += querySegTree($segTree, $lazyFlag, $rangeLeft, $rangeRight, $leftIndex, $left, $mid);
    }
    
    if ($mid < $rangeRight) { // 与右子节点有交集，继续查询
        $result += querySegTree($segTree, $lazyFlag, $rangeLeft, $rangeRight, $rightIndex, $mid + 1, $right);
    }
    
    return $result;
}

function updateSegTree(&$segTree, &$lazyFlag, $rangeLeft, $rangeRight, $updateValue, $root, $left, $right)
{
    // 如果整个包含在区间内， 设置flag， 更新当前值
    if ($left >= $rangeLeft && $right <= $rangeRight) {
        $lazyFlag[$root] = empty($lazyFlag[$root]) ? $updateValue : $lazyFlag[$root] + $updateValue;
        $segTree[$root] += $updateValue;
        return;
    }
    
    // 先下推现有flag
    pushDown($segTree, $lazyFlag, $root, $left, $right);
    
    // 继续处理左右子区间
    $mid = ($left + $right) >> 1;
    $leftIndex = $root << 1;
    $rightIndex = $leftIndex + 1;
    // 继续递归处理左右区间
    if ($mid >= $rangeLeft) {
        updateSegTree($segTree, $lazyFlag, $rangeLeft, $rangeRight, $updateValue, $leftIndex, $left, $mid);
    }
    if ($mid < $rangeRight) {
        updateSegTree($segTree, $lazyFlag, $rangeLeft, $rangeRight, $updateValue, $rightIndex, $mid + 1, $right);
    }
    
    // 更新当前区间值
    $segTree[$root] = $segTree[$leftIndex] + $segTree[$rightIndex];
}

function pushDown(&$segTree, &$lazyFlag, $root, $left, $right)
{
    // 没有flag， 不需要下推
    if (empty($lazyFlag[$root])) {
        return;
    }
    
    if ($left == $right) { //已经到了叶节点，没有可下推的了
        return;
    }
    
    $leftIndex = $root << 1;
    $rightIndex = $leftIndex + 1;
    
    // 对于左右节点，更新相应的值，打上懒惰更新标识
    $value = $lazyFlag[$root]; // 区间范围变化值
    
    $segTree[$leftIndex] += $value; // 左子节点区间变化值
    $lazyFlag[$leftIndex] = empty($lazyFlag[$leftIndex]) ? $value : $value + $lazyFlag[$leftIndex]; // 累加到原有更新标识上
    
    $segTree[$rightIndex] += $value; // 右子节点区间变化值
    $lazyFlag[$rightIndex] = empty($lazyFlag[$rightIndex]) ? $value : $value + $lazyFlag[$rightIndex]; // 累加到原有更新标识上
    
    unset($lazyFlag[$root]);
}
