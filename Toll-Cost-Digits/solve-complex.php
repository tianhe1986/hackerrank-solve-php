<?php
function tollCostDigits($n, &$roadFrom, &$roadTo, &$roadWeight)
{
    //连通图
    $connectMap = [];
    $count = count($roadFrom);
    
    //用于加速计算的缓存数组
    $addCache = [];
    $reCache = [];
    for ($i = 0; $i <= 9; $i++) {
        for ($j = 0; $j <= 9; $j++) {
            //for ($k = 0; $k <= 9; $k++) {
                $addCache[$i][$j] = ($i + $j) % 10;
            //}
        }
        $reCache[$i] = (10 - $i) % 10;
    }
    
    for ($i = 0; $i < $count; $i++) {
        $weight = $roadWeight[$i] % 10;
        $rweight = $reCache[$weight];
        $connectMap[$roadFrom[$i]][$roadTo[$i]][$weight] = true;
        $connectMap[$roadTo[$i]][$roadFrom[$i]][$rweight] = true;
    }
    
    $resultArr = [];
    for ($i = 0; $i <= 9; $i++) {
        $resultArr[$i] = 0;
    }
    
    $inGroupFlag = [];
    for ($i = 1; $i <= $n; $i++) {
        if ( ! isset($inGroupFlag[$i])) {
            calcuConnectDigits($resultArr, $inGroupFlag, $connectMap, $i, $addCache, $reCache);
        }
    }
    
    for ($i = 0; $i <= 9; $i++) {
        echo $resultArr[$i]."\n";
    }
}

function processDisAndCircle(&$disDigitsMap, &$groupMap, &$connectMap, &$nowQueueMap, $nowNode, $nowDis, &$firstNode, &$addCache, &$reCache)
{
    if ( ! isset($groupMap[$nowNode])) {
        $groupMap[$nowNode] = true;
    }
    if ( ! isset($connectMap[$nowNode])) {
        return;
    }
    
    foreach ($connectMap[$nowNode] as $nextNode => $nodeDisMap) { // 对于之后的每个节点
        foreach ($nodeDisMap as $nextDis => $dummy) {
            $tempDis = $addCache[$nowDis][$nextDis];
            if ($nextNode == $firstNode) { // 如果是首节点，成环，记录
                $disDigitsMap[$nextNode][$tempDis] = true;
            } else {
                if (isset($nowQueueMap[$nextNode])) { // 如果中间有环，将环的尾数设置到起点尾数中
                    $disDigitsMap[$firstNode][$addCache[$nowQueueMap[$nextNode]][$reCache[$tempDis]]] = true;
                    continue;
                } else {
                    if (isset($disDigitsMap[$nextNode][$tempDis])) { // 如果已经记录过同样尾数， 不管
                        continue;
                    }
                    // 未记录过，继续遍历
                    $disDigitsMap[$nextNode][$tempDis] = true;
                    $nowQueueMap[$nextNode] = $tempDis;
                    processDisAndCircle($disDigitsMap, $groupMap, $connectMap, $nowQueueMap, $nextNode, $tempDis, $firstNode, $addCache, $reCache);
                    unset($nowQueueMap[$nextNode]);
                }
            }
        }
    }
}

