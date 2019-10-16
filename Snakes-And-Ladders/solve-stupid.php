<?php
function quickestWayUp($ladders, $snakes) {
    $resultArr = [];
    $ladderMap = [];
    $snakeMap = [];
    
    $antiMap = [];
    
    foreach ($ladders as $item) {
        $ladderMap[$item[0]] = $item[1];
        $antiMap[$item[1]] = $item[0];
    }
    
    foreach ($snakes as $item) {
        $snakeMap[$item[0]] = $item[1];
        $antiMap[$item[1]] = $item[0];
    }
    
    //如果有连续6个都是snake，且不存在梯子能够越过此区间，则不可能到达终点
    $nowCount = 0;
    for ($i = 1; $i <= 100; $i++) {
        if ( ! isset($snakeMap[$i])) {
            if ($nowCount >= 6) { // 连续6个及以上蛇
                $canThrough = false;
                foreach ($ladderMap as $from => $to) { //检查是否有梯子能通过此区间
                    if ($from < $i && $to >= $i) {
                        $canThrough = true;
                        break;
                    }
                }
                if ( ! $canThrough) {
                    return -1;
                }
            }
            $nowCount = 0;
        } else {
            $nowCount++;
        }
    }
    
    // 先假设没有snake，找最短路
    $resultArr[100] = 0;
    for ($i = 99; $i >= 94; $i--) { //最终都是1步
        $resultArr[$i] = 1;
    }
    
    for ($i = 93; $i >= 1; $i--) {
        if (isset($ladderMap[$i])) {
            $resultArr[$i] = $resultArr[$ladderMap[$i]];
        } else {
            $min = $resultArr[$i + 1];
            for ($j = 2; $j <= 6; $j++) {
                if ($resultArr[$i + $j] < $min) {
                    $min = $resultArr[$i + $j];
                }
            }
            $resultArr[$i] = $min + 1;
        }
    }
    
    // 用snake，刷新最短路
    foreach ($snakeMap as $from => $to) {
        $resultArr[$from] = $resultArr[$to];
        
        $queue = new SplQueue();
        $queue->push($from);
        while ( ! $queue->isEmpty()) {
            $nowIndex = $queue->pop();
            // 影响之前的6个
            for ($i = 1; $i <= 6; $i++) {
                $nextIndex = $nowIndex - $i;
                if ($nextIndex <= 0) {
                    break;
                }
                
                if (isset($ladderMap[$nextIndex]) || isset($snakeMap[$nextIndex])) {
                    continue;
                }
                
                if ($resultArr[$nextIndex] > $resultArr[$nowIndex] + 1) { //有更短路径了，继续迭代处理
                    $resultArr[$nextIndex] = $resultArr[$nowIndex] + 1;
                    $queue->push($nextIndex);
                    
                    if (isset($antiMap[$nextIndex])) {
                        $resultArr[$antiMap[$nextIndex]] = $resultArr[$nextIndex];
                        $queue->push($antiMap[$nextIndex]);
                    }
                }
            }
        }
    }
    
    return $resultArr[1];
}
