<?php
function bytelandianTours($n, $roads) {
    // 全局通用fac数组
    static $facArr = [];
    static $mod = 1000000007;
    
    if (empty($facArr)) {
        $facArr[0] = 1;
        for ($i = 1; $i <= 10000; $i++) {
            $facArr[$i] = ($facArr[$i - 1] * $i) % $mod;
        }
    }
    
    // 连通图
    $connectArr = [];
    foreach ($roads as $road) {
        $connectArr[$road[0]][] = $road[1];
        $connectArr[$road[1]][] = $road[0];
    }
    
    // 全局非叶节点数，用来判定是否是星形
    $globalNonLeaf = 0;
    
    // 最终结果
    $result = 1;
    // 如果只有一个非叶节点， 则， (n - 1)!
    // 否则，2 * 每个非叶节点连接的叶节点数的阶乘
    // 即，假设有5个非叶节点， 连接的叶节点数分别是 1, 2, 3, 4, 5, 则最后是 2 * 1! * 2! * 3! * 4! * 5!
    for ($i = 0; $i < $n; $i++) {
        if (count($connectArr[$i]) > 1) { // 非叶节点，才处理
            $globalNonLeaf++;
            
            $leaf = 0;
            $nonLeaf = 0;
            
            foreach ($connectArr[$i] as $j) {
                if (count($connectArr[$j]) > 1) {
                    $nonLeaf++;
                } else {
                    $leaf++;
                }
            }
            
            // 如果一个节点连接了3个或更多非叶节点，则没有回路
            if ($nonLeaf > 2) {
                return 0;
            }
            
            // 乘上叶节点数的阶乘
            $result = ($result * $facArr[$leaf]) % $mod;
        }
    }
    
    return $globalNonLeaf == 1 ? $result : ($result * 2) % $mod; 
}