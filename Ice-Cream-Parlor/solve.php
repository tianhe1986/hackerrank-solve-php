<?php
function icecreamParlor($m, $arr) {
    $map = [];
    
    // 统计每个值对应出现的索引
    foreach ($arr as $i => $num) {
        $map[$num][] = $i + 1;
    }
    
    $result = [];
    foreach ($map as $num => $arr) {
        $remain = $m - $num;
        if ($remain == $num) { // 正好等于 m/2， 特殊处理
            if (count($arr) == 2) {
                $result = $arr;
                break;
            }
        } else { // 另一项存在即可
            if (isset($map[$remain])) {
                $result = [$arr[0], $map[$remain][0]];
                break;
            }
        }
    }
    
    // 因为要从小到大输出索引，所以，懒得写判断交换了，直接排序吧
    sort($result);
    return $result;
}
