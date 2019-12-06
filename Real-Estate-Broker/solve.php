<?php
function realEstateBroker($clients, $houses) {
    // 客户数, 客户点编号从1到m
    $m = count($clients);
    
    // 房子总数， 房子点编号从 m+1到m+n
    $n = count($houses);
    
    // 边
    $edges = [];
    $nextEdgeIndex = 0;
    
    // 建立有向图，源点为0，汇点为 m+n+1
    
    // 总点数
    $allCount = $m + $n + 2;
    $tNodeIndex = $allCount - 1;

    // 每个节点为出点的第一条边的index, 默认是-1，即没有
    $head = array_fill(0, $m + $n + 2, -1);

    // 源点->客户的边
    for ($i = 1; $i <= $m; $i++) {
        addEdge($edges, $nextEdgeIndex, $head, 0, $i, 1);
        addEdge($edges, $nextEdgeIndex, $head, $i, 0, 0);
    }
    
    // 满足购买需求的客户->房子的边
    for ($i = 1; $i <= $m; $i++) {
        for ($j = 1; $j <= $n; $j++) {
            if ($houses[$j - 1][0] > $clients[$i - 1][0] && $houses[$j - 1][1] <= $clients[$i - 1][1]) { // 比需求面积大，且小于等于心理价格，则配对成功
                addEdge($edges, $nextEdgeIndex, $head, $i, $m + $j, 1);
                addEdge($edges, $nextEdgeIndex, $head, $m + $j, $i, 0);
            }
        }
    }
    
    // 房子->汇点的边
    for ($j = 1; $j <= $n; $j++) {
        addEdge($edges, $nextEdgeIndex, $head, $m + $j, $tNodeIndex, 1);
        addEdge($edges, $nextEdgeIndex, $head, $tNodeIndex, $m + $j, 0);
    }
    
    // 用dinic算法求最大流
    $result = 0;
    
    // 用于保存分层信息
    $depthArr = [];
    
    // 用于保存当前遍历到的位置
    $nowLocArr = [];
    
    while (bfs($depthArr, $edges, $head, $allCount)) {
        $nowLocArr = $head;
        while (($newFlow = dfs($depthArr, $edges, $head, $nowLocArr, $tNodeIndex, 0, 1)) > 0) { // 不断寻找增广路，流量反正只可能是1
            $result += $newFlow;
        }
    }
    
    return $result;
}

// 求每个节点的层次
function bfs(&$depthArr, &$edges, &$head, $allCount)
{
    // 全部清空
    $depthArr = [];
    
    $queue = new SplQueue();
    // 源点层次设为1
    $depthArr[0] = 1;
    $queue->enqueue(0);
    
    while ( ! $queue->isEmpty()) {
        $node = $queue->dequeue();
        for ($edgeIndex = $head[$node]; $edgeIndex != -1;) { // 广度优先，将还没有层次的点加入层次
            $edge = $edges[$edgeIndex];
            
            if ($edge[1] > 0 && ! isset($depthArr[$edge[0]])) { // 有容量且还未遍历，则加入队列
                $depthArr[$edge[0]] = $depthArr[$node] + 1;
                
                $queue->enqueue($edge[0]);
            }
            
            $edgeIndex = $edge[2];
        }
    }
    
    // 检查汇点是否可达
    return isset($depthArr[$allCount - 1]);
}

// 寻找增广路
function dfs(&$depthArr, &$edges, &$head, &$nowLocArr, $tNodeIndex, $node, $flow)
{
    if ($node == $tNodeIndex) { // 到达汇点了
        return $flow;
    }
    
    for ($edgeIndex = $nowLocArr[$node]; $edgeIndex != -1;) { // 遍历能走的每条路， 有流量则直接返回
        $edge = $edges[$edgeIndex];
        
        // 有容量，且满足层级要求
        if ($edge[1] > 0 && $depthArr[$edge[0]] == $depthArr[$node] + 1) { // 有容量且满足层级要求，继续遍历
            $newFlow = dfs($depthArr, $edges, $head, $nowLocArr, $tNodeIndex, $edge[0], min($edge[1], $flow));
            
            if ($newFlow > 0) { // 走通了，减少对应边的容量，增加反向边的容量
                $edges[$edgeIndex][1] -= $newFlow;
                $edges[$edgeIndex^1][1] += $newFlow;
                return $newFlow;
            }
        }

        $nowLocArr[$node] = $edgeIndex = $edge[2];
    }
    
    // 所有路都走不通
    return 0;
}

function addEdge(&$edges, &$nextEdgeIndex, &$head, $fromNode, $toNode, $capacity)
{
    $edges[$nextEdgeIndex] = [$toNode, $capacity, $head[$fromNode]];  // 三元组， 指向点， 容量， 下一条以from为出点的条
    $head[$fromNode] = $nextEdgeIndex++;
}
