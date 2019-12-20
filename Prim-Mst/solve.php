<?php
function prims($n, $edges, $start) {
    // 边转成连通图
    $connectMap = [];
    foreach ($edges as $edge) {
        $connectMap[$edge[0]][$edge[1]] = $connectMap[$edge[1]][$edge[0]] = $edge[2];
    }
    
    // 存储最后结果
    $result = 0;
    
    // 是否已经求出了最小生成树
    $inMstArr = [];
    
    // 当前求出的到最小生成树任一节点的最小距离
    $minDisArr = [];
    
    // 优先级队列，按到最小生成树的最小距离排序
    $priorityQueue = new SplPriorityQueue();
    $priorityQueue->setExtractFlags(SplPriorityQueue::EXTR_DATA);
    
    // 初始化，起点到自己距离为0，入优先队列
    $minDisArr[$start] = 0;
    $priorityQueue->insert($start, 0);
    while ( ! $priorityQueue->isEmpty()) {
        $node = $priorityQueue->extract();
        
        if (isset($inMstArr[$node])) { // 之前已经遍历过，跳过
            continue;
        }
        
        // 设置为已处理，将距离加到最后结果中
        $inMstArr[$node] = true;
        $result += $minDisArr[$node];
        
        // 更新与其相连的节点的最小距离，有更新则加入队列
        foreach ($connectMap[$node] as $nextNode => $nextDis) {
            if (isset($inMstArr[$nextNode])) { // 已处理过的节点，忽略
                continue;
            }
            
            // 距离变短了，加入优先队列
            if ( ! isset($minDisArr[$nextNode]) || $nextDis < $minDisArr[$nextNode]) {
                $minDisArr[$nextNode] = $nextDis;
                $priorityQueue->insert($nextNode, -$nextDis);
            }
        }
    }
    
    return $result;
}
