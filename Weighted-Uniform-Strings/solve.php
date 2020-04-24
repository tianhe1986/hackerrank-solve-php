<?php
function weightedUniformStrings($s, $queries) {
    // 记录每个字母出现的最大连续长度
    $maxNumArr = [];
    
    $n = strlen($s);
    $nowLetter = $s[0];
    $nowNum = 1;
    for ($i = 1; $i < $n; $i++) {
        if ($s[$i] == $nowLetter) { // 与当前连续字母相同，长度增加
            $nowNum++;
        } else { // 否则，更新当前连续字母出现的最大连续长度
            if ( ! isset($maxNumArr[$nowLetter]) || $nowNum > $maxNumArr[$nowLetter]) {
                $maxNumArr[$nowLetter] = $nowNum;
            }
            
            $nowLetter = $s[$i];
            $nowNum = 1;
        }
    }
    
    if ( ! isset($maxNumArr[$nowLetter]) || $nowNum > $maxNumArr[$nowLetter]) {
        $maxNumArr[$nowLetter] = $nowNum;
    }
    
    // 储存所有可能出现的权值
    $cache = [];
    
    $start = ord('a') - 1;
    foreach ($maxNumArr as $letter => $value) {
        $weight = ord($letter) - $start;
        $nowWeight = 0;
        for ($i = 1; $i <= $value; $i++) {
            $nowWeight += $weight;
            $cache[$nowWeight] = true;
        }
    }
    
    $result = [];
    foreach ($queries as $query) {
        $result[] = (isset($cache[$query]) ? 'Yes' : 'No');
    }
    
    return $result;
}
