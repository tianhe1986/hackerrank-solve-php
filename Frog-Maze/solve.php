<?php
function escapeProbability($n, $m, $matrix, $tunnelArr)
{
    // 将隧道转换成map
    $tunnelMap = [];
    foreach ($tunnelArr as $tunnelItem) {
        $indexa = ($tunnelItem[0] - 1) * $m + $tunnelItem[1];
        $indexb = ($tunnelItem[2] - 1) * $m + $tunnelItem[3];
        $tunnelMap[$indexa] = $indexb;
        $tunnelMap[$indexb] = $indexa;
    }
    
    // 状态集，每个状态值为 列index + (行index - 1) * 总列数
    
    // 遍历每个点，计算转移概率矩阵
    // 非吸收态数量 
    $nonEndNum = 0;
    $nonEndMap = [];
    // 吸收态数量
    $endNum = 0;
    $endMap = [];
    $orginaltransMatrix = [];
    
    $initIndex = null;
    $winIndexArr = [];
    for ($i = 0; $i < $n; $i++) {
        for ($j = 0; $j < $m; $j++) {
            $index = $i * $m + $j + 1;
            
            switch ($matrix[$i][$j]) {
                case '#': // 墙，不参与状态计算，直接跳过
                    break;
                case '*': // 炸死你！
                    $endMap[$index] = true;
                    $endNum++;
                    break;
                case '%': // 恭喜，到终点了
                    $winIndexArr[] = $index;
                    $endMap[$index] = true;
                    $endNum++;
                    break;
                case 'A':
                    $initIndex = $index;
                case 'O': // 空白砖，非吸收态，计算转移矩阵
                    // 看周围能到的数量，即不是墙的数量
                    $canReachArr = [];
                    $tempIndex = null;
                    if ($i > 0) { // 上
                        $tempIndex = $index - $m;
                        if ('#' != $matrix[$i - 1][$j]) {
                            $canReachArr[] = isset($tunnelMap[$tempIndex]) ? $tunnelMap[$tempIndex] : $tempIndex;
                        }
                    }
                    
                    if ($j > 0) { // 左
                        $tempIndex = $index - 1;
                        if ('#' != $matrix[$i][$j - 1]) {
                            $canReachArr[] = isset($tunnelMap[$tempIndex]) ? $tunnelMap[$tempIndex] : $tempIndex;
                        }
                    }
                    
                    if ($i < $n - 1) { // 下
                        $tempIndex = $index + $m;
                        if ('#' != $matrix[$i + 1][$j]) {
                            $canReachArr[] = isset($tunnelMap[$tempIndex]) ? $tunnelMap[$tempIndex] : $tempIndex;
                        }
                    }
                    
                    if ($j < $m - 1) { // 右
                        $tempIndex = $index + 1;
                        if ('#' != $matrix[$i][$j + 1]) {
                            $canReachArr[] = isset($tunnelMap[$tempIndex]) ? $tunnelMap[$tempIndex] : $tempIndex;
                        }
                    }
                    
                    // 没有能到的，那也是吸收态
                    $canReachNum = count($canReachArr);
                    if (0 == $canReachNum) {
                        $endMap[$index] = true;
                        $endNum++;
                    } else { // 有能到的，平均分配转移概率
                        $nonEndMap[$index] = true;
                        $nonEndNum++;
                        foreach ($canReachArr as $canReachIndex) {
                            $orginaltransMatrix[$index][$canReachIndex] = 1/$canReachNum;
                        }
                    }
                    
                    break;
            }
        }
    }

    //将原状态映射到新状态，前面都是非终止状态，后面都是终止状态
    $oriNewMap = [];
    $nonEndIndex = 0;
    $endIndex = $nonEndNum;
    for ($i = 0; $i < $n; $i++) {
        for ($j = 0; $j < $m; $j++) {
            $index = $i * $m + $j + 1;
            if (isset($nonEndMap[$index])) {
                $oriNewMap[$index] = $nonEndIndex++;
            } else if (isset($endMap[$index])) {
                $oriNewMap[$index] = $endIndex++;
            }
        }
    }
    
    $newOriMap = array_flip($oriNewMap);
    // 根据Absorbing Markov chain定理，计算吸收概率
    
    // 生成It - Q矩阵, 从非终态转到非终态
    // 生成扩展It - Q矩阵，两者一起处理
    $itqMatrix = [];
    for ($i = 0; $i < $nonEndNum; $i++) {
        for ($j = 0; $j < 2 * $nonEndNum; $j++) {
            $itqMatrix[$i][$j] = 0;
            if ($j < $nonEndNum) { // It - Q矩阵
                $itqMatrix[$i][$j] = ($i == $j ? 1 : (isset($orginaltransMatrix[$newOriMap[$i]][$newOriMap[$j]]) ? -$orginaltransMatrix[$newOriMap[$i]][$newOriMap[$j]] : 0));
            } else { // 扩展矩阵
                if ($j == ($i + $nonEndNum)) {
                    $itqMatrix[$i][$j] = 1;
                }
            }
        }
    }
    
    // 行变换求逆矩阵N
    for ($i = 0; $i < $nonEndNum; $i++) {
        if ($itqMatrix[$i][$i] == 0) { // 如果为0， 找一行此列不为0的，加到此行
            $findIndex = null;
            for ($j = 0; $j < $nonEndNum; $j++) {
                if ($i != $j && $itqMatrix[$j][$i] != 0) {
                    $findIndex = $j;
                    break;
                }
            }
            
            for ($j = 0; $j < 2 * $nonEndNum; $j++) {
                $itqMatrix[$i][$j] = $itqMatrix[$i][$j] + $itqMatrix[$findIndex][$j];
            }
        }
        
        // 使i行i列数字变为1
        if ($itqMatrix[$i][$i] != 1) {
            $value = $itqMatrix[$i][$i];
            for ($j = 0; $j < 2 * $nonEndNum; $j++) {
                $itqMatrix[$i][$j] = $itqMatrix[$i][$j] / $value;
            }
        }
        
        // 其他行减去此行，使得第i列其他行数字都变成0
        for ($k = 0; $k < $nonEndNum; $k++) {
            if ($i == $k || 0 == $itqMatrix[$k][$i]) {
                continue;
            }
            
            $value = $itqMatrix[$k][$i];
            for ($j = 0; $j < 2 * $nonEndNum; $j++) {
                $itqMatrix[$k][$j] = $itqMatrix[$k][$j] - $value * $itqMatrix[$i][$j];
            }
        }
    }
    
    // R矩阵，从非终态转到终态
    $rMatrix = [];
    for ($i = 0; $i < $nonEndNum; $i++) {
        for ($j = 0; $j < $endNum; $j++) {
            $rMatrix[$i][$j] = isset($orginaltransMatrix[$newOriMap[$i]][$newOriMap[$j + $nonEndNum]]) ? $orginaltransMatrix[$newOriMap[$i]][$newOriMap[$j + $nonEndNum]] : 0;
        }
    }
    
    // 最终吸收概率矩阵 NR
    $nrMatrix = [];
    for ($i = 0; $i < $nonEndNum; $i++) {
        for ($j = 0; $j < $endNum; $j++) {
            $temp = 0;
            for ($k = 0; $k < $nonEndNum; $k++) {
                $temp = $temp + $itqMatrix[$i][$k + $nonEndNum] * $rMatrix[$k][$j];
            }
            $nrMatrix[$i][$j] = $temp;
        }
    }
    
    // 将初始状态到逃脱态的所有概率加在一起，就是最后结果
    $result = 0;
    foreach ($winIndexArr as $winIndex) {
        $result += $nrMatrix[$oriNewMap[$initIndex]][$oriNewMap[$winIndex] - $nonEndNum];
    }
    
    return $result;
}

