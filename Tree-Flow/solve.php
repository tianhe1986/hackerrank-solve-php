<?php

/*
 * Complete the treeFlow function below.
 */
function treeFlow($n, &$connectMap) {
    $start = 1;
    $end = $n;
    
    // 正向遍历，起点到每个点的距离
    $queue = new SplFixedArray($n + 1);
    
    $disMap = new SplFixedArray($n + 1);
    processDis($start, $disMap, $connectMap, $queue, $n);
    
    // 反向遍历，终点到每个点的距离
    $reDisMap = new SplFixedArray($n + 1);
    processDis($end, $reDisMap, $connectMap, $queue, $n);
    
    // 正向遍历，每个节点子节点数，父节点，向终点走的子节点。
    
    $result = 0;
    $remain = 0;
    $lack = 0;
    // 每个节点，
    for ($i = 2; $i < $n; $i++) {
        // 只从起点出发，到此节点，再到终点的流量
        // 剩余（缺失流量）处理
        $diff = $disMap[$i] - $reDisMap[$i];
        if ($diff >= 0) {
            $result += $reDisMap[$i];
            $remain += $diff;
        } else {
            $result += $disMap[$i];
            $lack -= $diff;
        }
    }
    
    // 最后加上剩余/缺失流量的最小值
    $result += min($remain, $lack);
    $result += $disMap[$end];
    return $result;
}

// 广度优先求最短路径长度
function processDis($startNode, &$distanceMap, &$connectMap, &$queue, $n)
{
    $nowIndex = 0;
    $endIndex = 0;
    $flagArr = new SplFixedArray($n + 1);
    $queue[$endIndex++] = $startNode.",0"; // 我也想直接存一个数组，但是那样内存为超
    $flagArr[$startNode] = true;
    
    while ($nowIndex < $endIndex) {
        $item = explode(',', $queue[$nowIndex]);
        $nowNode = intval($item[0]);
        $nowDis = intval($item[1]);
        unset($queue[$nowIndex++]);
        $distanceMap[$nowNode] = $nowDis;
        foreach ($connectMap[$nowNode] as $nextNode => $dis) { // 继续遍历
            if (isset($flagArr[$nextNode])) {
                continue;
            }
            $queue[$endIndex++] = $nextNode.",".($nowDis + $dis);
            $flagArr[$nextNode] = true;
        }
    }
}

$fptr = fopen(getenv("OUTPUT_PATH"), "w");

$stdin = fopen("php://stdin", "r");

fscanf($stdin, "%d\n", $n);

// 在读数据时处理连通数组，传进去
$connectMap = [];
for ($tree_row_itr = 0; $tree_row_itr < $n-1; $tree_row_itr++) {
    fscanf($stdin, "%[^\n]", $tree_temp);
    $item = array_map('intval', preg_split('/ /', $tree_temp, -1, PREG_SPLIT_NO_EMPTY));
    $connectMap[$item[0]][$item[1]] = $connectMap[$item[1]][$item[0]] = $item[2];
}

$result = treeFlow($n, $connectMap);

fwrite($fptr, $result . "\n");

fclose($stdin);
fclose($fptr);
