<?php
function binarySearch(&$arr, $low, $high, $value)
{
    while ($low <= $high) {
        $middle = ($low + $high) >> 1;
        if ($arr[$middle] == $value) {
            return $middle;
        } else if ($arr[$middle] < $value) {
            $low = $middle + 1;
        } else {
            $high = $middle - 1;
        }
    }
    
    return $high;
}

// q = 2时的解
function subSolveTwo($p)
{
    // 建立不同节点数量 => triplets数map
    $nodeTriMap = [];
    
    // 最少也得要4个点，才能形成一个triplet
    $nodeTriMap[4] = 1;
    $i = 5;
    
    while (true) {
        $temp = $i - 2;
        $add = $temp * ($temp - 1) / 2;
        
        $nodeTriMap[$i] = $nodeTriMap[$i - 1] + $add;
        if ($nodeTriMap[$i] >= 5000) { // 不会超过5000个triplet，因此超了5000就停下来;
            break;
        }
        
        $i++;
    }
    
    $mapLow = 4;
    $mapHigh = $i;
    
    $nodeCount = 0;
    $edgeCount = 0;
    
    // 不断二分查找找到 《= 当前需要剩余数量的节点数量，更新总节点数和总边数
    $resultArr = [];
    
    $processValue = $p;
    while ($processValue > 0) {
        $index = binarySearch($nodeTriMap, $mapLow, $mapHigh, $processValue);
        $resultArr[] = $index;
        $processValue -= $nodeTriMap[$index];
        
        $nodeCount += $index;
        $edgeCount += ($index - 1);
    }
    
    // 输出节点数和边数
    echo $nodeCount." ".$edgeCount."\n";
    
    // 输出每条边
    $nowStart = 1;
    
    foreach ($resultArr as $num) {
        for ($i = 1; $i < $num; $i++) {
            echo ($nowStart)." ".($nowStart + $i)."\n";
        }
        
        $nowStart += $num;
    }
}

function getMinDoubleArr()
{
    // 包含三项，第一项为两者之和，第二项为数字a，第二项为数字b
    $result = [];
    for ($i = 1; $i <= 5000; $i++) {
        $sqrt = intval(sqrt($i));
        for ($j = $sqrt; $j >= 1; $j--) {
            if ($i % $j == 0) { // 越接近，和越小，这是必然的
                $right = $i / $j;
                $result[$i] = [$j + $right, $j, $right];
                break;
            }
        }
    }
    
    return $result;
}

function getMinTripleArr()
{
    // 先获取double arr，再依次计算tripleArr
    $minDoubleArr = getMinDoubleArr();
    
    $result = [];
    for ($i = 1; $i <= 5000; $i++) {
        $nowMinValue = 100000;
        $nowMinItem = [];
        
        for ($j = 1; $j <= 20; $j++) {
            if ($i % $j != 0) { // 必须要能整除才能拆分
                continue;
            }
            
            $temp = $i / $j;
            $tempSum = $j + $minDoubleArr[$temp][0];
            if ($tempSum < $nowMinValue) {
                $nowMinValue = $tempSum;
                $nowMinItem = [$tempSum, $j, $minDoubleArr[$temp][1], $minDoubleArr[$temp][2]];
            }
        }
        
        $result[$i] = $nowMinItem;
    }
    
    return $result;
}

function buildMinTree(&$minTripleArr, $plusNum, $isEven = false)
{
    // 对于每个数，比较到底是自己相乘比较好，还是拆分比较好，选择更小的
    
    // 包含三项，第一项为节点总数，第二项为边总数，第三项为拆分后左子树triplet总数，第三项为0表示不拆
    $result = [];
    
    for ($i = 1; $i <= 5000; $i++) {
        $nowMinValue = $minTripleArr[$i][0] + $plusNum;
        // 对于奇数来说，每棵树边数和节点数总是相同
        // 对于偶数来说，边数是节点数-1
        $nowMinItem = [$nowMinValue, $isEven ? ($nowMinValue - 1) : $nowMinValue, 0];
        
        $processMax = $i >> 1;
        for ($j = 1; $j <= $processMax; $j++) {
            $k = $i - $j;
            $tempSum = $result[$j][0] + $result[$k][0];
            
            if ($tempSum < $nowMinValue) { // 这样拆需要的节点更少
                $nowMinValue = $tempSum;
                
                $nowMinItem = [$nowMinValue, $result[$j][1] + $result[$k][1], $j];
            }
        }
        
        $result[$i] = $nowMinItem;
    }
    
    return $result;
}

