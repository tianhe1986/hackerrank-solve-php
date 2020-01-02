<?php
function tripartiteMatching($n, $g1, $g2, $g3)
{
    $result = 0;
    
    //  第二张和第三张的连通图
    $g2Map = [];
    foreach ($g2 as $item) {
        $g2Map[$item[0]][$item[1]] = $g2Map[$item[1]][$item[0]] = true;
    }
    
    $g3Map = [];
    foreach ($g3 as $item) {
        $g3Map[$item[0]][$item[1]] = $g3Map[$item[1]][$item[0]] = true;
    }

    foreach ($g1 as $item) { // 对g1中的每条边对应的顶点a, b，进行查找，看满足g2中有 a->k，且g3中有 k->b的k有多少个，同样的也查找b, a
        $result += countNum($item[1], $item[0], $g2Map, $g3Map);
        $result += countNum($item[0], $item[1], $g2Map, $g3Map);
    }
    
    return $result;
}

function countNum($a, $b, &$g2Map, &$g3Map)
{
    // 对于节点k， g2中有 a->k， 且g3中有 k->b，则满足条件
    $result = 0;
    
    if ( ! isset($g2Map[$a])) {
        return 0;
    }
    
    foreach ($g2Map[$a] as $k => $dummy) {
        if (isset($g3Map[$k][$b])) {
            $result++;
        }
    }
    
    return $result;
}
