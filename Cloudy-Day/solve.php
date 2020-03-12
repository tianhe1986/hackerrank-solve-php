<?php
function maximumPeople($p, $x, $y, $r) {
    $townNum = count($p);
    $cloudNum = count($y);
    
    // 坐标和人气map
    $posMap = [];
    
    for ($i = 0; $i < $townNum; $i++) {
        // 如果多个镇子坐标相同，可以合并
        if ( ! isset($posMap[$x[$i]])) {
            $posMap[$x[$i]] = 0;
        }
        
        $posMap[$x[$i]] += $p[$i];
    }
    
    // 将镇按坐标排序
    ksort($posMap, SORT_NUMERIC);
    
    // 真正需要处理的town列表
    $townArr = [];
    foreach ($posMap as $k => $v) {
        $townArr[] = [$k, $v];
    }
    $townNum = count($townArr);
    
    // 遍历云，查找起止点，并处理被云覆盖的次数
    $beginMap = [];
    $endMap = [];
    for ($i = 0; $i < $cloudNum; $i++) {
        $beginIndex = bSearch($townArr, $y[$i] - $r[$i], $townNum, true);
        $endIndex = bSearch($townArr, $y[$i] + $r[$i], $townNum, false) + 1; // 注意，要 +1 才是真正已经离开的坐标

        // 将云覆盖和离开的事件绑定在小镇上
        $beginMap[$beginIndex][] = $i;
        $endMap[$endIndex][] = $i;
    }
    
    // 未被云覆盖的总人数
    $result = 0;
    
    // 移除一朵云能够增加的最大值
    $maxRemoveAddNum = 0;
    $cloudAddNum = array_fill(0, $cloudNum, 0);
    
    // 当前覆盖的云
    $nowCloudMap = [];
    
    // 扫描小镇
    for ($i = 0; $i < $townNum; $i++) {
        // 开始被某些云覆盖
        if (isset($beginMap[$i])) {
            foreach ($beginMap[$i] as $cloud) {
                $nowCloudMap[$cloud] = true;
            }
        }
        
        // 离开了某些云的覆盖范围
        if (isset($endMap[$i])) {
            foreach ($endMap[$i] as $cloud) {
                unset($nowCloudMap[$cloud]);
            }
        }
        
        if (empty($nowCloudMap)) { // 没有被云覆盖
            $result += $townArr[$i][1];
        } else if (count($nowCloudMap) == 1) { // 被一朵云覆盖
            $cloud = array_keys($nowCloudMap)[0];
            $cloudAddNum[$cloud] += $townArr[$i][1];
            if ($cloudAddNum[$cloud] > $maxRemoveAddNum) {
                $maxRemoveAddNum = $cloudAddNum[$cloud];
            }
        }
    }
    
    return $result + $maxRemoveAddNum;
}

function bSearch(&$arr, $value, $total, $isGt)
{
    $low = 0;
    $high = $total - 1;
    
    while ($low <= $high) {
        $middle = intval(($low + $high)/2);
        if ($arr[$middle][0] == $value) {
            return $middle;
        } else if ($arr[$middle][0] < $value) {
            $low = $middle + 1;
        } else {
            $high = $middle - 1;
        }
    }
    
    return $isGt ? $low : $high;
}
