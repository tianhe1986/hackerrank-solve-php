<?php
function ashtonString($string, $k) {
    //构造后缀数组
    $suffixArr = new SplFixedArray(strlen($string));
    buildSuffixArr($suffixArr, $string);

    //用kasai算法，计算后缀数组中相邻两项的最长公共前缀长度
    $longestCommonPrefixArr = new SplFixedArray(strlen($string));
    buildLCPWithKasai($longestCommonPrefixArr, $suffixArr, $string);

    $n = strlen($string);
    
    // 依次计算，找出值
    
    $nowBegin = 0;
    for ($i = 0; $i < $n; $i++) {
        $locationLen = $n - $suffixArr[$i];
        
        // 抛去与前一项的最长公共前缀部分，从前往后依次处理其数量
        for ($j = $nowBegin + 1; $j <= $locationLen; $j++) {
            if ($k <= $j) { // 达到要求的项长度了
                return substr($string, $suffixArr[$i] + $k - 1, 1);
            }
            
            $k -= $j;
        }
        
        $nowBegin = $longestCommonPrefixArr[$i];
    }
}

function buildSuffixArr(&$suffixArr, &$s)
{
    $len = strlen($s);
    //每一位当前排序
    $rankArr = new SplFixedArray($len);
    
    for ($i = 0; $i < $len; $i++) {
        $suffixArr[$i] = $i;
        $rankArr[$i] = ord($s[$i]);
    }
    
    $maxValue = 256;
    // 用于反查 second 值
    $secondValueMap = new SplFixedArray($len);
    $end = $len - 1;
    
    for ($k = 1; $k < $len; $k = $k << 1) {
        
        //使用基数排序
        $secondArr = [];
        $firstArr = [];
        
        $maxAllowIndex = $len - $k - 1;
        foreach ($suffixArr as $i) {
            $t = ($i > $maxAllowIndex) ? -1 : $rankArr[$i + $k];
            $secondArr[$t][] = $i;
            $secondValueMap[$i] = $t;
        }
        
        for ($j = -1; $j <= $maxValue; $j++) {
            if (isset($secondArr[$j])) {
                foreach ($secondArr[$j] as $i) {
                    $firstArr[$rankArr[$i]][] = $i;
                }
            }
        }
        
        // 一次性排序和生成rank
        $nowRank = -1;
        $nowIndex = 0;
        for ($j = 0; $j <= $maxValue; $j++) {
            if (isset($firstArr[$j])) {
                $preValue = null;
                foreach ($firstArr[$j] as $i) {
                    $suffixArr[$nowIndex++] = $i;
                    $rankArr[$i] = ($secondValueMap[$i] === $preValue ? $nowRank : ++$nowRank);
                    $preValue = $secondValueMap[$i];
                }
            }
        }
        
        if ($nowRank == $end) { //已经每一个都有不同的值了
            break;
        }
        $maxValue = $nowRank;
    }
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
    for ($i = 0; $i < $len; $i++) {
        // 从第nowLength一项开始比
        
        // 在当前排列之后的一项
        $nextSuffixIndex = $indexToSuffixArr[$i] + 1;
        //这已经是suffix数组中最后一项了
        if ($nextSuffixIndex >= $len) {
            $nowLength = 0;
            $longestCommonPrefixArr[$indexToSuffixArr[$i]] = $nowLength;
            continue;
        }
        
        $iindex = $i + $nowLength;
        $jindex = $suffixArr[$nextSuffixIndex] + $nowLength;
        while ($iindex < $len && $jindex < $len && $s[$iindex] == $s[$jindex]) { //未到头且相同，则继续尝试比较下一项
            $nowLength++;
            $iindex++;
            $jindex++;
        }
        
        $longestCommonPrefixArr[$indexToSuffixArr[$i]] = $nowLength;
        
        //向前移一位，因此当前公共长度-1
        if ($nowLength > 0) {
            $nowLength--;
        }
    }
}