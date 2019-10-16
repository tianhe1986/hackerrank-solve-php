<?php
function quickestWayUp($ladders, $snakes) {
    $resultArr = [];
    
    // 真正到达的节点
    $gotoMap = [];
    foreach ($ladders as $item) {
        $gotoMap[$item[0]] = $item[1];
    }
    
    foreach ($snakes as $item) {
        $gotoMap[$item[0]] = $item[1];
    }
    
    //从起点开始，广度优先
    $queue = new SplQueue();
    $queue->push(1);
    $resultArr[1] = 0;
    
    while ( ! $queue->isEmpty()) {
        $nowIndex = $queue->shift();

        //往后6个方格，都是一步走到
        for ($i = 1; $i <= 6 ; $i++) {
            
            $next = $nowIndex + $i;

            if (isset($gotoMap[$next])) { // 真正到达的节点
                $next = $gotoMap[$next];
            }
            
            if (isset($resultArr[$next])) { //之前已经遍历过
                continue;
            }
            
            
            $resultArr[$next] = $resultArr[$nowIndex] + 1; //步数+1， 继续遍历
            if ($next == 100) { // 已经到终点了，直接返回，不需要再遍历
                return $resultArr[$next];
            }
            $queue->push($next);
        }
    }
    
    // 到不了终点
    return -1;
}
