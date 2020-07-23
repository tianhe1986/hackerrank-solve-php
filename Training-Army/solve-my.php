<?php
function trainingArmy($skillArr, $transfromArr, $transtoArr)
{
    // 已经有的技能
    $already = 0;
    $skillNum = count($skillArr);
    
    // 用于保存技能flag，现在是多了还是缺，-1为缺，大于0则多出来
    $flagArr = [];
    
    for ($i = 0; $i < $skillNum; $i++) {
        if ($skillArr[$i] == 0) {
            $flagArr[$i + 1] = -1;
        } else {
            $flagArr[$i + 1] = $skillArr[$i] - 1;
            $already++;
        }
    }
    
    $wizardNum = count($transfromArr);
    
    // 源点
    $start = 0;
    
    // 汇点
    $end = (2 * $wizardNum + 2) * $skillNum + 1;
    
    // 边
    $edges = [];
    $nextEdgeIndex = 0;
    
    // 每个节点为出点的第一条边的index, 默认是-1，即没有
    $head = array_fill(0, $end + 1, -1);
    
    for ($i = 1; $i <= $skillNum; $i++) {
        // 多出来的，从源点进入from集合点
        // 少的，从to集合点进入汇点
        if ($flagArr[$i] > 0) {
            addEdge($edges, $nextEdgeIndex, $head, $start, $i, $flagArr[$i]);
            addEdge($edges, $nextEdgeIndex, $head, $i, $start, 0);
        } else if ($flagArr[$i] == -1) {
            addEdge($edges, $nextEdgeIndex, $head, $i + $skillNum, $end, 1);
            addEdge($edges, $nextEdgeIndex, $head, $end, $i + $skillNum, 0);
        }
    }
    
    $nowOffset = 2 * $skillNum;
    // 每个转换数组
    for ($i = 0; $i < $wizardNum; $i++) {
        // from集合点 -> from点
        foreach ($transfromArr[$i] as $fromNode) {
            addEdge($edges, $nextEdgeIndex, $head, $fromNode, $nowOffset + $fromNode, 1);
            addEdge($edges, $nextEdgeIndex, $head, $nowOffset + $fromNode, $fromNode, 0);
        }
        
        foreach ($transtoArr[$i] as $toNode) {
            // to点 -> from集合点
            addEdge($edges, $nextEdgeIndex, $head, $nowOffset + $skillNum + $toNode, $toNode, 1);
            addEdge($edges, $nextEdgeIndex, $head, $toNode, $nowOffset + $skillNum + $toNode, 0);
            
            // to点 -> to集合点
            addEdge($edges, $nextEdgeIndex, $head, $nowOffset + $skillNum + $toNode, $skillNum + $toNode, 1);
            addEdge($edges, $nextEdgeIndex, $head, $skillNum + $toNode, $nowOffset + $skillNum + $toNode, 0);
        }
        
        
        foreach ($transfromArr[$i] as $fromNode) {
            foreach ($transtoArr[$i] as $toNode) {
                // from -> to， 忽略自己转自己吧
                if ($fromNode == $toNode) {
                    continue;
                }
                addEdge($edges, $nextEdgeIndex, $head, $nowOffset + $fromNode, $nowOffset + $skillNum + $toNode, 1);
                addEdge($edges, $nextEdgeIndex, $head, $nowOffset + $skillNum + $toNode, $nowOffset + $fromNode, 0);
            }
        }
        
        $nowOffset += 2 * $skillNum;
    }
    
    // 用dinic算法求最大流
    $result = 0;
    
    // 用于保存分层信息
    $depthArr = [];
    
    // 用于保存当前遍历到的位置
    $nowLocArr = [];
    
    while (bfs($depthArr, $edges, $head, $end)) {
        $nowLocArr = $head;
        while (($newFlow = dfs($depthArr, $edges, $head, $nowLocArr, $end, 0, 1)) > 0) { // 不断寻找增广路，流量反正只可能是1
            $result += $newFlow;
        }
    }
    
    return $already + $result;
}


