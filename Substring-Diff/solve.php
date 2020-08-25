<?php
function substringDiff($k, $s1, $s2) {
    $result = 0;
    
    solveMax($s1, $s2, $k, $result);
    solveMax($s2, $s1, $k, $result);
    
    return $result;
}

function solveMax(&$s1, &$s2, $maxDiff, &$result)
{
    $n = strlen($s1);
    
    for ($i = 0; $i < $n; $i++) {
        $locationArr = []; // 记录各个不匹配次数对应的最靠前的位置
        $locationArr[0] = $i - 1;
        $nowDiffNum = 0;
        
        for ($j = $i, $k = 0; $j < $n; $j++, $k++) {
            if ($s1[$j] != $s2[$k]) { // 不相等时,增加diff数量
                $nowDiffNum++;
                $locationArr[$nowDiffNum] = $j;
            }
            
            // 以当前位置为结尾, 最多有k个字母不同的子串长度
            // 从头到当前位置，一共也没有k个，则到头
            // 超过了k个，则到localtionArr[nowDiffNum - k]
            $temp = $nowDiffNum <= $maxDiff ? $j - $locationArr[0] : $j - $locationArr[$nowDiffNum - $maxDiff];
            if ($temp > $result) {
                $result = $temp;
            }
        }
    }
}
