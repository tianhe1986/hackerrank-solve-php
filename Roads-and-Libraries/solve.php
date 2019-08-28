<?php
function roadsAndLibraries($n, $clib, $croad, $cities) {
    $result = 0;
    if ($clib <= $croad) { //建图书馆更便宜， 直接建图书馆就好
        return $n * $clib;
    }
    
    //连通性
    $connectMap = [];
    foreach ($cities as $item) {
        $connectMap[$item[0]][$item[1]] = $connectMap[$item[1]][$item[0]] = true;
    }
    
    //是否可达某个图书馆
    $arriveFlag = [];
    
    for ($i = 1; $i <= $n; $i++) {
        if (isset($arriveFlag[$i])) { //已经可达
            continue;
        }
        
        //不可达， 新建一座图书馆， 然后遍历其可达的城市
        $result += $clib;
        $arriveFlag[$i] = true;
        processCity($connectMap, $arriveFlag, $i, $result, $croad);
    }
    
    return $result;
}

function processCity(&$connectMap, &$arriveFlag, $index, &$result, &$croad)
{
    if ( ! isset($connectMap[$index])) {
        return;
    }
    
    foreach ($connectMap[$index] as $next => $dummy) {
        if ( ! isset($arriveFlag[$next])) { // 下一个未与图书馆连通的城市， 修一条路，并继续遍历
            $arriveFlag[$next] = true;
            $result += $croad;
            processCity($connectMap, $arriveFlag, $next, $result, $croad);
        }
    }
}
