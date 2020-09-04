<?php
function treePrunning($k, $weights, $tree) {
    $n = count($weights);
    
    for ($i = $n; $i > 0; $i--) {
        $weights[$i] = $weights[$i - 1];
    }
    $weights[0] = 0;
    
    // 转成connect map
    $connectMap = [];
    foreach ($tree as $item) {
        $connectMap[$item[0]][$item[1]] = $connectMap[$item[1]][$item[0]] = true;
    }

    $postRootQueue = [];
    $subTreeNumArr = [];
    // 构建后根遍历数组
    
    processPostRoot($postRootQueue, $subTreeNumArr, $weights, $connectMap, 1, 0);
    unset($connectMap);
    
    $preItem = [];
    $needRemoveArr = [];
    for ($j = 1; $j < $n; $j++) {
        $needRemoveArr[$j] = $subTreeNumArr[$postRootQueue[$j]][1] < 0;
        $preItem[$j] = $j - $subTreeNumArr[$postRootQueue[$j]][0];
    }
    unset($subTreeNumArr);
    
    // dp[i][j] 意味着遍历到元素i为止，且一共有j次移除操作达到的最大权值和
    $dp = [];
    
    $pre = 0;
    foreach ($postRootQueue as $i => $realIndex) {
        $pre += $weights[$realIndex];
        $dp[$i] = $pre;
    }
    $dp[-1] = 0;
    
    for ($i = 1; $i <= $k; $i++) {
        $newDp = [-1 => 0];
        
        // 第一项
        $newDp[0] = $weights[$postRootQueue[0]] > 0 ? $weights[$postRootQueue[0]] : 0;
        
        for ($j = 1; $j < $n; $j++) {
            // 该项不移除
            $newDp[$j] = $weights[$postRootQueue[$j]] + $newDp[$j - 1];
    
            // 该项移除
            if ($needRemoveArr[$j] && $dp[$preItem[$j]] > $newDp[$j]) {
                $newDp[$j] = $dp[$preItem[$j]];
            }
        }
        
        if ($dp[$n - 1] == $newDp[$n - 1]) {
            break;
        }
        $dp = $newDp;
    }
    
    return $dp[$n - 1];
}

function processPostRoot(&$postRootQueue, &$subTreeNumArr, &$weights, &$connectMap, $nowNode, $parentNode)
{
    // 数量要算上自己
    $num = 1;
    $totalWeight = $weights[$nowNode];
    
    foreach ($connectMap[$nowNode] as $nextNode => $dummy) {
        if ($nextNode == $parentNode) { // 父节点，跳过
            continue;
        }
        
        processPostRoot($postRootQueue, $subTreeNumArr, $weights, $connectMap, $nextNode, $nowNode);
        $num += $subTreeNumArr[$nextNode][0];
        $totalWeight += $subTreeNumArr[$nextNode][1];
    }
    
    $subTreeNumArr[$nowNode] = [$num, $totalWeight];
    $postRootQueue[] = $nowNode;
}
