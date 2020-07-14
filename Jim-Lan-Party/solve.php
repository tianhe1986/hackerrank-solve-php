<?php
// 获取根节点
function getParent(&$parent, $node)
{
    if ($parent[$node] == $node) {
        return $node;
    } else {
        $temp = getParent($parent, $parent[$node]);
        return $parent[$node] = $temp;
    }
}

function lanParty($games, $wires, $m) {
    $result = [];
    $n = count($games);
    $q = count($wires);
    
    // 记录每个组的成员
    $groupMembers = [];
    foreach ($games as $index => $group) {
        $groupMembers[$group][] = $index + 1;
    }
    
    // 用于二分查找
    $lowArr = [];
    $highArr = [];
    
    for ($i = 1; $i <= $m; $i++) {
        $result[$i] = -1;
        $lowArr[$i] = 0;
        $highArr[$i] = $q;
    }
    
    // q最大为100000， 二分查找不可能超过20次
    for ($time = 20; $time >= 0; $time--) {
        // 父节点
        $parent = [];
        for ($i = 1; $i <= $n; $i++) {
            $parent[$i] = $i;
        }
        
        // 初始化需要二分查找的数组
        $binaryArr = [];
        for ($i = 1; $i <= $m; $i++) {
            if ($lowArr[$i] > $highArr[$i]) {
                continue;
            }
            $mid = ($lowArr[$i] + $highArr[$i]) >> 1;
            // 在第mid根线时，需要判断第i组是否全
            $binaryArr[$mid][] = $i;
        }
        
        if (empty($binaryArr)) { // 不用再找了
            break;
        }
        
        // 遍历Q，每次连接节点之后，进行查询判断
        for ($i = 0; $i <= $q; $i++) {
            // 连接
            if ($i > 0) {
                $u = getParent($parent, $wires[$i - 1][0]);
                $v = getParent($parent, $wires[$i - 1][1]);
                if ($u != $v) {
                    $parent[$u] = $parent[$v];
                }
            }
            
            // 判断对应组是否全，若全了，最大设置为此值，向前查找，否则，向后查找
            if ( ! isset($binaryArr[$i])) {
                continue;
            }
            
            foreach ($binaryArr[$i] as $group) {
                $connect = true;
                $count = count($groupMembers[$group]);
                for ($j = 1; $j < $count; $j++) {
                    // 有相邻两个不在同一组，则直接break
                    if (getParent($parent, $groupMembers[$group][$j - 1]) != getParent($parent, $groupMembers[$group][$j])) {
                        $connect = false;
                        break;
                    }
                }
                
                if ($connect) { // 全了，最大设置为此值，向前查找
                    $result[$group] = $i;
                    $highArr[$group] = $i - 1;
                } else { // 不全，向后查找
                    $lowArr[$group] = $i + 1;
                }
            }
        }
    }
    
    return $result;
}
