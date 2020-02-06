<?php
function crabGraphs($n, $t, $graph)
{
    // 边
    $edges = [];
    $nextEdgeIndex = 0;
    
    // 每个点拆成两个， 序号分别是t 和 t + n
    
    // 小于等于n的点表示作为螃蟹脚， 否则作为螃蟹头。源点连螃蟹脚，容量为1，螃蟹头连汇点，容量为t。
    // 如果原始两点之间有一条边，则对应的脚和头相连
    // 例如，原始两点为a和b，则 a与b+n之间有条边， b与a+n之间有条边
    
    // 总点数
    $allCount = 2 * $n + 2;
    
    // 每个节点为出点的第一条边的index, 默认是-1，即没有
    $head = array_fill(0, $allCount, -1);
    
    // 源点到螃蟹脚的边
    for ($i = 1; $i <= $n; $i++) {
        addEdge($edges, $nextEdgeIndex, $head, 0, $i, 1);
        addEdge($edges, $nextEdgeIndex, $head, $i, 0, 0);
    }
    
    // 螃蟹头到汇点的边
    for ($i = 1; $i <= $n; $i++) {
        addEdge($edges, $nextEdgeIndex, $head, $i + $n, $allCount - 1, $t);
        addEdge($edges, $nextEdgeIndex, $head, $allCount - 1, $i + $n, 0);
    }
    
    // 螃蟹脚到螃蟹头的边
    foreach ($graph as $item) {
        addEdge($edges, $nextEdgeIndex, $head, $item[0], $item[1] + $n, 1);
        addEdge($edges, $nextEdgeIndex, $head, $item[1] + $n, $item[0], 0);
        addEdge($edges, $nextEdgeIndex, $head, $item[1], $item[0] + $n, 1);
        addEdge($edges, $nextEdgeIndex, $head, $item[0] + $n, $item[1], 0);
    }
    
    // 用dinic算法求最大流
    $result = 0;
    
    // 用于保存分层信息
    $depthArr = [];
    
    // 用于保存当前遍历到的位置
    $nowLocArr = [];
    
    while (bfs($depthArr, $edges, $head, $allCount)) {
        $nowLocArr = $head;
        while (($newFlow = dfs($depthArr, $edges, $head, $nowLocArr, $allCount - 1, 0, 1)) > 0) { // 不断寻找增广路，流量反正只可能是1
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
