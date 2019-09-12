<?php

function printShortestPath($n, $iStart, $jStart, $iEnd, $jEnd) {
    
    // 上下移动的格数必须是偶数
    $idiff = $iEnd - $iStart;
    if (abs($idiff) % 2 == 1) {
        echo "Impossible\n";
        return;
    }
    
    $istep = abs($idiff)/2;
    
    //上下移动的步数的奇偶性，必须与左右移动格数的奇偶性相同
    $jdiff = $jEnd - $jStart;
    
    if ($istep % 2 != abs($jdiff) % 2) {
        echo "Impossible\n";
        return;
    }
    
    $jstep = (abs($jdiff) - $istep)/2;
    if ($jstep < 0) {
        $jstep = 0;
    }
    
    $resultArr = [];
    if ($jstep == 0) { // 只需要上下移动就可以
        //左边的步数 left
        //右边的步数 right
        // 有 
        // left + right = istep
        // -left + right = jdiff
        $left = ($istep - $jdiff)/2;
        $right = ($istep + $jdiff)/2;
        if ($idiff < 0) { //上移, 先左上再右上
            for ($i = 1; $i <= $left; $i++) {
                $resultArr[] = 'UL';
            }
            for ($i = 1; $i <= $right; $i++) {
                $resultArr[] = 'UR';
            }
        } else { //下移, 先右下再左下
            for ($i = 1; $i <= $right; $i++) {
                $resultArr[] = 'LR';
            }
            for ($i = 1; $i <= $left; $i++) {
                $resultArr[] = 'LL';
            }
        }
    } else { //需要左右移动
        if ($jdiff < 0) { //左
            if ($idiff <= 0) { //先左上， 再左
                for ($i = 1; $i <= $istep; $i++) {
                    $resultArr[] = 'UL';
                }
                for ($i = 1; $i <= $jstep; $i++) {
                    $resultArr[] = 'L';
                }
            } else { // 先左下，再左
                for ($i = 1; $i <= $istep; $i++) {
                    $resultArr[] = 'LL';
                }
                for ($i = 1; $i <= $jstep; $i++) {
                    $resultArr[] = 'L';
                }
            }
        } else { //右
            if ($idiff <= 0) { //先右上， 再右
                for ($i = 1; $i <= $istep; $i++) {
                    $resultArr[] = 'UR';
                }
                for ($i = 1; $i <= $jstep; $i++) {
                    $resultArr[] = 'R';
                }
            } else { // 先右，再右下
                for ($i = 1; $i <= $jstep; $i++) {
                    $resultArr[] = 'R';
                }
                for ($i = 1; $i <= $istep; $i++) {
                    $resultArr[] = 'LR';
                }
            }
        }
    }
    
    echo ($istep + $jstep)."\n";
    echo implode(' ', $resultArr)."\n";
}
