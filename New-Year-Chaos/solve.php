<?php
function beautifulPath($edges, $a, $b) {
    // 连通数组
    $connectMap = [];
    foreach ($edges as $item) {
        $connectMap[$item[0]][] = [$item[1], $item[2]];
        $connectMap[$item[1]][] = [$item[0], $item[2]];
    }
    
    $result = [];
    
    // 使用队列依次处理
    $queue = new SplQueue();
    
    // 一开始，起点到自己的距离为0
    $result[$a][0] = true;
    $queue->enqueue([$a, 0]);
    
    while(!$queue->isEmpty()){
        $queueItem = $queue->dequeue();
        
        $index = $queueItem[0];
        $dis = $queueItem[1];
        
        foreach ($connectMap[$index] as $connectItem) { // 穷举更新距离
            $j = $connectItem[0];
            $tempDis = $connectItem[1];
            
            $newDis = $dis | $tempDis;
            if ( ! isset($result[$j][$newDis])) { // 一个节点出现了新的距离值，则继续遍历
                $result[$j][$newDis] = true;
                $queue->enqueue([$j, $newDis]);
            }
        }
    }

    for ($i = 0; $i < 1024; $i++) { // 终点出现的最小距离值
        if (isset($result[$b][$i])) {
            return $i;
        }
    }
    return -1;
}
