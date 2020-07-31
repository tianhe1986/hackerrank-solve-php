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
    
    // BITree
    $biTree = array_fill(0, $n + 1, 0);
    
    processSimilarPair($connectMap, $biTree, $result, $root, $k, $n);
    
    return $result;
}

function processSimilarPair(&$connectMap, &$biTree, &$result, $nowNode, $k, $n)
{
    // 在BIT中查找当前范围数量，累加进结果
    $rangeLeft = $nowNode - $k;
    if ($rangeLeft < 1) {
        $rangeLeft = 1;
    }
    
    $rangeRight = $nowNode + $k;
    if ($rangeRight > $n) {
        $rangeRight = $n;
    }
    
    $result += (getSum($biTree, $rangeRight) - getSum($biTree, $rangeLeft - 1));
    
    // 如果有子节点
    if (isset($connectMap[$nowNode])) {
        // 将此节点更新进入，继续遍历子节点
        updateBIT($biTree, $nowNode, 1, $n);
        
        foreach ($connectMap[$nowNode] as $nextNode => $dummy) {
            processSimilarPair($connectMap, $biTree, $result, $nextNode, $k, $n);
        }
        
        // 将此节点移除
        updateBIT($biTree, $nowNode, -1, $n);
    }
}

function getSum(&$biTree, $index)
{
    $sum = 0;
    
    while ($index > 0) {
        $sum += $biTree[$index];
        
        $index -= $index & (-$index);
    }
    
    return $sum;
}

function updateBIT(&$biTree, $index, $value, $n)
{
    while ($index <= $n) {
        $biTree[$index] += $value;
        
        $index += $index & (-$index);
    }
}
