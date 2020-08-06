<?php
function minimumMstGraph($n, $m, $s)
{
    // 生成树共 n - 1条边， n - 2条都取1，剩下的一条取最大值
    $maxValue = $s - ($n - 2);
    
    // 最大权重的最小值，所有边平均分配
    $minValue = intval(ceil($s / ($n - 1)));
    
    // 剩余要连接的边数
    $left = $m - ($n - 1);
    
    // 如果能够达成 n - 2条边都取1，再好不过
    $roundNum = ($n - 2) * ($n - 3) / 2;
    if ($left <= $roundNum) {
        return $s + $left;
    }
    
    // 最大值的边对应的节点需要往其他节点连接的边数
    $beyondNum = $left - $roundNum;
    
    $result = $s + $roundNum + $beyondNum * $maxValue;
    if ($beyondNum <= ($n - 3) / 2) { // 最大值分下去并没有什么卵用，根本不会减少
        return $result;
    }
    
    // 分一轮(n - 2)次能够减少的权值
    $subNum = $beyondNum * ($n - 2) - $roundNum;
    
    // 总共能分多少次
    $totalRound = $maxValue - $minValue;
    $mod = $totalRound % ($n - 2);
    
    // 先把完整的轮次分下去
    $result -= ($totalRound - $mod) / ($n - 2) * $subNum;
    
    // 剩余的看要不要分
    $t1 = $beyondNum * $mod;
    $t2 = ($n - 3 + $n - 2 - $mod) * $mod / 2;
            
    if ($t1 > $t2) { // 分
        $result -= ($t1 - $t2);
    }
    
    return $result;
}
