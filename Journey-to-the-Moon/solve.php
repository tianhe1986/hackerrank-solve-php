<?php
function journeyToMoon($n, $astronaut) {
    // 每个国家的人数
    $countMap = [];
    
    // 是否已经找到相应的国家
    $groupFlag = [];
    
    // 同一组标识
    $sameGroupMap = [];
    foreach ($astronaut as $item) {
        $sameGroupMap[$item[0]][$item[1]] = $sameGroupMap[$item[1]][$item[0]] = true;
    }
    
    // 国家ID
    $groupIndex = 0;
    for ($i = 0; $i < $n; $i++) {
        if (isset($groupFlag[$i])) {
            continue;
        }
        
        // 递归找出同一国家的宇航员， 返回此国家宇航员总数
        $countMap[$groupIndex++] = processSameGroup($sameGroupMap, $groupFlag, $i);
    }
    
    //所有组合减去同一国家的组合
    $result = $n * ($n - 1) / 2;
    foreach ($countMap as $count) {
        $result -= $count * ($count - 1)/2;
    }
    
    return $result;
}

function processSameGroup(&$sameGroupMap, &$groupFlag, $i)
{
    $result = 1;
    $groupFlag[$i] = true;
    
    if ( ! isset($sameGroupMap[$i])) {
        return $result;
    }
    
    // 继续遍历其他出现在同一组的宇航员
    foreach ($sameGroupMap[$i] as $next => $dummy) {
        if (isset($groupFlag[$next])) {
            continue;
        }
        
        $result += processSameGroup($sameGroupMap, $groupFlag, $next);
    }
    
    return $result;
}
