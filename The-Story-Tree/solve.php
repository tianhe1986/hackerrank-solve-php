<?php
function storyOfATree($n, $edges, $k, $guesses) {
    // connect map
    $connectMap = [];  
    foreach ($edges as $item) {
        $connectMap[$item[0]][$item[1]] = $connectMap[$item[1]][$item[0]] = true;
    }
    
    // first map 用于计算节点1为根时的情况，父 -> 子 加1分
    $firstValueMap = [];
    // guess map 父 -> 子 加1分， 子 -> 父 减1分
    $guessMap = [];
    foreach ($guesses as $item) {
        if ( ! isset($guessMap[$item[0]][$item[1]])) {
            $guessMap[$item[0]][$item[1]] = $guessMap[$item[1]][$item[0]] = 0;
        }
        $guessMap[$item[0]][$item[1]]++;
        $guessMap[$item[1]][$item[0]]--;
        
        if ( ! isset($firstValueMap[$item[0]][$item[1]])) {
            $firstValueMap[$item[0]][$item[1]] = 0;
        }

        $firstValueMap[$item[0]][$item[1]]++;
    }
    
    $winCount = 0;
    // 计算节点1为根时猜对次数
    $initValue = calcuFirstValue($connectMap, $firstValueMap, 1, 0);

    // 递归遍历每个节点为根时
    processRightValue($connectMap, $guessMap, 1, 0, $initValue, $k, $winCount);
    
    if ($winCount == 0) {
        return "0/1";
    } else if ($winCount == $n) {
        return "1/1";
    } else {
        return reduce($winCount, $n);
    }
}

// 计算首个节点为根时猜中的次数
function calcuFirstValue(&$connectMap, &$firstValueMap, $nowIndex, $parentIndex)
{
    // 猜中了， +1分
    $result = $firstValueMap[$parentIndex][$nowIndex] ?? 0;
    foreach ($connectMap[$nowIndex] as $nextIndex => $dummy) {
        if ($nextIndex == $parentIndex) {
            continue;
        }
        
        $result += calcuFirstValue($connectMap, $firstValueMap, $nextIndex, $nowIndex);
    }
    return $result;
}

// 遍历处理每个节点为根时猜中的次数
function processRightValue(&$connectMap, &$guessMap, $nowIndex, $parentIndex, $nowValue, &$winValue, &$winCount)
{
    // 此节点变成了根， 只有父节点变成其子节点，其他不变
    $newValue = $nowValue + ($guessMap[$nowIndex][$parentIndex] ?? 0);
    if ($newValue >= $winValue) {
        $winCount++;
    }

    foreach ($connectMap[$nowIndex] as $nextIndex => $dummy) {
        if ($nextIndex == $parentIndex) {
            continue;
        }
        
        processRightValue($connectMap, $guessMap, $nextIndex, $nowIndex, $newValue, $winValue, $winCount);
    }
}

// 输出指定格式的结果
function reduce($winCount, $n)
{
    //找最大公约数
    $gcd = findGcd($winCount, $n);
    
    // 约分
    return ($winCount/$gcd)."/".($n/$gcd);
}

// 找最大公约数
function findGcd($winCount, $n)
{
    $mod = $n % $winCount;
    while ($mod != 0) {
        $n = $winCount;
        $winCount = $mod;
        $mod = $n % $winCount;
    }
    
    return $winCount;
}