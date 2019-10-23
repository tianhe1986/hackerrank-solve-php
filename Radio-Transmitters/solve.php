<?php
function hackerlandRadioTransmitters($x, $k) {
    //总数
    $n = count($x);
    
    //排序
    sort($x);
    
    //建造总数量
    $result = 0;
    
    // 当前未覆盖的首个房子index
    $start = 0;
    
    while ($start < $n) {
        $now = $start + 1;
        while ($now < $n && $x[$now] - $x[$start] <= $k) { //now为第一个与start距离超过 k的房子
            $now++;
        }
        //则 now - 1为最远的能覆盖start的房子，建造
        $result++;
        
        // 第一个超出now - 1覆盖范围的房子，作为新的start
        $start = $now;
        while ($start < $n && $x[$start] - $x[$now - 1] <= $k) {
            $start++;
        }
    }
    
    return $result;
}
