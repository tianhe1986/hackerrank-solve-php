<?php
function candlesCounting($k, $candles) {
    $fullColor = pow(2, $k) - 1;
    
    // 找出最大的高度
    $maxHeight = 0;
    foreach ($candles as $candle) {
        if ($candle[0] > $maxHeight) {
            $maxHeight = $candle[0];
        }
    }
    
    // 从前往后遍历， 每次更新的值为 "以当前高度结尾的当前颜色组合的数量"
    $treeArr = [];
    for ($i = 1; $i <= $fullColor; $i++) {
        $treeArr[$i] = new SplFixedArray($maxHeight + 1);
        for ($j = 1; $j <= $maxHeight; $j++) {
            $treeArr[$i][$j] = 0;
        }
    }
    
    // 提前缓存要处理的颜色
    $needColorArr = [];
    $bitColorMap = [];
    for ($i = 0; $i < $k; $i++) {
        $color = 1 << $i;
        $tempArr = [];
        
        for ($j = 1; $j <= $fullColor; $j++) {
            $newColor = $j ^$color;
            if ($newColor > $j || $newColor == 0) {
                continue;
            }
            
            $tempArr[] = [$j, $newColor];
        }
        
        $needColorArr[$i + 1] = $tempArr;
        $bitColorMap[$i + 1] = $color;
    }
    
    foreach ($candles as $candle) {
        $nowHeight = $candle[0];
        $pre = $nowHeight - 1;
        $nowColor = $bitColorMap[$candle[1]];
        
        updateBIT($treeArr[$nowColor], $maxHeight, $nowHeight, getSum($treeArr[$nowColor], $pre) + 1);
        
        foreach ($needColorArr[$candle[1]] as $processItem) {
            updateBIT($treeArr[$processItem[0]], $maxHeight, $nowHeight, getTwoSum($treeArr[$processItem[0]], $treeArr[$processItem[1]],  $pre));
        }
    }
    
    return getSum($treeArr[$fullColor], $maxHeight);
}

// 求和
function getSum(&$tree, $index)
{
    $mod = 1000000007;
    $sum = 0;
    
    while ($index > 0) {
        $sum = ($sum + $tree[$index]);
        
        $index -= ($index & (-$index));
    }
    
    return $sum  % $mod;
}

// 同时求两个树状数组对应项之和
function getTwoSum(&$tree, &$tree2, $index)
{
    $mod = 1000000007;
    $sum = 0;
    
    while ($index > 0) {
        $sum = ($sum + $tree[$index] + $tree2[$index]);
        
        $index -= ($index & (-$index));
    }
    
    return $sum  % $mod;
}

// 更新BIT
function updateBIT(&$tree, $n, $index, $value)
{
    $mod = 1000000007;
    
    while ($index <= $n) {
        $tree[$index] = ($tree[$index] + $value) % $mod;
        $index += ($index & (-$index));
    }
}