function calcuConnectDigits(&$resultArr, &$inGroupFlag, &$connectMap, $firstNode, &$addCache, &$reCache)
{
    //从起点到各个节点, 是否存在各尾数的距离
    $disDigitsMap = [];
    
    //记录所有属于此组的节点
    $groupMap = [];
    
    $nowQueueMap = [];
    // 找环和能到的所有路径
    processDisAndCircle($disDigitsMap, $groupMap, $connectMap, $nowQueueMap, $firstNode, 0, $firstNode, $addCache, $reCache);
    
    //从前往后, 依次处理两两之间的距离
    $nodes = array_keys($groupMap);
    $count = count($nodes);
    if (isset($disDigitsMap[$firstNode][1]) || isset($disDigitsMap[$firstNode][3])  || isset($disDigitsMap[$firstNode][7])  || isset($disDigitsMap[$firstNode][9]) ||
            ((isset($disDigitsMap[$firstNode][2]) || isset($disDigitsMap[$firstNode][4])  || isset($disDigitsMap[$firstNode][6])  || isset($disDigitsMap[$firstNode][8]) ) && isset($disDigitsMap[$firstNode][5]))) { // 两两全部
        $allNum = $count * ($count - 1);
        for ($i = 0; $i <= 9; $i++) {
            $resultArr[$i] += $allNum;
        }
    } else if (isset($disDigitsMap[$firstNode][2]) || isset($disDigitsMap[$firstNode][4])  || isset($disDigitsMap[$firstNode][6])  || isset($disDigitsMap[$firstNode][8]) ) { // 全奇或全偶
        $tempMap = [];
        for ($i = 0; $i <= 8; $i+=2) {
            $tempMap[$i] = 0;
            $tempMap[$i+1] = 1;
        }
        
        $t1 = 0;
        foreach ($nodes as $node) {
            $value = null;
            foreach ($disDigitsMap[$node] as $dis => $dummy) {
                $value = $tempMap[$dis];
                break;
            }
            if ($value === 0) {
                $t1++;
            }
        }
        
        $t2 = $count - $t1;
        
        $oddSum = 2 * $t1 * $t2;
        $evenSum = $t1 * ($t1 - 1) + $t2 * ($t2 - 1);
        
        for ($i = 0; $i <= 8; $i+=2) {
            $resultArr[$i] += $evenSum;
            $resultArr[$i+1] += $oddSum;
        }
    } else if (isset($disDigitsMap[$firstNode][5])) { // 以5分隔
        $tempMap = [];
        $retempMap = [];
        for ($i = 0; $i <= 4; $i++) {
            $tempMap[$i] = $tempMap[$i+5] = $i;
            $retempMap[$i] = [$i, $i + 5];
        }
        
        //记录每两组能够产生的尾数
        $groupTailMap = [];
        for ($i = 0; $i < 4; $i++) {
            for ($j = $i + 1; $j <= 4; $j++) {
                $ttt = [];
                foreach ($retempMap[$i] as $t1) {
                    foreach ($retempMap[$j] as $t2) {
                        $addTemp = $addCache[$t1][$reCache[$t2]];
                        $ttt[$addTemp] = true;
                    }
                }
                $groupTailMap[$i][$j] = array_keys($ttt);
            }
        }
        
        $countMap = [0, 0, 0, 0, 0];
        foreach ($nodes as $node) {
            $value = null;
            foreach ($disDigitsMap[$node] as $dis => $dummy) {
                $value = $tempMap[$dis];
                break;
            }
            $countMap[$value]++;
        }
        
        $selfCount = 0;
        for ($i = 0; $i <= 4; $i++) {
            $selfCount += $countMap[$i] * ($countMap[$i] - 1);
        }
        $resultArr[0] += $selfCount;
        $resultArr[5] += $selfCount;
        
        for ($i = 0; $i < 4; $i++) {
            if ($countMap[$i] == 0) {
                continue;
            }
            for ($j = $i + 1; $j <= 4; $j++) {
                if ($countMap[$j] == 0) {
                    continue;
                }
                
                $addValue = $countMap[$i] * $countMap[$j];
                foreach ($groupTailMap[$i][$j] as $tail) {
                    $resultArr[$tail] += $addValue;
                    $resultArr[$reCache[$tail]] += $addValue;
                }
            }
        }
    } else { //全是0， 每个节点到起点只可能有一个值
        $countMap = [];
        for ($i = 0; $i <= 9; $i++) {
            $countMap[$i] = 0;
        }
        
        foreach ($nodes as $node) {
            $value = null;
            foreach ($disDigitsMap[$node] as $dis => $dummy) {
                $value = $dis;
                break;
            }
            $countMap[$value]++;
        }
        
        // 同一个值内部，两两组合
        $selfCount = 0;
        for ($i = 0; $i <= 9; $i++) {
            $selfCount += $countMap[$i] * ($countMap[$i] - 1);
        }
        $resultArr[0] += $selfCount;
        
        // 不同值之间
        for ($i = 0; $i < 9; $i++) {
            if ($countMap[$i] == 0) {
                continue;
            }
            for ($j = $i + 1; $j <= 9; $j++) {
                if ($countMap[$j] == 0) {
                    continue;
                }
                
                $addValue = $countMap[$i] * $countMap[$j];
                $tail = $addCache[$i][$reCache[$j]];
                
                $resultArr[$tail] += $addValue;
                $resultArr[$reCache[$tail]] += $addValue;
            }
        }
    }
    
    //标识为已有组别
    foreach ($nodes as $node) {
        $inGroupFlag[$node] = true;
    }
}

$stdin = fopen("php://stdin", "r");

fscanf($stdin, "%d %d\n", $road_nodes, $road_edges);

$road_from = array();
$road_to = array();
$road_weight = array();

for ($i = 0; $i < $road_edges; $i++) {
    fscanf($stdin, "%d %d %d\n", $road_from_item, $road_to_item, $road_weight_item);

    $road_from[] = $road_from_item;
    $road_to[] = $road_to_item;
    $road_weight[] = $road_weight_item;
}

fclose($stdin);
tollCostDigits($road_nodes, $road_from, $road_to, $road_weight);