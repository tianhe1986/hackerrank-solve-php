<?php
function evenForest($tNodes, $tEdges, $tFrom, $tTo) {
    $connectMap = [];
    
    //储存连通性
    for ($i = 0; $i < $tEdges; $i++) {
        $connectMap[$tFrom[$i]][$tTo[$i]] = $connectMap[$tTo[$i]][$tFrom[$i]] = true;
    }
    
    //因为根节点会被多算一次，因此从-1开始计数
    $result = -1;
    // 以1号节点作为根进行遍历
    process($connectMap, 1, 0, $result);
    
    return $result;
}

function process(&$connectMap, $nowIndex, $parentIndex, &$result)
{
    $count = 1;
    
    foreach ($connectMap[$nowIndex] as $nextIndex => $dummy) { //依次遍历
        if ($nextIndex != $parentIndex) { // 只处理子节点
            $temp = process($connectMap, $nextIndex, $nowIndex, $result);
            
            //只计算奇偶性即可，不需要具体数量
            $count = ($count + $temp) & 1;
        }
    }
    
    if ( ! $count) { //自己与子节点数量之和为偶数，则移除
        $result++;
    }
    
    return $count;
}