$stdin = fopen("php://stdin", "r");

fscanf($stdin, "%[^\n]", $nmk_temp);
$nmk = explode(' ', $nmk_temp);

$n = intval($nmk[0]);

$m = intval($nmk[1]);

$k = intval($nmk[2]);

$matrix = [];
for ($n_itr = 0; $n_itr < $n; $n_itr++) {
    $row = '';
    fscanf($stdin, "%[^\n]", $row);

    // Write Your Code Here
    $matrix[] = $row;
}
$tunnelArr = [];
for ($k_itr = 0; $k_itr < $k; $k_itr++) {
    fscanf($stdin, "%[^\n]", $i1J1I2J2_temp);
    $i1J1I2J2 = explode(' ', $i1J1I2J2_temp);

    $i1 = intval($i1J1I2J2[0]);

    $j1 = intval($i1J1I2J2[1]);

    $i2 = intval($i1J1I2J2[2]);

    $j2 = intval($i1J1I2J2[3]);

    // Write Your Code Here
    $tunnelArr[] = [$i1, $j1, $i2, $j2];
}
// Write Your Code Here

$result = escapeProbability($n, $m, $matrix, $tunnelArr);

$fptr = fopen(getenv("OUTPUT_PATH"), "w");
fwrite($fptr, $result . "\n");
fclose($fptr);

fclose($stdin);
