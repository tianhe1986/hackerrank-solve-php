<?php
function distantPairs($points, $c)
{
    // 数组，格式为[小坐标， 大坐标， 距离]
    $list = [];
    
    foreach ($points as $point) {
        $min = $max = null;
        if ($point[0] < $point[1]) {
            $min = $point[0];
            $max = $point[1];
        } else {
            $max = $point[0];
            $min = $point[1];
        }
        
        $dis = $max - $min;
        $subDis = $c - $dis;
        if ($subDis < $dis) {
            $dis = $subDis;
        }
        
        $list[] = [$min, $max, $dis];
    }
    
    // 按小坐标排序
    usort($list, function($a, $b) {
        $diff = $a[0] - $b[0];
        return $diff != 0 ? $diff : ($a[1] - $b[1]);
    });

    // 二分查找，找到最大的可以达到的距离
    $low = 1;
    $high = $c >> 2;
    
    while (true) {
        if (canReach($list, $low, $c)) {
            $low = $low << 1;
        } else {
            if ($low <= $high) {
                $high = $low - 1;
            }
            $low = $low >> 1;
            break;
        }
    }
    
    while ($low <= $high) {
        $middle = ($low + $high) >> 1;

        if (canReach($list, $middle, $c)) {
            $low = $middle + 1;
        } else {
            $high = $middle - 1;
        }
    }
    
    return $low - 1;
}

function canReach(&$list, $middle, $c)
{
    // 线段树，默认为空，不用初始化
    // 保存的是一段区间内最大的满足条件的小坐标值
    if (0 == $middle) {
        return true;
    }
    $segTree = [];
    $j = 0;
    
    foreach ($list as $item) {
        // 如果自己的距离都达不到要求，就不用再处理了
        if ($item[2] < $middle) {
            continue;
        }
        
        // 由于是按小坐标排序，因此j递增遍历即可
        while ($list[$j][0] <= $item[0] - $middle) {
            // 同样的，如果自己都达不到要求，就不管了
            if ($list[$j][2] >= $middle) {
                // 这个覆盖处理有些巧妙，每次都更新成更大的小坐标值
                updateSegTree($segTree, $list[$j][1], $list[$j][0], 0, $c, 1);
            }
            $j++;
        }

        // 假设当前遍历项小坐标为min, 大坐标为max， 满足条件的大坐标点可能有三种情况
        // 1. 大坐标 <= min - middle，同时考虑到环， 还有 大坐标 >= max + middle - c
        // 2. min + middle <= 大坐标 <= max- middle
        // 3. 大坐标 >= $max + $middle， 同时考虑到环， 还有 大坐标 <= min - middle + c
        
        // 同样，如果有找到满足条件的小坐标，也要满足 小坐标 >= max + middle - c   
        // 由于小坐标总是 <= min - middle的，因此它越大越好，也就是为什么每次都更新成更大的
        
        $need = $item[1] + $middle - $c;
        $searchArr = [
            [$item[1] + $middle - $c, $item[0] - $middle],
            [$item[0] + $middle, $item[1] - $middle],
            [$item[1] + $middle, $item[0] - $middle + $c]
        ];
        
        foreach ($searchArr as $searchItem) {
            if ($searchItem[0] > $searchItem[1] || $searchItem[1] < 0 || $searchItem[0] > $c) {
                continue;
            }
            
            $searchResult = querySegTree($segTree, $searchItem[0], $searchItem[1], 0, $c, 1);
            if (-1 != $searchResult && $searchResult >= $need) {
                return true;
            }
        }
    }
    
    return false;
}

// 线段树更新，这里只有单值更新，简单点处理
function updateSegTree(&$segTree, $originalIndex, $value, $left, $right, $root)
{
    if ($left == $originalIndex && $right == $originalIndex) {
        $segTree[$root] = $value;
        $root = $root >> 1;
        while ($root) {
            if ( ! isset($segTree[$root]) || $segTree[$root] < $value) {
                $segTree[$root] = $value;
                $root = $root >> 1;
            } else {
                break;
            }
        }
        return;
    }
    
    $mid = ($left + $right) >> 1;
    
    // 不在左就在右
    if ($originalIndex <= $mid) {
        updateSegTree($segTree, $originalIndex, $value, $left, $mid, $root << 1);
    } else {
        updateSegTree($segTree, $originalIndex, $value, $mid + 1, $right, ($root << 1) | 1);
    }
}

// 线段树范围查询
function querySegTree(&$segTree, $needLeft, $needRight, $left, $right, $root)
{
    if ($needLeft <= $left && $needRight >= $right) {
        return $segTree[$root] ?? -1;
    }
    
    $result = -1;
    
    $mid = ($left + $right) >> 1;
    $leftRoot = $root << 1;
    $rightRoot = $leftRoot | 1;
    
    if ($needLeft <= $mid && isset($segTree[$leftRoot]) && $segTree[$leftRoot] > $result) { // 查询范围与左段有交集，且存在更大的可能
        $temp = querySegTree($segTree, $needLeft, $needRight, $left, $mid, $leftRoot);
        if ($result < $temp) {
            $result = $temp;
        }
    }
    
    if ($needRight > $mid && isset($segTree[$rightRoot]) && $segTree[$rightRoot] > $result) { // 查询范围与右段有交集，且存在更大的可能
        $temp = querySegTree($segTree, $needLeft, $needRight, $mid + 1, $right, $rightRoot);
        if ($result < $temp) {
            $result = $temp;
        }
    }
    
    return $result;
}