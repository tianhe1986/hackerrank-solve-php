<?php

function favoriteSequence($sequenceList)
{
    $result = [];
    // 用于记录连通关系
    $connectMap = [];
    // 记录入度数
    $indgreeMap = [];
    
    // 记录是否已处理
    $processedMap = [];
    
    // 使用优先队列作为堆，每次都处理顶点
    $heap = new SplPriorityQueue();
    
    // 处理连通图和入度
    foreach ($sequenceList as $sequence) {
        $k = count($sequence);
        
        if ( ! isset($indgreeMap[$sequence[0]])) {
            $indgreeMap[$sequence[0]] = 0;
        }
        
        if ($indgreeMap[$sequence[0]] == 0) { // 首个元素没有入度，先暂时入堆，处理堆时会再次判断
            $heap->insert($sequence[0], -$sequence[0]); // SplPriorityQueue是最大堆， 我们这里需要的是最小堆，因此将权值取反
        }
        
        // 建立边，增加入度
        for ($i = 0; $i < $k - 1; $i++) {
            if (isset($connectMap[$sequence[$i]][$sequence[$i + 1]])) { // 已经有同样的边了，不重复处理
                continue;
            }
            $connectMap[$sequence[$i]][$sequence[$i + 1]] = true;
            if ( ! isset($indgreeMap[$sequence[$i + 1]])) {
                $indgreeMap[$sequence[$i + 1]] = 0;
            }
            $indgreeMap[$sequence[$i + 1]]++;
        }
    }
    
    // 对于堆中的元素， 如果入度不为0或已处理， 则跳过
    while ( ! $heap->isEmpty()) {
        $node = $heap->top();
        $heap->extract();
        
        if (isset($processedMap[$node]) || $indgreeMap[$node] != 0) {
            continue;
        }
        
        $result[] = $node;
        $processedMap[$node] = true;
        
        if ( ! isset($connectMap[$node])) {
            continue;
        }
        
        foreach ($connectMap[$node] as $nextNode => $dummy) { // 此节点相连的节点，入度都减1
            $indgreeMap[$nextNode]--;
            if ($indgreeMap[$nextNode] == 0) { // 入度为0了，加入到堆中
                $heap->insert($nextNode, -$nextNode);
            }
        }
    }
    
    return $result;
}

$stdin = fopen("php://stdin", "r");

fscanf($stdin, "%d\n", $n);

$sequenceList = [];
for ($i = 0; $i < $n; $i++) {
    fscanf($stdin, "%d\n", $k);
    fscanf($stdin, "%[^\n]", $a_temp);

    $a = array_map('intval', preg_split('/ /', $a_temp, -1, PREG_SPLIT_NO_EMPTY));
    $sequenceList[] = $a;
}

fclose($stdin);

$result = favoriteSequence($sequenceList);

$fptr = fopen(getenv("OUTPUT_PATH"), "w");
fwrite($fptr, implode(" ",$result) . "\n");
fclose($fptr);