<?php
function repetitiveSums($n, $k, $sums)
{
    $result = [];
    
    // 从小到大排序
    sort($sums);
    
    // 最小的，只可能是 result[0] * k
    $result[] = $sums[0] / $k;
    
    // 用map计数，用于移除元素
    $numMap = [];
    foreach ($sums as $num) {
        if ( ! isset($numMap[$num])) {
            $numMap[$num] = 1;
        } else {
            $numMap[$num]++;
        }
    }
    
    
    decreaseOne($numMap, $sums[0]);

    // 把以第i项为最大元素的K项和全部去掉，剩下的最小的K项和必定是 result[i+1] + (k-1) * result[0]
    for ($i = 1; $i < $n; $i++) {
        $firstSum = null;
        foreach ($numMap as $sum => $v) {
            $firstSum = $sum;
            break;
        }
        
        $result[$i] = $firstSum - ($k - 1) * $result[0];
        
        // 移除$result[$i]为最大元素的K项和
        recurseRemove($numMap, $result, 1, 0, $result[$i], $k, $i);
    }
    
    return $result;
}

function recurseRemove(&$numMap, &$result, $nowCount, $nowIndex, $nowSum, $k, $maxIndex)
{
    // nowCount表示当前累加了多少项， 显然当nowCount等于k的时候，就是K项和，处理数量减1
    if ($nowCount == $k) {
        decreaseOne($numMap, $nowSum);
        return;
    }
    
    // nowIndex表示当前位于的项索引，只允许选大于等于此index的元素， 可以选择它，然后继续选下一个元素，也可以跳过它（之后就不能再选了）
    recurseRemove($numMap, $result, $nowCount + 1, $nowIndex, $nowSum + $result[$nowIndex], $k, $maxIndex);
    if ($nowIndex < $maxIndex) { // 没有到头，才能跳过
        recurseRemove($numMap, $result, $nowCount, $nowIndex + 1, $nowSum, $k, $maxIndex);
    }
}

function decreaseOne(&$numMap, $key)
{
    // 数量减1，减到0就移除
    $numMap[$key]--;
    if ($numMap[$key] == 0) {
        unset($numMap[$key]);
    }
}

$fptr = fopen(getenv("OUTPUT_PATH"), "w");

$stdin = fopen("php://stdin", "r");

fscanf($stdin, "%d\n", $t);

for ($t_itr = 0; $t_itr < $t; $t_itr++) {
    fscanf($stdin, "%[^\n]", $nm_temp);
    $nm = explode(' ', $nm_temp);

    $n = intval($nm[0]);

    $k = intval($nm[1]);

    fscanf($stdin, "%[^\n]", $x_temp);

    $sums = array_map('intval', preg_split('/ /', $x_temp, -1, PREG_SPLIT_NO_EMPTY));

    $result = repetitiveSums($n, $k, $sums);

    fwrite($fptr, implode(" ", $result) . "\n");
}

fclose($stdin);
fclose($fptr);
