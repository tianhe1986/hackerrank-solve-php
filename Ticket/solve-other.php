<?php
function ticket($desList, $customerList, $m)
{
    $disCount = 0.2;
    // 转化成id map
    $desStrToIdMap = [];
    $priceMap = [];
    
    $k = count($desList);
    for ($i = 0; $i < $k; $i++) {
        $desStrToIdMap[$desList[$i][0]] = $i;
        $priceMap[$i] = intval($desList[$i][1]);
    }
    
    // 目的地队列表
    $customerIdList = [];
    
    $normalPrice = 0;
    $n = count($customerList);
    for ($i = 0; $i < $n; $i++) {
        $destId = $desStrToIdMap[$customerList[$i]];
        $customerIdList[] = $destId;
        $normalPrice += $priceMap[$destId];
    }
    
    $start = 0;
    $end = 2 * $n + 1;
    
    // 边的结构为 [容量，花费]
    $edges = [];
    
    
    for ($i = 0; $i < $n; $i++) {
        // 建立起点到每个左侧点的边，容量为1，花费为0
        addEdge($edges, $start, $i + 1, 1, 0);
        // 建立每个右侧点到终点的边，容量为1，花费为0
        addEdge($edges, $n + $i + 1, $end, 1, 0);
    }
    
    // 建立左侧点到右侧点的边，规则是若右侧点在左侧点之后，则建立
    for ($i = 0; $i < $n; $i++) {
        for ($j = $i + 1; $j < $n; $j++) {
            addEdge($edges, $i + 1, $n + $j + 1, 1, 
                    $customerIdList[$i] == $customerIdList[$j] ? -$priceMap[$customerIdList[$i]] : 0);
        }
    }
    
    // spfa算法找源点到终点的最小花费（最短）路径
    
    // 真正对应的前后点
    $realPreMap = [];
    $realAfterMap = [];
    
    $discountPrice = 0;
    $t1 = microtime(true);
    for ($i = 1; $i < $n; $i++) {
        $preMap = [];
        $disMap = [];
        $t1 = microtime(true);
        spfa($edges, $disMap, $preMap, $start, $end);
        //echo (microtime(true) - $t1)."\n";

        // 找不到新的路径了（理论上不太可能）
        if ($disMap[$end] == 500003) {
            break;
        }
        
        // 路径只会增加花费，且当前占用路径数已经在范围内，则停止
        if ($disMap[$end] >= 0 && $i > $n - $m) {
            break;
        }
        
        $discountPrice += $disMap[$end];
        
        for ($j = $end; $j != $start; $j = $preMap[$j]) {
            // 处理前后关系
            if ( $j > $n && $j != $end) {
                $realPreMap[$j - $n] = $preMap[$j];
                $realAfterMap[$preMap[$j]] = $j - $n;
            }
            
            // 处理流量占用
            // 每条边的流量只可能为1或0
            // 此边流量变0，反向边流量变为1
            $edges[$preMap[$j]][$j][0] = 0;
            $edges[$j][$preMap[$j]][0] = 1;
        }
    }
    
    //echo (microtime(true) - $t1)."\n";
    //exit;

    
    // 遍历每个点，如果某个点的左侧点没有再连下一个，则说明是队尾，依次前推放入一个队中
    $queueMap = [];
    $index = 1;

    for ($i = 1; $i <= $n; $i++) {
        if (isset($realAfterMap[$i])) {
            continue;
        }
        
        // 从队尾开始
        $j = $i;
        $queueMap[$j] = $index;
        
        // 所有排在更前面的人，都是同一个队伍
        while (isset($realPreMap[$j])) {
            $j = $realPreMap[$j];
            $queueMap[$j] = $index;
        }
        
        $index++;
    } 

    $result = [];
    
    $result[] = $normalPrice + $disCount * $discountPrice;
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

// spfa算法求最小花费增广路
function spfa(&$edges, &$disMap, &$preMap, $startIndex, $end)
{
    $queue = [];
    
    $inQueueMap = [];
    
    $disMap[$startIndex] = 0;
    $nowIndex = 0;
    $count = 1;
    $queue[] = $startIndex;

    while ($nowIndex < $count) {
        $now = $queue[$nowIndex++];
        unset($inQueueMap[$now]);
        
        if ($now == $end) {
            continue;
        }
        
        foreach ($edges[$now] as $to => $item) {
            if ( ! $item[0]) {
                continue;
            }
            
            $tempDis = $disMap[$now] + $item[1];
            if ( ! isset($disMap[$to]) || $disMap[$to] > $tempDis) { // 有容量而且花费更小，则更新
                $disMap[$to] = $tempDis;
                $preMap[$to] = $now;
                
                if ( ! isset($inQueueMap[$to])) {
                    $queue[] = $to;
                    $count++;
                    $inQueueMap[$to] = true;
                }
            }
        }
    }
}
