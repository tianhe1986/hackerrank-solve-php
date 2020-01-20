<?php

/*
 * Complete the jeanisRoute function below.
 */
function jeanisRoute($citys, $roads) {
    // 需要投递的城市
    $deliverMap = [];
    foreach ($citys as $city) {
        $deliverMap[$city] = true;
    }
    
    // road转成connectMap
    $connectMap = [];
    foreach ($roads as $item) {
        $connectMap[$item[0]][$item[1]] = $connectMap[$item[1]][$item[0]] = $item[2];
    }
    
    // 以第一个节点作为根
    $root = 1;
    
    // 其所有投递城市子节点到此节点的最长距离
    $maxHeightArr = [];
    
    // 连接两个投递城市节点的最长距离
    $maxDisItem = [0, []];
    
    processMaxDis($maxHeightArr, $maxDisItem, $deliverMap, $connectMap, $root, 0);
    
    $result = 0;
    
    // 最长距离对应的一个点作为起点
    $start = $maxDisItem[1][0];
    
    // 从起点开始遍历，找到到每个点的路径
    $parentMap = [];
    processParent($parentMap, $connectMap, $start, 0);
    
    // 记录当前点是否已经计算过路径
    $calcutedArr = [];
    $calcutedArr[$start] = true;
    
    // 从每个待投递城市开始，向其父节点遍历
    foreach ($citys as $city) {
        calcuParentDis($result, $connectMap, $parentMap, $calcutedArr, $city);
    }
    
    // 再减去一个最长距离，得到结果
    $result -= $maxDisItem[0];
    
    return $result;
}

function processMaxDis(&$maxHeightArr, &$maxDisItem, &$deliverMap, &$connectMap, $nowCity, $parentCity)
{
    $sonDisArr = [];
    foreach ($connectMap[$nowCity] as $nextCity => $dis) {
        if ($nextCity == $parentCity) { // 父节点，跳过
            continue;
        }
        
        // 遍历子节点
        processMaxDis($maxHeightArr, $maxDisItem, $deliverMap, $connectMap, $nextCity, $nowCity);
        
        // 如果子节点有最长距离，或是子节点是待投递城市，则继续处理
        $sonDis = null;
        $sonNode = null;
        if (isset($maxHeightArr[$nextCity])) {
            $sonDis = $dis + $maxHeightArr[$nextCity][0];
            $sonNode = $maxHeightArr[$nextCity][1];
        } else if (isset($deliverMap[$nextCity])) {
            $sonDis = $dis;
            $sonNode = $nextCity;
        } else {
            continue;
        }
        
        $sonDisArr[] = [$sonDis, $sonNode];
    }
    
    // 根据有子节点最长距离的数量进行处理
    
    // 没有，那算了
    if (empty($sonDisArr)) {
        return;
    }
    
    // 有1个，自己也是待投递城市，则进行比较
    if (1 == count($sonDisArr)) {
        if (isset($deliverMap[$nowCity])) {
            if ($sonDisArr[0][0] > $maxDisItem[0]) {
                $maxDisItem[0] = $sonDisArr[0][0];
                $maxDisItem[1] = [$nowCity, $sonDisArr[0][1]];
            }
        }
    } else { // 有2个以上，取最大的两个进行处理
        usort($sonDisArr, function($a, $b){
           return $b[0] - $a[0]; 
        });
        
        $sumDis = $sonDisArr[0][0] + $sonDisArr[1][0];
        if ($sumDis > $maxDisItem[0]) {
            $maxDisItem[0] = $sumDis;
            $maxDisItem[1] = [$sonDisArr[0][1], $sonDisArr[1][1]];
        }
    }
    
    $maxHeightArr[$nowCity] = $sonDisArr[0];
}

function processParent(&$parentMap, &$connectMap, $nowCity, $parentCity)
{
    foreach ($connectMap[$nowCity] as $nextCity => $dis) {
        if ($nextCity == $parentCity) { // 父节点，跳过
            continue;
        }
        
        $parentMap[$nextCity] = $nowCity;
        
        // 遍历子节点
        processParent($parentMap, $connectMap, $nextCity, $nowCity);
    }
}

function calcuParentDis(&$result, &$connectMap, &$parentMap, &$calcutedArr, $city)
{
    $now = $city;
    while (! isset($calcutedArr[$now])) { // 当前节点未计算，则继续向父节点遍历
        $calcutedArr[$now] = true;
        
        $parent = $parentMap[$now];
        $result += 2 * $connectMap[$now][$parent];
        
        $now = $parent;
    }
}

$fptr = fopen(getenv("OUTPUT_PATH"), "w");

$stdin = fopen("php://stdin", "r");

fscanf($stdin, "%[^\n]", $nk_temp);
$nk = explode(' ', $nk_temp);

$n = intval($nk[0]);

$k = intval($nk[1]);

fscanf($stdin, "%[^\n]", $city_temp);

$city = array_map('intval', preg_split('/ /', $city_temp, -1, PREG_SPLIT_NO_EMPTY));

$roads = array();

for ($roads_row_itr = 0; $roads_row_itr < $n-1; $roads_row_itr++) {
    fscanf($stdin, "%[^\n]", $roads_temp);
    $roads[] = array_map('intval', preg_split('/ /', $roads_temp, -1, PREG_SPLIT_NO_EMPTY));
}

$result = jeanisRoute($city, $roads);

fwrite($fptr, $result . "\n");

fclose($stdin);
fclose($fptr);
