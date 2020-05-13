<?php
function findStrings($w, $queries) {
    
    //构造后缀数组
    $lenMap = [];
    $maxLen = 0;
    foreach ($w as $index => $s) {
        $lenMap[$index] = strlen($s);
        if ($lenMap[$index] > $maxLen) {
            $maxLen = $lenMap[$index];
        }
    }
    
    // 二元组, [属于第几个字符串, 在对应字符串的位置]
    $suffixArr = [];
    buildSuffixArr($suffixArr, $w, $lenMap, $maxLen);
    
    //用kasai算法，计算后缀数组中相邻两项的最长公共前缀长度
    $longestCommonPrefixArr = [-1 => 0];
    buildLCPWithKasai($longestCommonPrefixArr, $suffixArr, $w, $lenMap);
    
    // 根据前缀数组和最长公共前缀，生成范围数组
    $suffixLen = count($suffixArr);
    $rangeArr = [];
    
    $rangeArr[] = [1, $lenMap[$suffixArr[0][0]] - $suffixArr[0][1]];
    for ($i = 1; $i < $suffixLen; $i++) {
        $tempLen = $lenMap[$suffixArr[$i][0]] - $suffixArr[$i][1] - $longestCommonPrefixArr[$i - 1];
        if ($tempLen == 0) {
            $rangeArr[] = [$rangeArr[$i-1][1], $rangeArr[$i-1][1]];
        } else {
            $rangeArr[] = [$rangeArr[$i-1][1] + 1, $rangeArr[$i-1][1] + $tempLen];
        }
    }
    
    // 对于每个查询,二分查找,找到范围数组中满足条件的项， 返回对应的字符串
    $result = [];
    
    foreach ($queries as $query) {
        $result[] = getQueryStr($suffixArr, $rangeArr, $longestCommonPrefixArr, $w, $query);
    }
    
    return $result;
}

function getQueryStr(&$suffixArr, &$rangeArr, &$longestCommonPrefixArr, &$w, $value)
{
    $low = 0;
    $high = count($rangeArr) - 1;
    
    if ($value > $rangeArr[$high][1]) { // 超出范围了
        return 'INVALID';
    }
    
    while ($low <= $high) {
        $middle = ($low + $high) >> 1;
        if ($rangeArr[$middle][0] <= $value) {
            if ($rangeArr[$middle][1] >= $value) {
                return substr($w[$suffixArr[$middle][0]], $suffixArr[$middle][1], $longestCommonPrefixArr[$middle-1] + $value - $rangeArr[$middle][0] + 1);
            } else {
                $low = $middle + 1;
            }
        } else {
            $high = $middle - 1;
        }
    }
}

function buildSuffixArr(&$suffixArr, &$w, &$lenMap, $maxLen)
{
    //每一位当前排序
    $rankArr = [];
    //用于临时储存当前排序
    $tempRankArr = [];
    
    $n = count($lenMap);
    for ($i = 0; $i < $n; $i++) {
        for ($j = 0; $j < $lenMap[$i]; $j++) {
            $suffixArr[] = [$i, $j];
            $rankArr[$i][$j] = ord($w[$i][$j]) - 96;
        }
    }
    
    $suffixLen = count($suffixArr);
    
    
    for ($k = 1; $k <= $maxLen; $k = $k << 1) {
        //使用快速排序
        // TODO: 使用基数排序
        usort($suffixArr, function($a, $b) use (&$rankArr, $k, &$lenMap) {
            if ($rankArr[$a[0]][$a[1]] != $rankArr[$b[0]][$b[1]]) {
                return $rankArr[$a[0]][$a[1]] - $rankArr[$b[0]][$b[1]];
            }
            
            $t1 = ($a[1] + $k >= $lenMap[$a[0]]) ? -1 : $rankArr[$a[0]][$a[1] + $k];
            $t2 = ($b[1] + $k >= $lenMap[$b[0]]) ? -1 : $rankArr[$b[0]][$b[1] + $k];
            
            return $t1 - $t2;
        });
        
        //重新生成rank
        $nowRank = 0;
        $tempRankArr[$suffixArr[0][0]][$suffixArr[0][1]] = $nowRank;
        for ($i = 1; $i < $suffixLen; $i++) {
            //与前一个不同，则nowRank++
            if ( ! isSame($rankArr, $suffixArr, $i, $k, $lenMap)) {
                $nowRank++;
            }
            $tempRankArr[$suffixArr[$i][0]][$suffixArr[$i][1]] = $nowRank;
        }
        
        for ($i = 0; $i < $n; $i++) {
            $rankArr[$i] = $tempRankArr[$i];
        }
        
        if ($nowRank == $suffixLen - 1) { //已经每一个都有不同的值了
            break;
        }
    }
}

// 判断相邻两项前2k个字符是否相等
function isSame(&$rankArr, &$suffixArr, $i, $k, &$lenMap)
{
    $a = $suffixArr[$i - 1];
    $b = $suffixArr[$i];
    if ($rankArr[$a[0]][$a[1]] != $rankArr[$b[0]][$b[1]]) {
        return false;
    }
            
    $t1 = ($a[1] + $k >= $lenMap[$a[0]]) ? -1 : $rankArr[$a[0]][$a[1] + $k];
    $t2 = ($b[1] + $k >= $lenMap[$b[0]]) ? -1 : $rankArr[$b[0]][$b[1] + $k];

    return $t1 == $t2;
}

function buildLCPWithKasai(&$longestCommonPrefixArr, &$suffixArr, &$w, &$lenMap)
{
    $len = count($suffixArr);
    //反向数组，用于查找下一项要处理的
    $indexToSuffixArr = [];
    for ($i = 0; $i < $len; $i++) {
        $indexToSuffixArr[$suffixArr[$i][0]][$suffixArr[$i][1]] = $i;
    }
    
    //当前的公共长度
    $n = count($lenMap);
    $nowLength = 0;
    
    for ($i = 0; $i < $n; $i++) {
        for ($j = 0; $j < $lenMap[$i]; $j++) {
            // 在当前排列之后的一项
            
            $nextSuffixIndex = $indexToSuffixArr[$i][$j] + 1;
            
            // 已经是整个数组最后一项了，将nowLength重置为0，继续
            if ($nextSuffixIndex >= $len) {
                $nowLength = 0;
                continue;
            }
            
            // 从nowLength的位置开始比
            
            $nextItem = $suffixArr[$nextSuffixIndex];
            while ($j + $nowLength < $lenMap[$i] && $nextItem[1] + $nowLength < $lenMap[$nextItem[0]] && $w[$i][$j + $nowLength] == $w[$nextItem[0]][$nextItem[1] + $nowLength]) { //未到头且相同，则继续尝试比较下一项
                $nowLength++;
            }
            
            $longestCommonPrefixArr[$indexToSuffixArr[$i][$j]] = $nowLength;
        
            //向前移一位，因此当前公共长度-1
            if ($nowLength > 0) {
                $nowLength--;
            }
        }
    }
}
