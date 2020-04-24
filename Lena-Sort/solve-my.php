<?php
function lenaSort($len, $c)
{
    // 缓存，每个长度对应的最小比较数和最大比较数
    static $minArr = [];
    static $maxArr = [];
    
    if (empty($minArr)) {
        $minArr = $maxArr = [0, 0, 1];
        for ($i = 3; $i <= 100000; $i++) {
            $sum = 0;
            if ($i % 2 == 0) {
                $sum = $minArr[$i/2] + $minArr[$i/2 - 1];
            } else {
                $sum = 2 * $minArr[($i-1)/2];
            }
            
            $minArr[$i] = $i - 1 + $sum;
            $maxArr[$i] = $i - 1 + $maxArr[$i - 1];
        }
    }
    
    if ($c < $minArr[$len] || $c > $maxArr[$len]) { // 不在此范围内
        return [-1];
    }
    
    $result = [];
    process($minArr, $maxArr, $c, $len, 1, $len, $result);
    
    return $result;
}

function process(&$minArr, &$maxArr, $compareNum, $len, $startNum, $endNum, &$result)
{
    if ($len == 0) {
        return;
    }
    // 减去本身比较占用的次数
    $compareNum -= $len - 1;
    
    $low = 0;
    $high = ($len % 2 == 0) ? ($len / 2 - 1) : (($len - 1) / 2);
    
    //  优化，如果已经是最大值，直接返回
    if ($compareNum == $maxArr[$len - 1]) {
        for ($i = 0; $i < $len; $i++) {
            $result[] = $startNum + $i;
        }
        return ;
    }
    
    // 二分查找，找到满足条件的组合
    while ($low <= $high) {
        $middle = ($low + $high) >> 1;
        $other = $len - 1 - $middle;
        
        $tempMin = $minArr[$middle] + $minArr[$other];
        $tempMax = $maxArr[$middle] + $maxArr[$other];
        
        if ($compareNum >= $tempMin && $compareNum <= $tempMax) { // 在范围内，再决定实际拆分值
            // 再次二分查找，找左拆分值和右拆分值
            $secondLow = $minArr[$middle];
            $secondHigh = $maxArr[$middle];
            $leftNum = null;
            
            while ($secondLow <= $secondHigh) {
                $leftNum = ($secondLow + $secondHigh) >> 1;
                
                $rightNum = $compareNum - $leftNum;
                if ($rightNum >= $minArr[$other] && $rightNum <= $maxArr[$other]) {
                    break;
                } else if ($rightNum < $minArr[$other]) { // 右值太小了
                    $secondHigh = $leftNum - 1;
                } else {
                    $secondLow = $leftNum + 1;
                }
            }
            
            // 因为每次比较选的都是第一个元素，所以实际上算先根遍历，直接加入结果即可
            $result[] = $startNum + $middle;
            process($minArr, $maxArr, $leftNum, $middle, $startNum, $startNum + $middle - 1, $result);
            process($minArr, $maxArr, $compareNum - $leftNum, $other, $startNum + $middle + 1, $endNum, $result);
            break;
        } else if ($compareNum < $tempMin) { // 比可能的最小值还小，继续向上
            $low = $middle + 1;
        } else { // 比可能的最大值还大，向下
            $high = $middle - 1;
        }
    }
}

$stdin = fopen("php://stdin", "r");

fscanf($stdin, "%d\n", $q);

for ($q_itr = 0; $q_itr < $q; $q_itr++) {
    fscanf($stdin, "%[^\n]", $lc_temp);
    $lc = explode(' ', $lc_temp);

    $l = intval($lc[0]);

    $c = intval($lc[1]);

    // Write Your Code Here
    $result = lenaSort($l, $c);
    echo implode(" ", $result) . "\n";
}

fclose($stdin);