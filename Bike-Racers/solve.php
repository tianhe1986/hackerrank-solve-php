<?php
/*
 * Complete the bikeRacers function below.
 */
function bikeRacers($bikers, $bikes, $k) {
    // 先求出所有距离，排序
    
    // 每一项是[少item index, 多item index, 两者之间的距离]
    $list = [];
    
    $itemDisList = [];
    
    $m = count($bikers);
    $n = count($bikes);
    
    
    // 以数量少的作为基准处理
    $minCount = $n;
    if ($m < $n) {
        $minCount = $m;
        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $diff1 = $bikers[$i][0] - $bikes[$j][0];
                $diff2 = $bikers[$i][1] - $bikes[$j][1];
                $diff = $diff1 * $diff1 + $diff2 * $diff2;
                $itemDisList[$i][] = [$j, $diff];
                $list[] = [$i, $j, $diff];
            }
        }
    } else {
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $m; $j++) {
                $diff1 = $bikes[$i][0] - $bikers[$j][0];
                $diff2 = $bikes[$i][1] - $bikers[$j][1];
                $diff = $diff1 * $diff1 + $diff2 * $diff2;
                $itemDisList[$i][] = [$j, $diff];
                $list[] = [$i, $j, $diff];
            }
        }
    }
    
    foreach ($itemDisList as $i => &$item) {
        usort($item, function($a, $b){
            return $a[1] - $b[1];
        });
    }
    
    usort($list, function($a, $b){
        return $a[2] - $b[2];
    });
    
    // 二分查找
    $low = 0;
    $high = $m * $n - 1;
    
    $min = null;
    
    while ($low <= $high) {
        $middle = ($low + $high) >> 1;
        // 能够满足，则作为最小值
        if (canBegin($itemDisList, $list[$middle][2], $minCount, $k)) {
            $min = $list[$middle][2];
            $high = $middle - 1;
        } else {
            $low = $middle + 1;
        }
    }
    
    return $min;
}

function canBegin(&$itemDisList, $maxDis, $listNum, $k)
{
    // 这里就是匈牙利算法了
    $count = 0;
    
    // 当前匹配
    $matchMap = [];
    for ($i = 0; $i < $listNum; $i++) {
        // 清除已搜索标记
        $visArr = [];
        if (canReach($itemDisList, $matchMap, $visArr, $i, $maxDis)) {
            $count++;
            if ($count >= $k) {
                return true;
            }
        }
    }
    return false;
}

function canReach(&$itemDisList, &$matchMap, &$visArr, $target, $maxDis)
{
    $end = count($itemDisList[$target]) - 1;
    for ($i = 0; $i <= $end ; $i++) {
        if ($itemDisList[$target][$i][1] > $maxDis) { // 加速，距离已经超了，后面的也就不用处理了
            return false;
        }
        
        $node = $itemDisList[$target][$i][0];
        
        if (isset($visArr[$node])) { // 已经遍历过了
            continue;
        }
        
        $visArr[$node] = true;
        
        if ( ! isset($matchMap[$node]) || canReach($itemDisList, $matchMap, $visArr, $matchMap[$node], $maxDis)) {
            $matchMap[$node] = $target;
            return true;
        }
    }

    return false;
}

$fptr = fopen(getenv("OUTPUT_PATH"), "w");

$stdin = fopen("php://stdin", "r");

fscanf($stdin, "%[^\n]", $nmk_temp);
$nmk = explode(' ', $nmk_temp);

$n = intval($nmk[0]);

$m = intval($nmk[1]);

$k = intval($nmk[2]);

$bikers = array();

for ($bikers_row_itr = 0; $bikers_row_itr < $n; $bikers_row_itr++) {
    fscanf($stdin, "%[^\n]", $bikers_temp);
    $bikers[] = array_map('intval', preg_split('/ /', $bikers_temp, -1, PREG_SPLIT_NO_EMPTY));
}

$bikes = array();

for ($bikes_row_itr = 0; $bikes_row_itr < $n; $bikes_row_itr++) {
    fscanf($stdin, "%[^\n]", $bikes_temp);
    $bikes[] = array_map('intval', preg_split('/ /', $bikes_temp, -1, PREG_SPLIT_NO_EMPTY));
}

$result = bikeRacers($bikers, $bikes, $k);

fwrite($fptr, $result . "\n");

fclose($stdin);
fclose($fptr);