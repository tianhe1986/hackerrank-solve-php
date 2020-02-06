<?php
function crabGraphs($n, $t, $graph)
{
    // graph转连通图
    $connectMap = [];
    foreach ($graph as $item) {
        $connectMap[$item[0]][$item[1]] = $connectMap[$item[1]][$item[0]] = true;
    }
    
    // 以当前节点作为头部的节点数，如果没有，则为0， 同时可用于判断节点是否已经被处理过
    $subNumArr = [];
    
    // 是否是叶节点
    $leafFlagArr = [];
    
    // 预处理，找出所有叶节点和不需要处理的节点
    for ($i = 1; $i <= $n; $i++) {
        if ( ! isset($connectMap[$i])) { // 孤点，完全不需要处理
            $subNumArr[$i] = 0;
            continue;
        }
        
        if (1 == count($connectMap[$i])) { // 只连接了一个节点，则是叶节点
            $leafFlagArr[$i] = 0;
        }
    }
    
    $result = 0;
    
    // 当前是否正在遍历，因为可能有环
    $inProcessArr = [];
    
    // 记录父节点，用于调整子节点数量
    $parentArr = [];
    
    for ($i = 1; $i <= $n; $i++) {
        if (isset($subNumArr[$i])) {
            continue;
        }
        
        processCrab($connectMap, $subNumArr, $leafFlagArr, $result, $i, $inProcessArr, $parentArr, $t);
    }
    
    return $result;
}

// 螃蟹呀螃蟹
function processCrab(&$connectMap, &$subNumArr, &$leafFlagArr, &$result, $nowNode, &$inProcessArr, &$parentArr, $t)
{
    $inProcessArr[$nowNode] = true;
    
    // 还有余量的子节点
    $notFullSon = null;
    
    // 还未属于任何一个crab的子节点数量
    $noCrabCount = 0;
    
    // 还未属于任何一个crab的非叶子节点
    $notCrabNonLeafArr = [];
    
    foreach ($connectMap[$nowNode] as $nextNode => $dummy) {
        if (isset($subNumArr[$nextNode]) || isset($inProcessArr[$nextNode])) { // 已处理过或正在遍历中
            continue;
        }
        
        if (isset($leafFlagArr[$nextNode])) { // 是叶节点，自然还未属于任何一个crab
            $subNumArr[$nextNode] = 0;
            $noCrabCount++;
            continue;
        }
        
        processCrab($connectMap, $subNumArr, $leafFlagArr, $result, $nextNode, $inProcessArr, $parentArr, $t);
        
        // 如果已经有了crab，判断是否有余量
        if (isset($subNumArr[$nextNode])) {
            if ($subNumArr[$nextNode] < $t) {
                $notFullSon = $nextNode;
            }
        } else { // 加入到非叶子节点数组，之后再统一处理
            $notCrabNonLeafArr[] = $nextNode;
            $noCrabCount++;
            continue;
        }
    }
    
    // 如果所有子节点都已经有crab了，尝试将自己作为一个有余量的子节点的feet
    if (0 == $noCrabCount) {
        if (null !== $notFullSon) {
            $subNumArr[$notFullSon]++;
            if (isset($parentArr[$notFullSon])) { // 如果原本是feet，则恢复对应parent余量，清除其parent
                $subNumArr[$parentArr[$notFullSon]]--;
                unset($parentArr[$notFullSon]);
            }
            
            $subNumArr[$nowNode] = 0;
            $parentArr[$nowNode] = $notFullSon;
            $result++;
        }
    } else {  // 否则，自己作为head，构建crab
        $endIndex = count($notCrabNonLeafArr) - 1;
        if ($noCrabCount > $t) {
            $endIndex = $t - ($noCrabCount - count($notCrabNonLeafArr)) - 1;
            $noCrabCount = $t;
        }
        
        for ($i = 0; $i <= $endIndex; $i++) {
            $subNumArr[$notCrabNonLeafArr[$i]] = 0;
            $parentArr[$notCrabNonLeafArr[$i]] = $nowNode;
        }
        
        $subNumArr[$nowNode] = $noCrabCount;
        $result += ($noCrabCount + 1);
    }
    
    unset($inProcessArr[$nowNode]);
}

