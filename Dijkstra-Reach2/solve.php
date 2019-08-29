<?php
function shortestReach($n, &$connectMap, $s) {
    //是否已遍历
    $flagArr = [];
    
    //当前已计算出的最小距离数组
    $minDisArr = $connectMap[$s];
    
    // 起点
    $minDisArr[$s] = 0;
    $flagArr[$s] = true;
    
    //每次加入一个节点，最多n - 1步
    for ($i = 1; $i < $n; $i++) {
        //取未遍历的当前计算出的距离最小的节点
        $min = 300000000;
        $minIndex = null;
        for ($j = 1; $j <= $n; $j++) {
            if (isset($flagArr[$j]) || ! isset($minDisArr[$j])) {
                continue;
            }
            
            if ($minDisArr[$j] < $min) {
                $min = $minDisArr[$j];
                $minIndex = $j;
            }
        }
        
        if ($minIndex === null) { //没有可以遍历的了
            break;
        }
        
        //加入已遍历列表
        $flagArr[$minIndex] = true;
        
        //用此节点更新其他节点的最小距离
        if ( ! isset($connectMap[$minIndex])) {
            continue;
        }
        
        foreach ($connectMap[$minIndex] as $nextIndex => $nextDis) {
            if (isset($flagArr[$nextIndex])) {
                continue;
            }
            
            $tempDis = $min + $nextDis;
            if ( ! isset($minDisArr[$nextIndex]) || $minDisArr[$nextIndex] > $tempDis) {
                $minDisArr[$nextIndex] = $tempDis;
            }
        }
    }
    
    $result = [];
    
    // 按序加入
    for ($i = 1; $i <= $n; $i++) {
        if ($i == $s) {
            continue;
        }
        
        $result[] = $minDisArr[$i] ?? -1;
    }
    
    return $result;
}

$fptr = fopen(getenv("OUTPUT_PATH"), "w");

$stdin = fopen("php://stdin", "r");

fscanf($stdin, "%d\n", $t);

for ($t_itr = 0; $t_itr < $t; $t_itr++) {
    fscanf($stdin, "%[^\n]", $nm_temp);
    $nm = explode(' ', $nm_temp);

    $n = intval($nm[0]);

    $m = intval($nm[1]);

    //邻接矩阵
    $connectMap = array();

    for ($i = 0; $i < $m; $i++) {
        $buffer = fgets($stdin, 4096);
        $edge = explode(' ', trim($buffer));
        if (isset($connectMap[$edge[0]][$edge[1]]) && $connectMap[$edge[0]][$edge[1]] <= $edge[2]) { //有多条边的情况，只取最短的一条
            continue;
        }

        $connectMap[$edge[0]][$edge[1]] = $connectMap[$edge[1]][$edge[0]] = $edge[2];
    }

    fscanf($stdin, "%d\n", $s);

    $result = shortestReach($n, $connectMap, $s);

    fwrite($fptr, implode(" ", $result) . "\n");
}

fclose($stdin);
fclose($fptr);
