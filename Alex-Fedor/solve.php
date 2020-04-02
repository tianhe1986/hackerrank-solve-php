<?php
function alexFedor($graph_nodes, $graph_from, $graph_to, $graph_weight) {
    bcscale(100);
    // 顶点数量
    $n = $graph_nodes;

    // 边数量
    $m = count($graph_from);
    
    // 边转换成连通图
    $connectMap = [];
    
    // 边列表
    $edges = [];
    
    for ($i = 0; $i < $m; $i++) {
        $connectMap[$graph_from[$i] - 1][$graph_to[$i] - 1][] = [$i, $graph_weight[$i]];
        $connectMap[$graph_to[$i] - 1][$graph_from[$i] - 1][] = [$i, $graph_weight[$i]];
        $edges[] = [$i, $graph_from[$i] - 1, $graph_to[$i] - 1, $graph_weight[$i]];
    }
    
    // 边按权值排序
    usort($edges, function($a, $b){
        return $a[3] - $b[3];
    });
    
    // 根据连通图，计算对应的生成树数量
    $total = calcuDetByConnectMap($connectMap, $n);
    
    // 得到一颗最小生成树对应的边id
    $edgeMap = getMstEdgeMap($edges);
    
    // 拷贝一份连通图来处理
    $newConnectMap = $connectMap;
    
    // 根据最小生成树，调整边
    justEdge($newConnectMap, $connectMap, $edgeMap);
    
    // 新连通图的生成树数量，就是原图的最小生成树数量
    $mstNum = calcuDetByConnectMap($newConnectMap, $n);

    // 求最大公约数，约分
    $gcd = getGcd($total, $mstNum);
    
    return ($mstNum/$gcd) . "/" . ($total/$gcd);
}

function justEdge(&$newConnectMap, &$connectMap, &$edgeMap)
{
    // 祖先数组
    $ancestorArr = [-1 => [-1, -1, 0]];
    
    process($newConnectMap, $connectMap, $edgeMap, $ancestorArr, 0, -1, -1);
}

function process(&$newConnectMap, &$connectMap, &$edgeMap, &$ancestorArr, $nowNode, $parentNode, $ancestorIndex)
{
    foreach ($connectMap[$nowNode] as $nextNode => $edges) {
        if ($nextNode == $parentNode) {
            continue;
        }
        
        foreach ($edges as $edgeItem) {
            if ( ! isset($edgeMap[$edgeItem[0]])) {
                continue;
            }
            
            // 更新ancestor数组
            $newAncestorIndex = binarySearch($ancestorArr, $edgeItem[1], $ancestorIndex, false) + 1;
            
            $oldAncestorItem = isset($ancestorArr[$newAncestorIndex]) ? $ancestorArr[$newAncestorIndex] : null;
            $ancestorArr[$newAncestorIndex] = [$edgeItem[1], $nowNode, $nextNode];
            
            // 先遍历子节点
            process($newConnectMap, $connectMap, $edgeMap, $ancestorArr, $nextNode, $nowNode, $newAncestorIndex);
            
            // nextNode上所有边
            foreach ($newConnectMap[$nextNode] as $processNode => $processEdges) {
                foreach ($processEdges as $k => $newEdgeItem) {
                    if ($newEdgeItem[0] == $edgeItem[0]) { // 就是当前遍历的边，不处理
                        continue;
                    }
                    
                    // 找到第一个大于等于此边权值的边
                    $searchIndex = binarySearch($ancestorArr, $newEdgeItem[1], $newAncestorIndex);
                    
                    // 如果就是最后一项，不作调整
                    if ($searchIndex == $newAncestorIndex) {
                        continue;
                    }
                    
                    // 否则，将此边链接到对应的节点上
                    $newConnectMap[$ancestorArr[$searchIndex][2]][$processNode][] = $newEdgeItem;
                    $newConnectMap[$processNode][$ancestorArr[$searchIndex][2]][] = $newEdgeItem;
                    unset($newConnectMap[$nextNode][$processNode][$k]);
                    unset($newConnectMap[$processNode][$nextNode][$k]);
                }
            }
            
            // 还原ancestor数组
            $ancestorArr[$newAncestorIndex] = $oldAncestorItem;
        }
    }
}

