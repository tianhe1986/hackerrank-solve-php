<?php
function flippingMatrix($matrix) {
    $maxIndex = count($matrix) - 1;
    $n = count($matrix) / 2;
    
    $result = 0;
    
    for ($i = 0; $i < $n; $i++) {
        for ($j = 0; $j < $n; $j++) {
            
            // 对称的四个位置，取最大值即可
            $max = 0;
            
            $arr = [
                [$i, $j],
                [$i, $maxIndex - $j],
                [$maxIndex - $i, $j],
                [$maxIndex - $i, $maxIndex - $j],
            ];
            
            foreach ($arr as $item) {
                if ($matrix[$item[0]][$item[1]] > $max) {
                    $max = $matrix[$item[0]][$item[1]];
                }
            }
            
            $result += $max;
        }
    }
    
    return $result;
}