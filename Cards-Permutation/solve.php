<?php
function solve($x) {
    // 用于取模的数
    $mod = 1000000007;
    
    // 数列长度
    $n = count($x);
    
    // 用于计算阶乘
    $fac = [];
    $fac[-1] = 0;
    $fac[0] = 1;
    for ($i = 1; $i <= $n; $i++) {
        $fac[$i] = ($fac[$i-1] * $i) % $mod;
    }
    
    // 两个线段树
    // 对应原始值为某个数是否已经出现， 范围查询为某个范围内当前已经出现的数的个数
    $nowExistTree = [];
    buildSegTree($nowExistTree, 1, 0, $n - 1);
    
    // 对应原始值为某个数是否未出现，范围查询为某个范围内未出现的数的个数。
    $notExistTree = [];
    buildSegTree($notExistTree, 1, 0, $n - 1);
    
    // 不知(即输入为0)的元素个数
    $k = 0;
    // 某个数是否出现 
    $existArr = [];
    
    // 处理，将元素-1， 便于计算
    for ($i = 0; $i < $n; $i++) {
        $x[$i]--; // 自减1，便于计算
        if ($x[$i] == -1) {
            $k++;
        } else {
            $existArr[$x[$i]] = true;
        }
    }
    
    // 所有未出现的元素之和
    $allNotExitSum = 0;
    // 更新未出现的数
    for ($i = 0; $i < $n; $i++) {
        if ( ! isset($existArr[$i])) {
            updateSegTree($notExistTree, $i, 1, 1, 0, $n - 1);
            $allNotExitSum = ($allNotExitSum + $i) % $mod;
        }
    }
    
    // 最终结果
    $result = 0;
    
    // 保存未出现数字中比当前每个已出现数字大的个数之和
    $canBigSum = 0;
    
    // 当前-1的个数
    $nowNotExsitNum = 0;
    
    $ck2 = ($k * ($k - 1)/2) % $mod;
    for ($i = 0; $i < $n; $i++) {
        $temp = 0;
        if ($x[$i] == -1) { // 是空白
            // （所有未出现的数之和）*(k-1)！
            $total = ($allNotExitSum * $fac[$k - 1]) % $mod;
            // (未出现数字中比当前每个已出现数字大的个数之和) * (k-1)!
            $notBlank = ($canBigSum * $fac[$k - 1]) % $mod;
            // （前i-1位中-1的个数)* k! / 2， 但是有除法的关系直接算会出错，可能除不尽，因此，还是写成c(k, 2) * (k-1)!的形式
            //$blank = ($nowNotExsitNum * $fac[$k] / 2) % $mod;
            $blank = ((($nowNotExsitNum * $ck2) % $mod) * $fac[$k - 2]) % $mod;
            
            $temp = ($total - $notBlank - $blank + $mod + $mod) % $mod;
            
            // -1的个数加1
            $nowNotExsitNum++;
        } else { // 不是空白
            // 总数， k! * x[i]
            $total = ($x[$i] * $fac[$k]) % $mod;
            // 前i-1位中已经出现的数字里比a[i]小的个数 * k!
            $notBlank = $x[$i] == 0 ? 0 : (querySegTree($nowExistTree, 0, $x[$i] - 1, 1, 0, $n - 1) * $fac[$k]) % $mod;
            // (前i-1位中-1的个数) * （所有未出现的数字中比a[i]小的个数） * (k-1)!
            $blank = $x[$i] == 0 ? 0 : (($nowNotExsitNum * querySegTree($notExistTree, 0, $x[$i] - 1, 1, 0, $n - 1) % $mod) * $fac[$k - 1]) % $mod;
            $temp = ($total - $notBlank - $blank + $mod + $mod) % $mod;
            
            // 当前出现的数字，更新，即更新nowExistTree线段树
            updateSegTree($nowExistTree, $x[$i], 1, 1, 0, $n - 1);
            // 未出现数字中比当前每个已出现数字大的个数， 进行累加
            $canBigSum = ($canBigSum + querySegTree($notExistTree, $x[$i] + 1, $n - 1, 1, 0, $n - 1)) % $mod;
        }
        
        // 乘以公因子 (n - 1 - i) !
        $temp = $temp * $fac[$n - 1 - $i] % $mod;
        $result = ($result + $temp) % $mod;
    }
    
    // 最后再补上 k!
    $result = ($result + $fac[$k]) % $mod;
    
    return $result;
}

// 初始化线段树， 一开始都是0
function buildSegTree(&$segTree, $index, $left, $right)
{
    if ($left == $right) {
        $segTree[$index] = 0;
        return;
    }
    $mid = intval(($left + $right)/2);
    $leftIndex = $index << 1;
    $rightIndex = $leftIndex | 1;
    
    buildSegTree($segTree, $leftIndex, $left, $mid);
    buildSegTree($segTree, $rightIndex, $mid + 1, $right);
    $segTree[$index] = 0;
}

// 线段树更新，只可能是单个更新，因此不需要lazy
function updateSegTree(&$segTree, $originalIndex, $value, $index, $left, $right)
{
    if ($left == $right) {
        $segTree[$index] = $value;
        return;
    }
    
    $mid = intval(($left + $right)/2);
    $leftIndex = $index << 1;
    $rightIndex = $leftIndex | 1;
    
    // 不在左就在右
    if ($originalIndex <= $mid) {
        updateSegTree($segTree, $originalIndex, $value, $leftIndex, $left, $mid);
    } else {
        updateSegTree($segTree, $originalIndex, $value, $rightIndex, $mid + 1, $right);
    }

    $segTree[$index] = $segTree[$leftIndex] + $segTree[$rightIndex];
}

// 线段树范围查询
function querySegTree(&$segTree, $needLeft, $needRight, $index, $left, $right)
{
    if ($needLeft <= $left && $needRight >= $right) {
        return $segTree[$index];
    }
    
    $result = 0;
    
    $mid = intval(($left + $right)/2);
    $leftIndex = $index << 1;
    $rightIndex = $leftIndex | 1;
    
    if ($needLeft <= $mid) { // 查询范围与左段有交集
        $result += querySegTree($segTree, $needLeft, $needRight, $leftIndex, $left, $mid);
    }
    
    if ($needRight > $mid) { // 查询范围与右段有交集
        $result += querySegTree($segTree, $needLeft, $needRight, $rightIndex, $mid + 1, $right);
    }
    
    return $result;
}