<?php

/*
 * Complete the jeanisRoute function below.
 */
function jeanisRoute($citys, $roads) {
    // 需要投递的城市
    $deliverMap = [];
    $k = count($citys);
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
    
    // 其所有子节点中待投递城市的数量
    $subDeliveryCountArr = [];
    
    // 所有走过的边的距离之和
    $result = 0;
    
    // 最长距离
    $globalMaxDis = 0;
    
    processDis($subDeliveryCountArr, $connectMap, $deliverMap,  $result, $globalMaxDis, $root, 0, $k);

    
    return 2 * $result - $globalMaxDis;
}

function processDis(&$subDeliveryCountArr, &$connectMap, &$deliverMap, &$result, &$globalMaxDis, $nowCity, $parentCity, $k)
{
    $maxDis = -1;
    $secondDis = -1;
    
    $subDeliveryCountArr[$nowCity] = isset($deliverMap[$nowCity]) ? 1 : 0;

    foreach ($connectMap[$nowCity] as $nextCity => $dis) {
        if ($nextCity == $parentCity) { // 父节点，跳过
            continue;
        }
        
        // 遍历子节点
        $tempDis = processDis($subDeliveryCountArr, $connectMap, $deliverMap, $result, $globalMaxDis, $nextCity, $nowCity, $k);
        
        if ($tempDis < 0) { // 子节点子树（包括自己）中没有待投递节点
            continue;
        }
        
        $tempDis += $dis;
        if ($tempDis > $secondDis) { // 比第二大的要大
            $secondDis = $tempDis;
            if ($secondDis > $maxDis) { // 比最大的要大，进行交换
                $swapTemp = $maxDis;
                $maxDis = $secondDis;
                $secondDis = $swapTemp;
            }
        }
        
        if ($subDeliveryCountArr[$nextCity] > 0 && $subDeliveryCountArr[$nextCity] < $k) { // 有一条从其他待投递节点至nextCity子树中的待投递节点的路径
            $result += $dis;
        }
        
        // 累加子节点中待投递节点数
        $subDeliveryCountArr[$nowCity] += $subDeliveryCountArr[$nextCity];
    }
    
    $newDis = 0;
    if ($secondDis >= 0) { // 有两棵子树都有待投递节点
        $newDis = $maxDis + $secondDis;
    } else if ($maxDis > 0) { // 只有一棵子树有待投递节点
        if (isset($deliverMap[$nowCity])) { // 此节点也是待投递节点
            $newDis = $maxDis;
        }
    }
    
    if ($newDis > $globalMaxDis) {
        $globalMaxDis = $newDis;
    }
    
    // 返回当前节点到子树中所有待投递节点（包括自己）的最大距离
    return $maxDis > 0 ? $maxDis : (isset($deliverMap[$nowCity]) ? 0 : $maxDis);
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
