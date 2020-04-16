<?php
function ticket($desList, $customerList, $m)
{
    // 无穷大
    $inf = 50000003;
    // 折扣
    $disCount = 0.8;
    // 转化成id map
    $desStrToIdMap = [];
    $priceMap = [];
    
    $k = count($desList);
    for ($i = 0; $i < $k; $i++) {
        $desStrToIdMap[$desList[$i][0]] = $i;
        $priceMap[$i] = intval($desList[$i][1]) * 10;
    }
    
    // 目的地队列表
    $customerIdList = [];
    
    $n = count($customerList);
    for ($i = 0; $i < $n; $i++) {
        $destId = $desStrToIdMap[$customerList[$i]];
        $customerIdList[] = $destId;
    }
    
    $start = 0;
    $secondStart = 2 * $n + 1;
    $end = 2 * $n + 2;
    
    // 边的结构为 [容量，花费]
    $edges = [];
    
    // 建立真·源点到第二源点的边
    addEdge($edges, $start, $secondStart, $m, 0);
    
    for ($i = 0; $i < $n; $i++) {
        // 建立第二起点到每个左侧点的边，容量为1，花费为左侧点的花费
        addEdge($edges, $secondStart, $i + 1, 1, $priceMap[$customerIdList[$i]]);
        // 建立每个右侧点到终点的边，容量为1，花费为0
        addEdge($edges, $n + $i + 1, $end, 1, 0);
        // 建立左侧点到右侧点的边，容量为1，花费为-inf，这样能保证每个点都会被经过
        addEdge($edges, $i + 1, $n + $i + 1, 1, -$inf);
    }
    
    // 建立右侧点到左侧点的边，规则是若右侧点在左侧点之前，则建立
    for ($i = 0; $i < $n; $i++) {
        for ($j = $i + 1; $j < $n; $j++) {
            addEdge($edges, $n + $i + 1, $j + 1, 1, 
                    $customerIdList[$i] == $customerIdList[$j] ? $priceMap[$customerIdList[$j]] * $disCount : $priceMap[$customerIdList[$j]]);
        }
    }
    
    // spfa算法找源点到终点的最小花费（最短）路径
    
    // 真正对应的前后点
    $realPreMap = [];
    $realAfterMap = [];
    
    for ($i = 1; $i <= $m; $i++) {
        $preMap = [];
        $disMap = [];
        spfa($edges, $disMap, $preMap, $start, $end);
        
        // 找不到新的路径了
        if ( ! isset($preMap[$end]) ) {
            break;
        }
        
        // 路径只会增加花费，说明所有的节点都已经属于某个队列了
        if ($disMap[$end] >= 0) {
            break;
        }

        for ($j = $end; $j != $start; $j = $preMap[$j]) {
            // 处理前后关系
            if ( $j <= $n) { // 建立关系
                if ($preMap[$j] != $secondStart) {
                    $realPreMap[$j] = $preMap[$j] - $n;
                    $realAfterMap[$preMap[$j] - $n] = $j;
                }
            } else if ($j <= 2 * $n) { // 解除之前的关系
                if (isset($realPreMap[$preMap[$j]]) && $realPreMap[$preMap[$j]] == $j - $n) {
                    unset($realPreMap[$preMap[$j]]);
                }
                if (isset($realAfterMap[$j - $n]) && $realAfterMap[$j - $n] == $preMap[$j]) {
                    unset($realAfterMap[$j - $n]);
                }
            }
            
            // 处理流量占用
            $edges[$preMap[$j]][$j][0] -= 1;
            $edges[$j][$preMap[$j]][0] += 1;
        }
    }
    
    // 遍历每个点，如果某个点的左侧点没有再连下一个，则说明是队尾，依次前推放入一个队中
    $queueMap = [];
    $index = 1;

    $totalPrice = 0;
    for ($i = 1; $i <= $n; $i++) {
        if (isset($realAfterMap[$i])) {
            continue;
        }
        
        $j = $i;
        $queueMap[$j] = $index;
        
        while (isset($realPreMap[$j])) {
            $totalPrice += ($customerIdList[$j - 1] == $customerIdList[$realPreMap[$j] - 1] ? $priceMap[$customerIdList[$j - 1]] * $disCount : $priceMap[$customerIdList[$j - 1]]);
            $j = $realPreMap[$j];
            $queueMap[$j] = $index;
        }
        
        $totalPrice += $priceMap[$customerIdList[$j - 1]];
        
        $index++;
    } 

    $result = [];
    
    $result[] = $totalPrice / 10;
    for ($i = 1; $i <= $n; $i++) {
        $result[] = $queueMap[$i];
    }
    
    return $result;
}

function addEdge(&$edges, $from, $to, $flow, $cost)
{
    // 正向边
    $edges[$from][$to] = [$flow, $cost];
    
    // 反向边
    $edges[$to][$from] = [0, -$cost];
}

function spfa(&$edges, &$disMap, &$preMap, $startIndex, $end)
{
    $queue = new SplQueue();
    
    $inQueueMap = [];
    
    $disMap[$startIndex] = 0;
    
    $queue->enqueue($startIndex);
    $inQueueMap[$startIndex] = true;

    while ( ! $queue->isEmpty()) {
        $now = $queue->dequeue();
        
        if ($now == $end) {
            continue;
        }
        
        unset($inQueueMap[$now]);
        
        foreach ($edges[$now] as $to => $item) {
            if ( ! $item[0]) {
                continue;
            }
            
            $tempDis = $disMap[$now] + $item[1];
            if ( ! isset($disMap[$to]) || $disMap[$to] > $tempDis) {
                $disMap[$to] = $tempDis;
                $preMap[$to] = $now;
                
                
                if ( ! isset($inQueueMap[$to])) {
                    $queue->enqueue($to);
                    $inQueueMap[$to] = true;
                }
            }
        }
    }
}
