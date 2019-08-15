<?php
function permutationEquation($p) {
    $result = []; // 存放结果
    $q = []; // 反映射关系 
    $n = count($p);
    for ($i = 0; $i < $n; $i++) {
        $q[$p[$i]] = $i + 1; // 数组下标从0开始，因此需要加1
    }
    
    for ($i = 1; $i <= $n; $i++) {
        $result[] = $q[$q[$i]];
    }
    
    return $result;
}
