<?php
function dijkstraDis(&$edges, $index, $n)
{
    $result = [];
    
    //是否已经处理
    $flagArr = [];
    
    $result[$index] = 0;
    
    $queue = new SplPriorityQueue();
    
    $queue->insert($index, 0);
    
    // 用堆处理当前最短长度
    while(!$queue->isEmpty()){
        $minDisIndex = $queue->top();
        $queue->extract();
        if (isset($flagArr[$minDisIndex])) {
            continue;
        }
        $minDis = $result[$minDisIndex];
        
        $flagArr[$minDisIndex] = true;
        foreach ($edges[$minDisIndex] as $j => $tempDis) { // 更新最短长度
            if (isset($flagArr[$j])) {
                continue;
            }

            if ( ! isset($result[$j]) || $result[$j] > ($minDis + $tempDis)) {
                $result[$j] = $minDis + $tempDis;
                $queue->insert($j, -$result[$j]);
            }
        }
    }

    return $result;
}

function solve($n, $k, $roads) {
    //边
    $edges = [];
    //最短路径数组
    
    $a = 0; // a节点
    $b = 0; // b节点
    $disatob = 0; // a和b之间的直连路径的长度
    $count = count($roads);
    for ($i = 0; $i < $count; $i++) {
        $item = $roads[$i];
        $edges[$item[0]][$item[1]] = $edges[$item[1]][$item[0]] = $item[2];
        if ($i == $k - 1) {
            $a = $item[0];
            $b = $item[1];
            $disatob = $item[2];
        }
    }
    
    // dijkstra算法求最短距离
    $disaArr = dijkstraDis($edges, $a, $n);
    $disbArr = dijkstraDis($edges, $b, $n);
    
    // 假设k连续的两个节点是a和b，按与a的最短路径排序
    $processArr = [];
    for ($i = 1; $i <= $n; $i++) {
        $processArr[] = [$disaArr[$i], $disbArr[$i]];
    }
    
    usort($processArr, function($t1, $t2) {
        return $t1[0] - $t2[0];
    });
    
    // 初始化距离最大值为a到所有节点的最短路径中的最大值，待比较组为该组
    $maxDis = $processArr[$n - 1][0];
    $disToa = 0;
    
    $compareIndex = $n - 1;
    // 按与a的最短路径从大到小遍历，如果与待比较组有交点(即到b的最短路径更大)，计算，若产生更小的最大值，则替换，有交点的话，将此组设为待比较组。
    for ($i = $n - 2; $i >= 0; $i--) {
        if ($processArr[$i][1] >= $processArr[$compareIndex][1]) { // 与当前终点最大项有交点
            $tempMaxDis = ($processArr[$compareIndex][1] + $disatob + $processArr[$i][0])/2;
            $tempDisToa = $tempMaxDis - $processArr[$i][0];
            if ($tempMaxDis < $maxDis || ($tempMaxDis == $maxDis && $tempDisToa < $disToa)) { //更短，则替换
                $maxDis = $tempMaxDis;
                $disToa = $tempDisToa;
            }
            $compareIndex = $i;
        }
    }
    
    // 最后,再处理b到所有节点的最短路径中的最大值，即待比较组与b的最短路径
    $tempMaxDis = $processArr[$compareIndex][1];
    if ($tempMaxDis < $maxDis) {
        $maxDis = $tempMaxDis;
        $disToa = $disatob;
    }
    
    //返回结果
    return [sprintf("%.5f", round($disToa, 5)), sprintf("%.5f", round($maxDis, 5))];
}
