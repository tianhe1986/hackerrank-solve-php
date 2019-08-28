<?php
function findConnectedComponents($d) {
    $result = 0;
    $n = count($d);
    
    $connectArr = []; //用于存储当前每个连通图对应的标识位
    //递归处理
    reverse($n - 1, $connectArr, false, $d, $result);
    reverse($n - 1, $connectArr, true, $d, $result);
    
    return $result;
}

function reverse($index, $connectArr, $flag, &$d, &$result)
{
    if ($flag) { // flag表示此项是否加入子集
        $newArr = [$d[$index]];
        foreach ($connectArr as $item) {
            if ($item & $d[$index]) { // 有交集， 合并成同一个连通图
                $newArr[0] |= $item;
            } else { // 没有交集，保持单独的连通图
                $newArr[] = $item;
            }
        }
        
        $connectArr = $newArr;
    }
        
    if ($index == 0) { // 遍历到了最后一个元素
        $temp = 0;
        foreach ($connectArr as $item) {
            $temp |= $item;
        }
        $num = 0; // 计算所有在连通图中的节点数
        while ($temp > 0) {
            if ($temp & 1) {
                $num++;
            }
            $temp = $temp >> 1;
        }
        
        // 对于任何一个连通图，如果其中有 t 和节点， 则当前子集连通图总数减少 (t - 1)
        $result = $result + (64 - $num + count($connectArr));
    } else { // 继续递归遍历
        reverse($index - 1, $connectArr, false, $d, $result);
        reverse($index - 1, $connectArr, true, $d, $result);
    }
}
