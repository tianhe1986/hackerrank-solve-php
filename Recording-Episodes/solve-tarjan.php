<?php
function episodeRecording($episodes) {
    $n = count($episodes);
    
    //建立有向连通图
    $connectMap = [];
    
    for ($i = 0; $i < $n - 1; $i++) {
        for ($j = $i + 1; $j < $n; $j++) {
            //i首播与j首播冲突，则选i首播，就必须选j重播，选j首播，就必须选i重播
            if (cross($episodes[$i][0], $episodes[$i][1], $episodes[$j][0], $episodes[$j][1])) {
                $connectMap[$i * 2][$j * 2 + 1] = true;
                $connectMap[$j * 2][$i * 2 + 1] = true;
            }
            
            //同样的，处理另外三种情况
            if (cross($episodes[$i][0], $episodes[$i][1], $episodes[$j][2], $episodes[$j][3])) {
                $connectMap[$i * 2][$j * 2] = true;
                $connectMap[$j * 2 + 1][$i * 2 + 1] = true;
            }
            
            if (cross($episodes[$i][2], $episodes[$i][3], $episodes[$j][0], $episodes[$j][1])) {
                $connectMap[$i * 2 + 1][$j * 2 + 1] = true;
                $connectMap[$j * 2][$i * 2] = true;
            }
            
            if (cross($episodes[$i][2], $episodes[$i][3], $episodes[$j][2], $episodes[$j][3])) {
                $connectMap[$i * 2 + 1][$j * 2] = true;
                $connectMap[$j * 2 + 1][$i * 2] = true;
            }
        }
    }
    
    //从左往右,寻找2-SAT解
    $maxValue = 1;
    $maxItem = [1, 1];
    for ($i = 0; $i < $n - 1; $i++) {
        for ($j = $i + $maxValue; $j < $n; $j++) {
            if (hasSolve($connectMap, 2 * $i, 2 * $j + 1)) { //找到解则继续
                $maxValue = $j - $i + 1;
                $maxItem = [$i + 1, $j + 1];
            } else {
                break;
            }
        }
    }
    
    return $maxItem;
}

// 时间是否相交
function cross($s1, $e1, $s2, $e2)
{
    return $s1 < $s2 ? $s2 <= $e1 : $s1 <= $e2;
}

function hasSolve(&$connectMap, $l, $r)
{
    //使用Tarjan算法
    
    $indexMap = [];
    $lowLinkMap = [];
    $groupMap = [];
    $index = 0;
    $stack = [];
    $inStackMap = [];
    
    for ($i = $l; $i <= $r; $i++) { //深度优先遍历
        if ( ! isset($indexMap[$i])) {
            strongConnect($indexMap, $lowLinkMap, $stack, $inStackMap, $groupMap, $connectMap, $index, $i, $l, $r);
        }
    }
    
    for ($i = $l; $i <= $r; $i += 2) {
        if ($groupMap[$i] == $groupMap[$i + 1]) { // 2i 和 2i + 1如果在同一个强连通分量，则矛盾
            return false;
        }
    }
    
    return true;
}

function strongConnect(&$indexMap, &$lowLinkMap, &$stack, &$inStackMap, &$groupMap, &$connectMap, &$index, $i, $l, $r)
{
    $indexMap[$i] = $index;
    $lowLinkMap[$i] = $index;
    $index++;
    
    $stack[] = $i;
    $inStackMap[$i] = true;
    
    if (isset($connectMap[$i])) {
        foreach ($connectMap[$i] as $next => $dummy) {
            if ($next >= $l && $next <= $r) {
                if ( ! isset($indexMap[$next])) { //深度优先递归计算low link值
                    strongConnect($indexMap, $lowLinkMap, $stack, $inStackMap, $groupMap, $connectMap, $index, $next, $l, $r);
                    $lowLinkMap[$i] = min($lowLinkMap[$i], $lowLinkMap[$next]);
                } else if (isset($inStackMap[$next])) { //碰到环了
                    $lowLinkMap[$i] = min($lowLinkMap[$i], $indexMap[$next]);
                }
            }
        }
    }
    
    if ($lowLinkMap[$i] == $indexMap[$i]) { //依次弹栈，构成强连通分量
        $w = null;
        while ($w !== $i) {
            $w = array_pop($stack);
            unset($inStackMap[$w]);
            $groupMap[$w] = $i;
        }
    }
}

