<?php
function getCost($n, $from, $to, $weight) {
    // 连通图
    $connectMap = [];
    $count = count($from);
    for ($i = 0; $i < $count; $i++) {
        $connectMap[$from[$i]][$to[$i]] = $connectMap[$to[$i]][$from[$i]] = $weight[$i];
    }
    
    // 当前计算的每个节点最短路径
    $minDisArr = [];
    $minDisArr[1] = 0;
    
    // 是否已经计算了最短路径
    $flagArr = [];
    
    $queue = new SplPriorityQueue();
    $queue->setExtractFlags(SplPriorityQueue::EXTR_DATA);
    $queue->insert(1, 0);
    
    while ( ! $queue->isEmpty()) {
        $node = $queue->extract();
        
        if (isset($flagArr[$node])) {
            continue;
        }
        
        $flagArr[$node] = true;
        foreach ($connectMap[$node] as $next => $dis) {
            if (isset($flagArr[$next])) {
                continue;
            }
            
            $temp = $minDisArr[$node] > $dis ? $minDisArr[$node] : $dis;
            
            if ( ! isset($minDisArr[$next]) || $minDisArr[$next] > $temp) { // 找到了更小距离，入优先队列
                $minDisArr[$next] = $temp;
                $queue->insert($next, -$temp);
            }
        }
    }
    
    echo (isset($flagArr[$n]) ? $minDisArr[$n] : 'NO PATH EXISTS') . "\n";
}
