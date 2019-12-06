<?php
function shop($n, $k, $centers, $roads) {
    // 所有的鱼bitmask
    $allFishMask = (1 << $k) - 1;
    
    // 存储每个center贩卖的鱼，用bitmask表示
    $centerFinishMap = [];
    for ($i = 1; $i <= $n; $i++) {
        $centerStr = $centers[$i - 1];
        $tempArr = explode(" ", $centerStr);
        $count = count($tempArr);
        $centerFinishMap[$i] = 0;
        for ($j = 1; $j < $count; $j++) {
            $centerFinishMap[$i] = ($centerFinishMap[$i] | (1 << ($tempArr[$j] - 1)));
        }
    }
    
    // connectMap，也没啥好说的
    $connectMap = [];
    foreach ($roads as $road) {
        $connectMap[$road[0]][$road[1]] = $connectMap[$road[1]][$road[0]] = $road[2];
    }
    
    // 用二维数组表示一个节点，即现在有 n * (2^k) 个节点，需要依次计算
    // 节点是否已经求出最小值
    $hasMinFlag = [];
    // 节点当前已经求出的最短距离
    $nowMinDis = [];
    
    // 迪杰斯特拉， 求最短路径
    dijkstra($nowMinDis, $hasMinFlag, $connectMap, $centerFinishMap);
    
    // 对于序号为n的中心， 对其节点两两遍历，如果满足两个节点覆盖全部的鱼，则最小值可能是两个节点中的最大值
    $result = 1000000000;
    $maskArr = array_keys($nowMinDis[$n]);
    $maskLen = count($maskArr);
    
    for ($i = 0; $i < $maskLen; $i++) {
        for ($j = $i; $j < $maskLen; $j++) {
            if (($maskArr[$i] | $maskArr[$j]) ^ $allFishMask) { // 没有覆盖全部的鱼种类
                continue;
            }
            
            $time = max($nowMinDis[$n][$maskArr[$i]], $nowMinDis[$n][$maskArr[$j]]);
            if ($result > $time) {
                $result = $time;
            }
        }
    }
    
    return $result;
}

function dijkstra(&$nowMinDis, &$hasMinFlag, &$connectMap, &$centerFinishMap)
{
    // 使用优先队列取当前已经计算出的节点中距离最小者
    $heap = new SplPriorityQueue();
    $heap->setExtractFlags(SplPriorityQueue::EXTR_DATA);
    
    // 初始化
    $nowMinDis[1][$centerFinishMap[1]] = 0;
    $heap->insert([1, $centerFinishMap[1]], 0);

    // 不断遍历，求最小距离
    while ( ! $heap->isEmpty()) {
        $item = $heap->extract();
        if (isset($hasMinFlag[$item[0]][$item[1]])) {
            continue;
        }
        
        $hasMinFlag[$item[0]][$item[1]] = true;
        updateMinDis($nowMinDis, $hasMinFlag, $connectMap, $centerFinishMap, $item[0], $item[1], $heap);
    }
}

// 更新最短距离
function updateMinDis(&$nowMinDis, &$hasMinFlag, &$connectMap, &$centerFinishMap, $nowNode, $nowMask, SplPriorityQueue $heap)
{
    $nowDis = $nowMinDis[$nowNode][$nowMask];
    
    foreach ($connectMap[$nowNode] as $nextNode => $nextDis) {
        // 连接的节点
        $nextMask = ($nowMask | $centerFinishMap[$nextNode]);
        if (isset($hasMinFlag[$nextNode][$nextMask])) {
            continue;
        }

        // 如果能够缩短距离，则更新
        $tempDis = $nowDis + $nextDis;
        if ( ! isset($nowMinDis[$nextNode][$nextMask]) || $nowMinDis[$nextNode][$nextMask] > $tempDis) {
            $nowMinDis[$nextNode][$nextMask] = $tempDis;
            $heap->insert([$nextNode, $nextMask], -$tempDis);
        }
    }
}
