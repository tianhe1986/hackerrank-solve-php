<?php
function determiningDna(&$genes, &$health, &$strands)
{
    // 存储前缀是否在序列中
    $preMap = [];
    
    // 存储每一个gene出现对应的index和值
    $healthValueMap = [];
    
    foreach ($genes as $index => $gene) {
        $len = strlen($gene);
        for ($i = 1; $i <= $len; $i++) {
            $preMap[substr($gene, 0, $i)] = true;
        }
        
        // 0数组存储的是所有出现的index
        // 1数组存储的是到出现的index为止，此项的累计健康值
        $healthValueMap[$gene][0][] = $index;
        $healthValueMap[$gene][1] = [0];
    }
    
    foreach ($healthValueMap as $gene => &$valueArr) {
        foreach ($valueArr[0] as $i => $origiIndex) {
            $valueArr[1][] = $valueArr[1][$i] + $health[$origiIndex];
        }
    }
    
    $min = 2000000000007;
    $max = 0;
    foreach ($strands as $item) {
        $tempValue = 0;
        $len = strlen($item[2]);
        for ($i = 0; $i <= $len - 1; $i++) {
            $endLen = $len - $i;
            for ($j = 1; $j <= $endLen; $j++) {
                $tempStr = substr($item[2], $i, $j);
                if ( ! isset($preMap[$tempStr])) { // 不存在此前缀，不用继续了
                    break;
                }
                
                if ( ! isset($healthValueMap[$tempStr])) { // 不存在此gene，继续下一个
                    continue;
                }
                
                $left = leftSearch($healthValueMap[$tempStr][0], $item[0]);
                $right = rightSearch($healthValueMap[$tempStr][0], $item[1]);
                
                // 增加此gene在start和end范围内健康值累加和
                $tempValue += $healthValueMap[$tempStr][1][$right] - $healthValueMap[$tempStr][1][$left];
            }
        }
        
        if ($tempValue < $min) {
            $min = $tempValue;
        }
        
        if ($tempValue > $max) {
            $max = $tempValue;
        }
    }
    
    return [$min, $max];
}

// 返回左数第一个大于等于此值的index
function leftSearch(&$arr, $value)
{
    $low = 0;
    $high = count($arr) - 1;
    if ($value <= $arr[$low]) {
        return $low;
    } else if ($value > $arr[$high]) {
        return $high + 1;
    }
    
    while ($low <= $high) {
        $middle = intval(($low + $high) / 2);
        if ($arr[$middle] == $value) { // 相等，就是它了
            return $middle;
        } else if ($arr[$middle] < $value) { // 比要找的值小，提高下界
            $low = $middle + 1;
        } else { // 比要找的值大，降低上界
            $high = $middle - 1;
        }
    }
    
    return $low;
}

// 返回左数第一个大于此值的index
function rightSearch(&$arr, $value)
{
    $low = 0;
    $high = count($arr) - 1;
    if ($value < $arr[$low]) {
        return $low;
    } else if ($value >= $arr[$high]) {
        return $high + 1;
    }
    
    while ($low <= $high) {
        $middle = intval(($low + $high) / 2);
        if ($arr[$middle] == $value) { // 相等，就是它后面那个了
            return $middle + 1;
        } else if ($arr[$middle] < $value) { // 比要找的值小，提高下界
            $low = $middle + 1;
        } else { // 比要找的值大，降低上界
            $high = $middle - 1;
        }
    }
    
    return $low;
}

$stdin = fopen("php://stdin", "r");

fscanf($stdin, "%d\n", $n);

fscanf($stdin, "%[^\n]", $genes_temp);

$genes = preg_split('/ /', trim($genes_temp), -1, PREG_SPLIT_NO_EMPTY);

fscanf($stdin, "%[^\n]", $health_temp);

$health = array_map('intval', preg_split('/ /', trim($health_temp), -1, PREG_SPLIT_NO_EMPTY));

fscanf($stdin, "%d\n", $s);

$strands = [];

for ($s_itr = 0; $s_itr < $s; $s_itr++) {
    fscanf($stdin, "%[^\n]", $firstLastd_temp);
    $firstLastd = explode(' ', trim($firstLastd_temp));

    $first = intval($firstLastd[0]);

    $last = intval($firstLastd[1]);

    $d = $firstLastd[2];
    $strands[] = [$first, $last, $d];
}

fclose($stdin);

$result = determiningDna($genes, $health, $strands);
echo implode(" ", $result) . "\n";