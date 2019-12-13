<?php
function minimumPasses($m, $w, $p, $n) {
    // 每一步，都有买和不买两种情况，比较哪个更优
    $notBuyMinStep = ceil($n / ($m * $w));
    $buyMinStep = 1;
    
    // 第一轮过后
    $candy = $m * $w;
    $nowm = $m;
    $noww = $w;
    
    $speed = $nowm * $noww;
    while ($candy < $n) {
        //如果不买
        $notBuyMinStep = min($notBuyMinStep, $buyMinStep + ceil(($n - $candy) / $speed));
        
        $buyCost = 0;
        // 一个都买不了，等到至少能买一个为止
        if ($candy < $p) {
            $round = ceil(($p - $candy) / $speed);
            $buyMinStep += $round;
            
            $candy += $round * $speed;
        } else { // 能买，尽量让工人和机器数一样大
            
            $canBuyNum = floor($candy / $p);
            
            $diff = abs($nowm - $noww);
            if ($canBuyNum < $diff) { // 没法补到一样的数量，全给数量小的
                if ($nowm < $noww) {
                    $nowm += $canBuyNum;
                } else {
                    $noww += $canBuyNum;
                }
            } else {
                // 补到一样的数量
                if ($nowm < $noww) { 
                    $nowm = $noww;
                } else {
                    $noww = $nowm;
                }
                
                // 多出来的，平均分配
                $plus = $canBuyNum - $diff;
                $half = $plus >> 1;
                $noww += $half;
                $nowm = $noww;
                
                if ($plus & 1) { // 还多了一个，给机器吧
                    $nowm++;
                }
            }
            
            $buyCost = $canBuyNum * $p;
            $speed = $nowm * $noww;
            // 更新蜡烛
            $candy = $candy - $buyCost + $speed;

            $buyMinStep++;
        } 
    }
    
    return min($notBuyMinStep, $buyMinStep);
}