// q>2时的解
function subSolveOther($p, $q)
{
    // q是否是偶数
    $isEven = ($q % 2 == 0);
    // 奇数：中间三个点两两连接，仿佛三体一般, 然后每个点各向一个方向延伸
    // 偶数： 中间一个点，形成三条射线

    // 每个数划分成3个数相乘能够达到的最小值
    $minTripleArr = getMinTripleArr();
    // 每一棵树需要额外占用的节点数
    $plusNum = ($isEven ? (3 * $q / 2 - 2) : (3 * ($q - 1) / 2));
    
    // 构造可能的最小数量
    $minTree = buildMinTree($minTripleArr, $plusNum, $isEven);
    
    // 遍历输出结果
    $nowStart = 1;
    
    echo $minTree[$p][0]. " ".$minTree[$p][1] . "\n";
    printTree($minTree, $minTripleArr, $p, $isEven ? ($plusNum - 1)/3 : $plusNum/3, $nowStart, $isEven);
}

function printTree(&$minTree, &$minTripleArr, $tripleNum, $directNum, &$nowStart, $isEven)
{
    if ($minTree[$tripleNum][2] != 0) { // 被拆开了，继续遍历打印每棵树
        printTree($minTree, $minTripleArr, $minTree[$tripleNum][2], $directNum, $nowStart, $isEven);
        printTree($minTree, $minTripleArr, $tripleNum - $minTree[$tripleNum][2], $directNum, $nowStart, $isEven);
    } else {
        // 中心点
        $center = null;
        if ($isEven) {
            $center = $nowStart++;
        }
        
        $startEndArr = [
            [$nowStart, $nowStart + $directNum - 1]
        ];
        
        for ($i = 1; $i <= 2; $i++) {
            $startEndArr[$i] = [$startEndArr[$i - 1][0] + $directNum, $startEndArr[$i - 1][1] + $directNum];
        }
        $nowStart += 3 * $directNum;
        // 偶数： 真起点连到每条射线起点
        if ($isEven) {
            echo $center." ".$startEndArr[0][0]."\n";
            echo $center." ".$startEndArr[1][0]."\n";
            echo $center." ".$startEndArr[2][0]."\n";
        } else { // 奇数：三个起点互连
            echo $startEndArr[0][0]." ".$startEndArr[1][0]."\n";
            echo $startEndArr[0][0]." ".$startEndArr[2][0]."\n";
            echo $startEndArr[1][0]." ".$startEndArr[2][0]."\n";
        }
        
        // 每条边上的射线
        for ($i = 0; $i <= 2; $i++) {
            for ($j = $startEndArr[$i][0] + 1; $j <= $startEndArr[$i][1]; $j++) {
                echo ($j - 1)." ".$j."\n";
            }
        }
        
        for ($i = 0; $i <= 2; $i++) {
            $edgeNum = $minTripleArr[$tripleNum][$i + 1];
            for ($j = 1; $j <= $edgeNum; $j++) { // 连到对应的end点上
                echo $startEndArr[$i][1]." ".$nowStart."\n";
                $nowStart++;
            }
        }
    }
}

function lovelyTriplets($p , $q)
{
    // q = 2
    if ($q == 2) {
        subSolveTwo($p);
    } else{ // q > 2
        subSolveOther($p, $q);
    }
}


$stdin = fopen("php://stdin", "r");

fscanf($stdin, "%[^\n]", $PQ_temp);
$PQ = explode(' ', $PQ_temp);

$P = intval($PQ[0]);

$Q = intval($PQ[1]);

lovelyTriplets($P, $Q);

fclose($stdin);