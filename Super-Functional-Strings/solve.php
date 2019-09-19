<?php

// 缓存各个长度的计算结果
$globalMultipleMap = new SplFixedArray(100001);
for ($i = 1; $i <= 100000; $i++) {
    $temp = 1;
    $globalMultipleMap[$i] = new SplFixedArray(27);
    for ($j = 1; $j <= 26; $j++) {
        $temp = ($temp * $i) % 1000000007;
        $globalMultipleMap[$i][$j] = $temp;
    }
}

for ($j = 1; $j <= 26; $j++) {
    $globalMultipleMap[0][$j] = 0;
    $temp = $globalMultipleMap[1][$j];
    for ($i = 2; $i <= 100000; $i++) {
        $globalMultipleMap[$i][$j] = ($temp + $globalMultipleMap[$i][$j]) % 1000000007;
        $temp = $globalMultipleMap[$i][$j];
    }
}

function superFunctionalStrings(&$s) {
    global $globalMultipleMap;
    
    $len = strlen($s);
    
    //计算每个位置及之后， 每个字母第一次出现的位置。
    $letterPlaceArr = [];
    $letterPlaceArr[$len - 1][ord($s[$len - 1]) - 96] = $len - 1;
    for ($i = $len - 2; $i >= 0; $i--) {
        $letterPlaceArr[$i] = $letterPlaceArr[$i + 1];
        $letterPlaceArr[$i][ord($s[$i]) - 96] = $i;
        asort($letterPlaceArr[$i]);
    }
    
    //构造后缀数组
    $suffixArr = [];
    buildSuffixArr($suffixArr, $s);
    
    //var_dump($suffixArr);exit;
    //
    //用kasai算法，计算后缀数组中相邻两项的最长公共前缀长度
    $longestCommonPrefixArr = new SplFixedArray($len);
    buildLCPWithKasai($longestCommonPrefixArr, $suffixArr, $s);
    
    //遍历后缀数组，求最终结果
    $result = 0;
    for ($i = 0; $i < $len; $i++) {
        //与前一项的最长公共前缀长度
        $nowLen = $i == 0 ? 0 : $longestCommonPrefixArr[$i - 1];
        
        //当前项的起始index
        $startIndex = $suffixArr[$i];
        
        //从此起始index开始，各字母第一次出现的位置
        $tempArr = array_values($letterPlaceArr[$startIndex]);
        //补上最后一项，用于相减
        $tempArr[] = $len;
        
        for ($j  = 1, $tempLen = count($tempArr); $j < $tempLen; $j++) {
            if ($tempArr[$j] - $startIndex <= $nowLen) { //小于最长公共前缀长度，不需计算
                continue;
            }
            
            //使用两项相减，计算出需要的累加和
            $diff = $globalMultipleMap[$tempArr[$j] - $startIndex][$j] - $globalMultipleMap[max($tempArr[$j - 1] - $startIndex, $nowLen)][$j];
            if ($diff < 0) {
                $diff += 1000000007;
            }
            $result = ($result + $diff) % 1000000007;
        }
    }
    
    return $result;
}

function buildSuffixArr(&$suffixArr, &$s)
{
    $len = strlen($s);
    //每一位当前排序
    $rankArr = new SplFixedArray($len);
    //用于临时储存当前排序
    $tempRankArr = new SplFixedArray($len);
    
    for ($i = 0; $i < $len; $i++) {
        $suffixArr[$i] = $i;
        $rankArr[$i] = ord($s[$i]) - 96;
    }
    
    
    for ($k = 1; $k <= $len; $k = $k << 1) {
        //使用快速排序
        /*
        usort($suffixArr, function($a, $b) use (&$rankArr, $k, $len) {
            if ($rankArr[$a] != $rankArr[$b]) {
                return $rankArr[$a] - $rankArr[$b];
            }
            
            $t1 = ($a + $k >= $len) ? -1 : $rankArr[$a + $k];
            $t2 = ($b + $k >= $len) ? -1 : $rankArr[$b + $k];
            
            return $t1 - $t2;
        });
        */
        //使用基数排序
        
        $secondArr = [];
        $firstArr = [];
        for ($i = 0; $i < $len; $i++) {
            $t = ($suffixArr[$i] + $k >= $len) ? -1 : $rankArr[$suffixArr[$i] + $k];
            $secondArr[$t][] = $i;
        }
        
        $maxValue = max($len, 27);
        for ($j = -1; $j < $maxValue; $j++) {
            if (isset($secondArr[$j])) {
                foreach ($secondArr[$j] as $i) {
                    $firstArr[$rankArr[$suffixArr[$i]]][] = $suffixArr[$i];
                }
            }
        }
        
        $nowIndex = 0;
        for ($j = 0; $j < $maxValue; $j++) {
            if (isset($firstArr[$j])) {
                foreach ($firstArr[$j] as $i) {
                    $suffixArr[$nowIndex++] = $i;
                }
            }
        }
        
        //重新生成rank
        $nowRank = 0;
        $tempRankArr[$suffixArr[0]] = $nowRank;
        for ($i = 1; $i < $len; $i++) {
            //与前一个不同，则nowRank++
            if ( ! isSame($rankArr, $suffixArr, $i, $k, $len)) {
                $nowRank++;
            }
            $tempRankArr[$suffixArr[$i]] = $nowRank;
        }
        
        for ($i = 0; $i < $len; $i++) {
            $rankArr[$i] = $tempRankArr[$i];
        }
        
        if ($nowRank == $len - 1) { //已经每一个都有不同的值了
            break;
        }
    }
}

function isSame(&$rankArr, &$suffixArr, $i, $k, $len)
{
    $a = $suffixArr[$i - 1];
    $b = $suffixArr[$i];
    if ($rankArr[$a] != $rankArr[$b]) {
        return false;
    }
            
    $t1 = ($a + $k >= $len) ? -1 : $rankArr[$a + $k];
    $t2 = ($b + $k >= $len) ? -1 : $rankArr[$b + $k];

    return $t1 == $t2;
}

function buildLCPWithKasai(&$longestCommonPrefixArr, &$suffixArr, &$s)
{
    $len = strlen($s);
    
    //反向数组，用于查找下一项要处理的
    $indexToSuffixArr = new SplFixedArray($len);
    for ($i = 0; $i < $len; $i++) {
        $indexToSuffixArr[$suffixArr[$i]] = $i;
    }
    
    //当前的公共长度
    $nowLength = 0;
    for ($i = 0; $i <= $len - 1; $i++) {
        // 从第nowLength一项开始比
        
        // 在当前排列之后的一项
        $nextSuffixIndex = $indexToSuffixArr[$i] + 1;
        //这已经是suffix数组中最后一项了
        if ($nextSuffixIndex >= $len) {
            $nowLength = 0;
            continue;
        }
        $j = $suffixArr[$nextSuffixIndex];
        
        while ($i + $nowLength < $len && $j + $nowLength < $len && $s[$i + $nowLength] == $s[$j + $nowLength]) { //未到头且相同，则继续尝试比较下一项
            $nowLength++;
        }
        
        $longestCommonPrefixArr[$indexToSuffixArr[$i]] = $nowLength;
        
        //向前移一位，因此当前公共长度-1
        if ($nowLength > 0) {
            $nowLength--;
        }
    }
}