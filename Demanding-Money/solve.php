<?php
function demandingMoney($money, $roads) {
    $n = count($money);
    
    // roads转connectMap， 只存小 -> 大
    $connectMap = [];
    
    foreach ($roads as $road) {
        $l = $road[0];
        $h = $road[1];
        
        if ($l > $h) {
            $h = $road[0];
            $l = $road[1];
        }
        
        $connectMap[$l][$h] = true;
    }
    
    // 标志位，0代表此位可选，1代表此位不可选
    $nowKey = str_repeat('0', $n);
    
    $result = dynamic($money, $connectMap, 0, $nowKey, $n - 1);
    
    return $result;
}

function dynamic(&$money, &$connectMap, $nowIndex, $nowKey, $maxIndex)
{
    static $cache = [];
    if (isset($cache[$nowIndex][$nowKey])) {
        return $cache[$nowIndex][$nowKey];
    }
    
    if ($nowIndex == $maxIndex) { // 最后了， 注意没有矿的房子要特殊处理，可偷时算成2种方案
        $result = $nowKey[0] == '0' ? [$money[$nowIndex], $money[$nowIndex] == 0 ? 2 : 1] : [0, 1];
    } else {
        $newKey = substr($nowKey, 1);
        
        // 此位能选
        if ($nowKey[0] == '0') {
            if ( ! isset($connectMap[$nowIndex + 1]) && $money[$nowIndex] > 0) { // 如果此位对后面其他位不会造成影响,且更大，那肯定选啊
                $result = dynamic($money, $connectMap, $nowIndex + 1, $newKey, $maxIndex);
                $result[0] += $money[$nowIndex];
            } else { // 此位不选， 和此位选，两者比较
                $result = dynamic($money, $connectMap, $nowIndex + 1, $newKey, $maxIndex);
            
                if (isset($connectMap[$nowIndex + 1])) {
                    foreach ($connectMap[$nowIndex + 1] as $nextIndex => $dummy) {
                        $newKey[$nextIndex - $nowIndex - 2] = '1';
                    }
                }

                $temp = dynamic($money, $connectMap, $nowIndex + 1, $newKey, $maxIndex);
                $temp[0] += $money[$nowIndex];

                if ($temp[0] > $result[0]) {
                    $result = $temp;
                } else if ($temp[0] == $result[0]) {
                    $result[1] += $temp[1];
                }
            }
        } else { // 此位没法选
            $result = dynamic($money, $connectMap, $nowIndex + 1, $newKey, $maxIndex);
        }
    }
    
    return $cache[$nowIndex][$nowKey] = $result;
}