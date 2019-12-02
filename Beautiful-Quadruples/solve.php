<?php
function beautifulQuadruples($a, $b, $c, $d) {
    $arr = [$a , $b, $c, $d];
    
    // 排序，从小到大
    sort($arr);
    
    // 存放第三个数>=s时，第三个数与第四个数异或结果里，每个数出现的次数
    $flagArr = [];
    
    // 存放第三个数>=s时，第三个数与第四个数异或结果总数
    $numArr = [];
    
    $flagArr[$arr[2] + 1] = [];
    $numArr[$arr[2] + 1] = 0;
    for ($i = $arr[2]; $i >= 1; $i--) {
        // 累加， >= s的结果为 =s的结果 加上 >s的结果
        // 这是 >s的结果，即上一个数对应项
        $flagArr[$i] = $flagArr[$i + 1];
        // 这是 =s 的结果， 遍历第四项，进行计算
        for ($j = $i; $j <= $arr[3]; $j++) {
            $t = $i ^ $j;
            if ( ! isset($flagArr[$i][$t])) {
                $flagArr[$i][$t] = 0;
            }
            $flagArr[$i][$t]++;
        }
        
        // >=s时，第三个数与第四个数异或结果总数 = >s时的结果总数（即上一个结果） + 这一次第四个数能够选择的总数
        $numArr[$i] = $numArr[$i + 1] + ($arr[3] - $i + 1);
    }
    
    $result = 0;
    for ($i = 1; $i <= $arr[0]; $i++) {
        $t1 = $i;
        for ($j = $i; $j <= $arr[1]; $j++) {
            $t2 = $t1 ^ $j;
            // 第二个数取j，前两个数异或结果为t2
            // 累加上（第三个数>=j时的结果总数 - 第三个数>=j时后两数异或结果为 t2的数量）
            $result += $numArr[$j] - ($flagArr[$j][$t2] ?? 0);
        }
    }
    
    return $result;
}
