<?php
function gridWalking($m, $x, $D) {
    // 用于处理组合值
    static $c = [];
    
    $mod = 1000000007;
    
    if (empty($c)) {
        for ($i = 1; $i <= 300; $i++) {
            $c[$i][0] = $c[$i][$i] = 1;
            
            // 组合公式C(n, i) = C(n - 1, i) + C(n - 1, i - 1)
            for ($j = 1; $j < $i; $j++) {
                $c[$i][$j] = ($c[$i - 1][$j] + $c[$i - 1][$j - 1]) % $mod;
            }
        }
    }

    // 动态规划，计算每个维度走固定步数， 能够有多少种走法
    $n = count($x);
    $singleStepArr = [];
    for ($i = 0; $i < $n; $i++) {
        $dp = [];
        for ($j = 0; $j <= $m; $j++) {
            $singleStepArr[$i][$j] = getSingleStep($dp, $j, $x[$i], $D[$i]);
        }
    }
    
    // 动态规划，从第i维开始，可以选择此维及后面的维度移动，一共j步，共有多少种
    $cache = [];
    return getMoveStep($cache, $c, $singleStepArr, 0, $m, $n);
}

function getMoveStep(&$cache, &$c, &$singleStepArr, $nowIndex, $totalStep, $n)
{
    if (isset($cache[$nowIndex][$totalStep])) {
        return $cache[$nowIndex][$totalStep];
    }
    
    if ($nowIndex == $n - 1) {
        return $cache[$nowIndex][$totalStep] = $singleStepArr[$nowIndex][$totalStep];
    }

    if ($totalStep == 0) {
        return $cache[$nowIndex][$totalStep] = 1;
    }
    
    $result = 0;
    $mod = 1000000007;
    
    // 该维分别取0, 1, ... $totalStep步， 再继续遍历下一维
    for ($i = 0; $i <= $totalStep; $i++) {
        $result = ($result + ($c[$totalStep][$i] * $singleStepArr[$nowIndex][$i] % $mod) * getMoveStep($cache, $c, $singleStepArr, $nowIndex + 1, $totalStep - $i, $n) % $mod) % $mod;
    }
    
    return $cache[$nowIndex][$totalStep] = $result;
}

function getSingleStep(&$dp, $totalStep, $nowLoc, $maxLoc)
{
    if (isset($dp[$totalStep][$nowLoc])) {
        return $dp[$totalStep][$nowLoc];
    }
    
    if ($totalStep == 0) {
        return $dp[$totalStep][$nowLoc] = 1;
    }
    
    $result = 0;
    $mod = 1000000007;
    if ($nowLoc > 1) { // 可以往低处走
        $result = ($result + getSingleStep($dp, $totalStep - 1, $nowLoc - 1, $maxLoc)) % $mod;
    }
    
    if ($nowLoc < $maxLoc) { // 可以往高处走
        $result = ($result + getSingleStep($dp, $totalStep - 1, $nowLoc + 1, $maxLoc)) % $mod;
    }
    
    return $dp[$totalStep][$nowLoc] = $result;
}