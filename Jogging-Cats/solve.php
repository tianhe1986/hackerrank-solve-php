<?php
function joggingCats($roads, $n) {
    // 转connect map
    $connectMap = [];
    foreach ($roads as $road) {
        $connectMap[$road[0]][$road[1]] = $connectMap[$road[1]][$road[0]] = true;
    }
    
    $threshold = intval(pow($n, 1/3));
    
    // 邻居数超过threshold的数量
    $bigFlag = [];
    
    // big邻居表
    $bigAdjMap = [];
    
    $bigIndex = 0;
    foreach ($connectMap as $section => $item) {
        if (count($item) >= $threshold) {
            $bigFlag[$section] = $bigIndex++;
        }
    }
    
    foreach ($connectMap as $section => $item) {
        foreach ($item as $adj => $dummy) {
            if (isset($bigFlag[$adj])) {
                $bigAdjMap[$section][] = $adj;
            }
        }
    }

    // 三个节点 a-b-c，都是大节点的情况
    $allBigNum = [];
    
    $direct = true;
    if ($bigIndex > 2000) {
        $allBigNum = new SplFixedArray($bigIndex);
        for ($i = 0; $i < $bigIndex; $i++) {
            $allBigNum[$i] = new SplFixedArray($bigIndex);
        }
        $direct = false;
    }
    
    // 三个节点a-b-c，a和c是大节点，b是小节点的情况
    $bigSmallNum = [];
    
    $result = 0;
    // 4个节点，都是大的，其实就是对于$allBigNum中的每项t, 求t * (t-1)/2的和，最后再除以4
    $big4Num = 0;
    for ($i = 1; $i <= $n; $i++) {
        if ( ! isset($bigFlag[$i]) || ! isset($bigAdjMap[$i])) {
            continue;
        }
        
        foreach ($bigAdjMap[$i] as $bigAdj) {
            if ( ! isset($bigAdjMap[$bigAdj])) {
                continue;
            }
            
            foreach ($bigAdjMap[$bigAdj] as $newBigAdj) {
                if ($newBigAdj <= $i) {
                    continue;
                }
                
                // 这里比较巧妙，每多一个节点，以i和newBigAdj为对角线的4大节点环就多$allBigNum[$i][$newBigAdj]个，也就是新来的节点能够与之前的任一节点组合
                if ( ! isset($allBigNum[$bigFlag[$i]][$bigFlag[$newBigAdj]])) {
                    $allBigNum[$bigFlag[$i]][$bigFlag[$newBigAdj]] = 1;
                } else {
                    if ( ! $direct) {
                        $big4Num += ($allBigNum[$bigFlag[$i]][$bigFlag[$newBigAdj]]);
                        $allBigNum[$bigFlag[$i]][$bigFlag[$newBigAdj]] = $allBigNum[$bigFlag[$i]][$bigFlag[$newBigAdj]] + 1;
                    } else {
                        $big4Num += ($allBigNum[$bigFlag[$i]][$bigFlag[$newBigAdj]]++);
                    }
                }
            }
        }
    }

    // 最后除以4，是因为对于每个环 a b c d， allBigNum[a][c]算了一次，ca算了一次，bd算了一次，db算了一次
    $result += $big4Num / 2;

    // 3大1小，对小的遍历其相邻大节点，加上对应的$allBigNum的值
    $big3Num = 0;
    
    for ($i = 1; $i <= $n; $i++) {
        if (isset($bigFlag[$i]) || ! isset($bigAdjMap[$i])) {
            continue;
        }
        
        // 防止重复计算
        $tempCount = count($bigAdjMap[$i]);
        
        for ($j = 0; $j < $tempCount; $j++) {
            $first = $bigAdjMap[$i][$j];
            for ($k = $j + 1; $k < $tempCount; $k++) {
                $second = $bigAdjMap[$i][$k];
                $tempMin = $first;
                $tempMax = $second;
                if ($second < $first) {
                    $tempMin = $second;
                    $tempMax = $first;
                }
                
                if (isset($allBigNum[$bigFlag[$tempMin]][$bigFlag[$tempMax]])) {
                    $big3Num += $allBigNum[$bigFlag[$tempMin]][$bigFlag[$tempMax]];
                }
            }
        }
    }
    $result += $big3Num;
    unset($allBigNum);
    
    // 2大2小，大的成对角，其实就是对于$bigSmallNum中的每项t, 求t * (t-1)/2的和，最后再除以2
    // 实际处理，类似big3Num
    $big2diaNum = 0;
    for ($i = 1; $i <= $n; $i++) {
        if (isset($bigFlag[$i]) || ! isset($bigAdjMap[$i])) {
            continue;
        }
        
        // 防止重复计算
        $tempCount = count($bigAdjMap[$i]);
        
        for ($j = 0; $j < $tempCount; $j++) {
            $first = $bigAdjMap[$i][$j];
            for ($k = $j + 1; $k < $tempCount; $k++) {
                $second = $bigAdjMap[$i][$k];
                $tempMin = $first;
                $tempMax = $second;
                if ($second < $first) {
                    $tempMin = $second;
                    $tempMax = $first;
                }
                
                if ( ! isset($bigSmallNum[$tempMin][$tempMax])) {
                    $bigSmallNum[$tempMin][$tempMax] = 1;
                } else {
                    $big2diaNum += ($bigSmallNum[$tempMin][$tempMax]++);
                }
            }
        }
    }
    $result += $big2diaNum;
    unset($bigSmallNum);
    
    // 2大2小，大的相连，直接暴力莽
    $big2conNum = 0;
    for ($i = 1; $i <= $n; $i++) {
        if (isset($bigFlag[$i]) || ! isset($bigAdjMap[$i]) || ! isset($connectMap[$i])) {
            continue;
        }
        
        foreach ($connectMap[$i] as $next => $dummy) {
            if (isset($bigFlag[$next])) {
                continue;
            }
            
            if ( ! isset($bigAdjMap[$next])) {
                continue;
            }
            
            foreach ($bigAdjMap[$i] as $bigOne) {
                foreach ($bigAdjMap[$next] as $bigTwo) {
                    if (isset($connectMap[$bigOne][$bigTwo])) {
                        $big2conNum++;
                    }
                }
            }
        }
    }
    $result += $big2conNum / 2;
    
    
    // 3小，4小，也直接莽
    $big1Num = 0;
    $big0Num = 0;
    
    for ($i = 1; $i <= $n; $i++) {
        if (isset($bigFlag[$i]) || ! isset($connectMap[$i])) {
            continue;
        }
        
        $keys = array_keys($connectMap[$i]);
        $tempCount = count($keys);
        
        for ($j = 0; $j < $tempCount; $j++) {
            $first = $keys[$j];
            if (isset($bigFlag[$first]) || ! isset($connectMap[$first])) {
                continue;
            }
            
            for ($k = $j + 1; $k < $tempCount; $k++) {
                $second = $keys[$k];
                if (isset($bigFlag[$second])) {
                    continue;
                }
                
                foreach ($connectMap[$first] as $next => $dummy) {
                    if ($next == $i || $next == $second) {
                        continue;
                    }
                    
                    if (isset($connectMap[$next][$second])) {
                        if (isset($bigFlag[$next])) {
                            $big1Num++;
                        } else {
                            $big0Num++;
                        }
                    }
                }
            }
        }
    }
    
    $result += $big1Num + $big0Num/4;

    return $result;
}


$fptr = fopen(getenv("OUTPUT_PATH"), "w");

$stdin = fopen("php://stdin", "r");

fscanf($stdin, "%[^\n]", $nc_temp);
$nc = explode(' ', $nc_temp);

$n = intval($nc[0]);

$m = intval($nc[1]);

$roads = [];
$temp = '';
for ($i = 0; $i < $m; $i++) {
    fscanf($stdin, "%[^\n]", $temp);
    $roads[] = explode(' ', trim($temp));
}

$result = joggingCats($roads, $n);

fwrite($fptr, $result . "\n");

fclose($stdin);
fclose($fptr);
