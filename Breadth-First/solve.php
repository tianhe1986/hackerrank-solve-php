<?php
function bfs($n, $m, $edges, $s) {
    // 距离数组，默认为-1
    $disMap = array_fill(0, $n + 1, -1);
    
    // 将边储存为连通图
    $connectMap = [];
    
    foreach ($edges as $edge) {
        $connectMap[$edge[0]][$edge[1]] = $connectMap[$edge[1]][$edge[0]] = true;
    }
    
    // 广度优先遍历，使用队列
    $queue = new SplQueue();
    
    // 起点距离设置为0，将起点入队列
    $disMap[$s] = 0;
    $queue->enqueue($s);
    
    while ( ! $queue->isEmpty()) {
        $node = $queue->dequeue();
        
        if ( ! isset($connectMap[$node])) { // 以防万一起点没有跟任何点相连
            continue;
        }
        
        foreach ($connectMap[$node] as $next => $dummy) {
            if ($disMap[$next] == -1) { // 未遍历过的，设置最短距离，插入队列，继续遍历
                $disMap[$next] = $disMap[$node] + 6;
                $queue->enqueue($next);
            }
        }
    }
    
    // 去除起点以及未用到的序号0
    unset($disMap[0], $disMap[$s]);
    return $disMap;
}
