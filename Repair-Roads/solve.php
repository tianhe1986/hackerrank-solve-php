<?php
function repairRoads($n, $roads) {
    $connectMap = []; //记录邻居节点
    foreach ($roads as $road) {
        $connectMap[$road[0]][$road[1]] = $connectMap[$road[1]][$road[0]] = true;
    }

    $removeFlagArr = []; //记录节点是否被移除
    $leafFlagArr = []; // 记录节点是否是叶节点
    $result = 0;
    
    process($connectMap, $removeFlagArr, $leafFlagArr, $result, 0, 0, 0);
    
    return $result;
}

function process(&$connectMap, &$removeFlagArr, &$leafFlagArr, &$result, $index, $parent, $root)
{
    $subCount = 0; // 非叶子节点数量
    $hasSubLeaf = false; //是否有叶子节点
    
    foreach ($connectMap[$index] as $next => $dummy) {
        if ($next == $parent) {
            continue;
        }
        
        // 递归处理子节点
        process($connectMap, $removeFlagArr, $leafFlagArr, $result, $next, $index, $root);
        if (isset($removeFlagArr[$next])) { // 子节点被移除了，什么也不做
            continue;
        }
        
        //判断子节点是否是叶节点
        if (isset($leafFlagArr[$next])) { // 叶节点
            $hasSubLeaf = true;
        } else {
            $subCount++;
        }
    }
    
    $mod = 0;
    if ($subCount > 0) {
        $mod = $subCount % 2;
        $result += ($subCount - $mod) / 2;

        if ($mod == 0) { //自己已经被遍历过了
            $removeFlagArr[$index] = true;
        }
    } else { //如果下面有叶节点，自己不能当作叶节点来看
        if ( ! $hasSubLeaf) {
            $leafFlagArr[$index] = true;
        }
    }
    
    if ($root == $index) { //根节点额外处理
        if ($subCount > 0) {
            if ($mod > 0) {
                $result++;
            }
        } else if ($hasSubLeaf) {
            $result++;
        }
    }
}