function binarySearch(&$ancestorArr, $value, $high, $includeEqual = true)
{
    // 数组为从大到小排列，返回大于value的第一个index，没有则返回-1
    if ($high < 0 || $ancestorArr[0] < $value) {
        return -1;
    }
    
    $low = 0;
    while ($low <= $high) {
        $middle = ($low + $high) >> 1;
        $compareValue = $ancestorArr[$middle][0];
        if ($compareValue == $value) {
            return $includeEqual ? $middle : $middle - 1;
        } else if ($compareValue > $value) {
            $low = $middle + 1;
        } else {
            $high = $middle - 1;
        }
    }
    
    return $high;
}

// 求最大公约数
function getGcd($a, $b)
{
    if ($a < $b) {
        $temp = $a;
        $a = $b;
        $b = $temp;
    }
    
    if ($b == 0) {
        return $a;
    }
    
    $mod = $a % $b;
    while ($mod != 0) {
        $a = $b;
        $b = $mod;
        $mod = $a % $b;
    }
    
    return $b;
}

// Kruskal算法求最小生成树
function getMstEdgeMap(&$edges)
{
    $edgeMap = [];
    
    // 用于获取节点的根
    $parentMap = [];
    
    foreach ($edges as $edge) {
        $parent1 = getParent($parentMap, $edge[1]);
        $parent2 = getParent($parentMap, $edge[2]);
        if ($parent1 != $parent2) {
            $parentMap[$parent2] = $parent1;
            $edgeMap[$edge[0]] = true;
        }
    }
    
    return $edgeMap;
}

function getParent(&$parentMap, $node)
{
    return isset($parentMap[$node]) ? getParent($parentMap, $parentMap[$node]) : $node;
}

function calcuDetByConnectMap(&$connectMap, $n)
{
    $matrix = [];
    for ($i = 0; $i < $n; $i++) {
        for ($j = 0; $j < $n; $j++) {
            $matrix[$i][$j] = 0;
        }
    }
    
    // 顶点的度数记录数组
    $dgreeCountArr = array_fill(0, $n, 0);
    // 是对称的，因此只用处理下三角即可
    for ($i = 0; $i < $n; $i++) {
        for ($j = $i + 1; $j < $n; $j++) {
            $dgree = isset($connectMap[$i][$j]) ? count($connectMap[$i][$j]) : 0;
            $matrix[$i][$j] = $matrix[$j][$i] = -$dgree;
            $dgreeCountArr[$i] += $dgree;
            $dgreeCountArr[$j] += $dgree;
        }
    }
    
    for ($i = 0; $i < $n; $i++) {
        $matrix[$i][$i]= $dgreeCountArr[$i];
    }

    // 根据Kirchhoff定理，计算去掉一行和一列的行列式，那就，去掉最后一行和最后一列好了
    return getDet($matrix, $n - 1);
}

// 计算行列式
function getDet($matrix, $n)
{
    // 这里对角线上元素肯定不为0，就不用交换了

    for ($i = 0; $i < $n - 1; $i++) {
        for ($j = $n - 1; $j > $i; $j--) {
            // 想办法将matrix[j][i]变成0
            if (bccomp($matrix[$j][$i], 0) == 0) { // 已经是0了， 不需要处理
                continue;
            }
            $div = bcdiv($matrix[$j][$i], $matrix[$i][$i]);
            
            for ($k = $i + 1; $k < $n; $k++) {
                $matrix[$j][$k] = bcsub($matrix[$j][$k], bcmul($matrix[$i][$k], $div));
            }
            
            $matrix[$j][$i] = 0;
        }
    }

    $result = 1;
    
    for ($i = 0; $i < $n; $i++) {
        $result = bcmul($result, $matrix[$i][$i]);
    }

    return bcround($result, 0);
}

function bcround($number, $precision = 0)
{
    if (strpos($number, '.') !== false) {
        if ($number[0] != '-') {
            return bcadd($number, '0.' . str_repeat('0', $precision) . '5', $precision);
        }
        return bcsub($number, '0.' . str_repeat('0', $precision) . '5', $precision);
    }
    return $number;
}