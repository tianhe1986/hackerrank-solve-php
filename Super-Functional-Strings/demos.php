<?php
function buildSuffixArr(&$suffixArr, &$s)
{
    $len = strlen($s);
    //每一位当前排序
    $rankArr = new SplFixedArray($len);
    //用于临时储存当前排序
    $tempRankArr = new SplFixedArray($len);
    
    // 初始化当前排序和后缀数组
    for ($i = 0; $i < $len; $i++) {
        $suffixArr[$i] = $i;
        $rankArr[$i] = ord($s[$i]) - 96;
    }
    
    
    for ($k = 1; $k <= $len; $k = $k << 1) {
        //使用快速排序
        
        usort($suffixArr, function($a, $b) use (&$rankArr, $k, $len) {
            if ($rankArr[$a] != $rankArr[$b]) { // 比较前k个字符
                return $rankArr[$a] - $rankArr[$b];
            }
            
            // 比较后k个字符
            $t1 = ($a + $k >= $len) ? -1 : $rankArr[$a + $k];
            $t2 = ($b + $k >= $len) ? -1 : $rankArr[$b + $k];
            
            return $t1 - $t2;
        });
        
        //使用基数排序
        /*
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
        */
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

// 判断两项前2k个字符是否相等
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

$suffixArr = [];
$s= 'nlkhyohnonbbbbb';

buildSuffixArr($suffixArr, $s);