<?php
function kruskals($nodes, $from, $to, $weight) {
    // 用于标记此节点加入了哪个组
    $parentArr = [];
    
    // 用于储存当前边的总长度
    $result = 0;
    
    $n = count($from);
    
    // 将边按权值排序
    $edges = [];
    for ($i = 0; $i < $n; $i++) {
        $edges[] = [$from[$i], $to[$i], $weight[$i]];
    }
    
    usort($edges, function($a, $b){
        $diff = $a[2] - $b[2];
        return $diff != 0 ? $diff : ($a[0] + $a[1] - $b[0] - $b[1]);
    });
    
    for ($i = 0; $i < $n; $i++) {
        $leftGroup = getGroup($parentArr, $edges[$i][0]);
        $rightGroup = getGroup($parentArr, $edges[$i][1]);
        if ($leftGroup == $rightGroup) { // 如果在同一个组，不处理
            continue;
        }
        
        // 加入该边，将两个组合并成一个，并增加总长度
        $parentArr[$rightGroup] = $leftGroup;
        
        $result += $edges[$i][2];
    }
    
    return $result;
}

// 获取所在组id，即连通子图的根节点id
function getGroup(&$parentArr, $index)
{
    return isset($parentArr[$index]) ? getGroup($parentArr, $parentArr[$index]) : $index;
}
