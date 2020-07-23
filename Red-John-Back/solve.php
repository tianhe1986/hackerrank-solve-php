<?php
function redJohn($n) {
    static $ways = [];
    static $primeNum = [];
    
    if (empty($ways)) {
        for ($i = 0; $i < 4; $i++) {
            $ways[$i] = 1;
        }

        // 动态规划计算填充方式
        $max = dynamic($ways, 40);
        
        // 计算质数
        $primeMap = [2 => true, 3 => true];
        for ($i = 5; $i <= $max; $i+=2) {
            $isPrime = true;
            foreach ($primeMap as $prime => $dummy) {
                if ($i % $prime == 0) { // 被整除了，不是质数
                    $isPrime = false;
                    break;
                }
                
                if ($prime * $prime > $i) { // 不会有更大的整除情况了
                    break;
                }
            }
            
            if ($isPrime) {
                $primeMap[$i] = true;
            }
        }
        
        $primeNum[0] = 0;
        for ($i = 1; $i <= $max; $i++) {
            $primeNum[$i] = isset($primeMap[$i]) ? $primeNum[$i - 1] + 1 : $primeNum[$i - 1];
        }
    }
    
    return $primeNum[$ways[$n]];
}

function dynamic(&$ways, $i)
{
    if (isset($ways[$i])) {
        return $ways[$i];
    }
    
    return $ways[$i] = dynamic($ways, $i - 4) + dynamic($ways, $i - 1);
}