// 求每个节点的层次
function bfs(&$depthArr, &$edges, &$head, $end)
{
    // 全部清空
    $depthArr = [];
    
    $queue = new SplQueue();
    // 源点层次设为1
    $depthArr[0] = 1;
    $queue->enqueue(0);
    
    while ( ! $queue->isEmpty()) {
        $node = $queue->dequeue();
        for ($edgeIndex = $head[$node]; $edgeIndex != -1;) { // 广度优先，将还没有层次的点加入层次
            $edge = $edges[$edgeIndex];
            
            if ($edge[1] > 0 && ! isset($depthArr[$edge[0]])) { // 有容量且还未遍历，则加入队列
                $depthArr[$edge[0]] = $depthArr[$node] + 1;
                
                $queue->enqueue($edge[0]);
            }
            
            $edgeIndex = $edge[2];
        }
    }
    
    // 检查汇点是否可达
    return isset($depthArr[$end]);
}

// 寻找增广路
function dfs(&$depthArr, &$edges, &$head, &$nowLocArr, $end, $node, $flow)
{
    if ($node == $end) { // 到达汇点了
        return $flow;
    }
    
    for ($edgeIndex = $nowLocArr[$node]; $edgeIndex != -1;) { // 遍历能走的每条路， 有流量则直接返回
        $edge = $edges[$edgeIndex];
        
        // 有容量，且满足层级要求
        if ($edge[1] > 0 && $depthArr[$edge[0]] == $depthArr[$node] + 1) { // 有容量且满足层级要求，继续遍历
            $newFlow = dfs($depthArr, $edges, $head, $nowLocArr, $end, $edge[0], min($edge[1], $flow));
            
            if ($newFlow > 0) { // 走通了，减少对应边的容量，增加反向边的容量
                $edges[$edgeIndex][1] -= $newFlow;
                $edges[$edgeIndex^1][1] += $newFlow;
                return $newFlow;
            }
        }

        $nowLocArr[$node] = $edgeIndex = $edge[2];
    }
    
    // 所有路都走不通
    return 0;
}

function addEdge(&$edges, &$nextEdgeIndex, &$head, $fromNode, $toNode, $capacity)
{
    $edges[$nextEdgeIndex] = [$toNode, $capacity, $head[$fromNode]];  // 三元组， 指向点， 容量， 下一条以from为出点的条
    $head[$fromNode] = $nextEdgeIndex++;
}


/*
$skillArr = [3, 0, 0];
$transfromArr = [[1]];
$transtoArr = [[2, 3]];

$result = trainingArmy($skillArr, $transfromArr, $transtoArr);
echo $result . "\n";

exit;*/

$fptr = fopen(getenv("OUTPUT_PATH"), "w");

$stdin = fopen("php://stdin", "r");

$temp = '';
fscanf($stdin, "%[^\n]", $temp);
$nm = explode(' ', $temp);

$n = intval($nm[0]);

$t = intval($nm[1]);

fscanf($stdin, "%[^\n]", $temp);
$skillArr = array_map('intval', preg_split('/ /', $temp, -1, PREG_SPLIT_NO_EMPTY));

$transfromArr = [];
$transtoArr = [];
for ($i = 0; $i < $t; $i++) {
    fscanf($stdin, "%[^\n]", $temp);
    $newArr = array_map('intval', preg_split('/ /', $temp, -1, PREG_SPLIT_NO_EMPTY));
    array_splice($newArr, 0, 1);
    $transfromArr[] = $newArr;
    
    fscanf($stdin, "%[^\n]", $temp);
    $newArr = array_map('intval', preg_split('/ /', $temp, -1, PREG_SPLIT_NO_EMPTY));
    array_splice($newArr, 0, 1);
    $transtoArr[] = $newArr;
}

$result = $result = trainingArmy($skillArr, $transfromArr, $transtoArr);

fwrite($fptr, $result . "\n");

fclose($stdin);
fclose($fptr);