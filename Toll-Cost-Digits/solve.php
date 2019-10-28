<?php
function tollCostDigits($n, &$roadFrom, &$roadTo, &$roadWeight)
{
    //连通图
    //$connectMap = new SplFixedArray($n + 1);
    $connectMap = [];
    /*for ($i = 1; $i <= $n; $i++) {
        $connectMap[$i] = [];
    }*/
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
        //$connectMap[$roadFrom[$i]][$roadTo[$i]][$weight] = true;
        //$connectMap[$roadTo[$i]][$roadFrom[$i]][$rweight] = true;
        $connectMap[$roadFrom[$i]][] = [$roadTo[$i], $weight];
        $connectMap[$roadTo[$i]][] = [$roadFrom[$i], $rweight];
    }
    
    unset($roadFrom, $roadTo, $roadWeight);
    
    $resultArr = [];
    for ($i = 0; $i <= 9; $i++) {
        $resultArr[$i] = 0;
    }
    
    $inGroupFlag = [];
    $queue = new SplFixedArray(10 * $n + 1);
    for ($i = 1; $i <= $n; $i++) {
        if ( ! isset($inGroupFlag[$i])) {
            calcuConnectDigits($resultArr, $inGroupFlag, $connectMap, $i, $addCache, $reCache, $queue);
        }
    }
    
    for ($i = 0; $i <= 9; $i++) {
        echo $resultArr[$i]."\n";
    }
}

function processDisAndCircle(&$disDigitsMap, &$inGroupFlag, &$connectMap, &$firstNode, &$addCache, &$queue)
{
    $disDigitsMap[$firstNode][0] = true;
    
    // 队列每项存为一个数字， 节点id * 10 + 尾数
    $newItem = $firstNode * 10 + 0;
    //$newItem = [$firstNode, 0];
    
    $endIndex = 0;
    $nowIndex = 0;
    $queue[$endIndex++] = $newItem;
    
    while ($nowIndex < $endIndex) {
        $nowItem = $queue[$nowIndex];
        unset($queue[$nowIndex]);
        $nowIndex++;
        
        // 尾数
        $nowDis = $nowItem % 10;
        // 节点id
        $nowNode = ($nowItem - $nowDis)/10;
        //$nowDis = $nowItem[1];
        //$nowNode = $nowItem[0];
        
        if ( ! isset($inGroupFlag[$nowNode])) {
            $inGroupFlag[$nowNode] = true;
        }
        if ( ! isset($connectMap[$nowNode])) {
            continue;
        }
        foreach ($connectMap[$nowNode] as $item) { // 对于之后的每个节点
            $nextNode = $item[0];
            $nextDis = $item[1];
            $tempDis = $addCache[$nowDis][$nextDis];
            
            if (isset($disDigitsMap[$nextNode][$tempDis])) { // 如果已经记录过同样尾数， 不管
                continue;
            }
            
            $disDigitsMap[$nextNode][$tempDis] = 1;
            //$queue->enqueue([$nextNode, $tempDis]);
            // 队列每项存为一个数字， 节点id * 10 + 尾数
            $newItem = $nextNode * 10 + $tempDis;
            //$newItem = [$nextNode, $tempDis];
            $queue[$endIndex++] = $newItem;
        }
    }
}

function calcuConnectDigits(&$resultArr, &$inGroupFlag, &$connectMap, $firstNode, &$addCache, &$reCache, $queue)
{
    //从起点到各个节点, 是否存在各尾数的距离
    $disDigitsMap = [];
    /*for ($i = 1; $i <= $n; $i++) {
        $disDigitsMap[$i] = new SplFixedArray(10);
    }*/
    
    // 找环和能到的所有路径
    
    processDisAndCircle($disDigitsMap, $inGroupFlag, $connectMap, $firstNode, $addCache, $queue);

    // 统计起点到终点的距离
    $disCalMap = [];
    
    foreach ($disDigitsMap as $node => &$tempDisMap) {
        foreach ($tempDisMap as $dis => $dummy) {
            if ( ! isset($disCalMap[$dis])) {
                $disCalMap[$dis] = 0;
            }
            $disCalMap[$dis]++;
        }
    }
    
    // 对于每个节点
    foreach ($disDigitsMap as $node => &$tempDisMap) {
        $pathDis = null;
        foreach ($tempDisMap as $dis => $dummy) {
            $pathDis = $reCache[$dis];
            break;
        }
        
        foreach ($disCalMap as $i => &$dummy2) { // 计算每个尾数距离， 加到最终统计值中
            $realDis = $addCache[$i][$pathDis];
            $temp = $disCalMap[$i] - (isset($disDigitsMap[$node][$i]) ? 1 : 0);
            $resultArr[$realDis] += $temp;
        }
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
