<?php
function bendersPlay($n, $paths, $query) {
    static $nimMap = [];
    
    if (empty($nimMap)) {
        $connectMap = [];
        
        foreach ($paths as $path) {
            $connectMap[$path[0]][$path[1]] = true;
        }
        
        // 求nim值
        for ($i = 1; $i <= $n; $i++) {
            processNim($nimMap, $connectMap, $i);
        }
    }
    
    $value = 0;
    
    foreach ($query as $item) {
        $value ^= $nimMap[$item];
    }
    
    return $value > 0 ? 'Bumi' : 'Iroh';
}

function processNim(&$nimMap, &$connectMap, $i)
{
    if (isset($nimMap[$i])) {
        return $nimMap[$i];
    }
    
    if ( ! isset($connectMap[$i])) {
        return $nimMap[$i] = 0;
    }
    
    // mex - Minimum excludant
    $flagMap = [];
    foreach ($connectMap[$i] as $j => $dummy) {
        $temp = processNim($nimMap, $connectMap, $j);
        $flagMap[$temp] = true;
    }
    
    
    // Mex值最大为total，因为一共就total个数
    $total = count($connectMap[$i]);
    for ($j = 0; $j <= $total; $j++) {
        if ( ! isset($flagMap[$j])) {
            return $nimMap[$i] = $j;
        }
    }
}
