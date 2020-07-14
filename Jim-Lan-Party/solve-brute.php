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
    $n = count($games);
    
    $result = [];
    for ($i = 1; $i <= $m; $i++) {
        $result[$i] = -1;
    }
    
    // 父节点
    $parent = [];
    for ($i = 1; $i <= $n; $i++) {
        $parent[$i] = $i;
    }
    
    // 组内成员数
    $groupCountArr = [];
    
    // 当前组内成员数
    $nowGroupCountArr = [];
    foreach ($games as $index => $group) {
        if ( ! isset($groupCountArr[$group])) {
            $groupCountArr[$group] = 1;
        } else {
            $groupCountArr[$group]++;
        }
        
        $nowGroupCountArr[$index + 1] = [$group => 1];
    }
    
    // 检查组内成员数为1的情况
    foreach ($groupCountArr as $group => $num) {
        if ($num == 1) {
            $result[$group] = 0;
        }
    }
    
    // 合并组，更新计数，把数量小的往数量大的里合并
    foreach ($wires as $wireIndex => $item) {
        $left = getParent($parent, $item[0]);
        $right = getParent($parent, $item[1]);
        
        if ($left == $right) { // 本来就已经在同一个集合中，啥也不做
            continue;
        }
        
        // left总是小的
        if (count($nowGroupCountArr[$left]) > count($nowGroupCountArr[$right])) {
            $temp = $left;
            $left = $right;
            $right = $temp;
        }
        
        $parent[$left] = $right;
        
        foreach ($nowGroupCountArr[$left] as $group => $num) {
            if ( ! isset($nowGroupCountArr[$right][$group])) {
                $nowGroupCountArr[$right][$group] = $num;
            } else {
                $nowGroupCountArr[$right][$group] += $num;
                if ($nowGroupCountArr[$right][$group] == $groupCountArr[$group]) { // 满员了
                    $result[$group] = $wireIndex + 1;
                }
            }
        }
        
        // 合并后，原有的小的就没用了，空间回收
        unset($nowGroupCountArr[$left]);
    }

    return $result;
}
