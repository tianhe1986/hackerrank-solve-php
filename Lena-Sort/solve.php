<?php
function lenaSort($len, $c)
{
    // 缓存，每个长度对应的最小比较数和最大比较数
    static $minArr = [];
    static $maxArr = [];
    
    if (empty($minArr)) {
        $minArr = $maxArr = [0, 0, 1];
        for ($i = 3; $i <= 100000; $i++) {
            $sum = 0;
            if ($i % 2 == 0) {
                $sum = $minArr[$i/2] + $minArr[$i/2 - 1];
            } else {
                $sum = 2 * $minArr[($i-1)/2];
            }
            
            $minArr[$i] = $i - 1 + $sum;
            $maxArr[$i] = $i - 1 + $maxArr[$i - 1];
        }
    }
    
    if ($c < $minArr[$len] || $c > $maxArr[$len]) { // 不在此范围内
        return [-1];
    }
    
    // 最坏情况下每层节点数为1
    $levelNumArr = array_fill(0, $len, 1);
    $levelNumArr[$len] = 0;
            
    // 当前比较次数
    $nowNum = $maxArr[$len];
    
    // 当前最小有空间层数
    $nowHasEmptyIndex = 1;
    
    // 当前最小有空闲层数可容纳总数
    $nowMaxEmptyNum = 2;

    // 不断的移动，直到满足条件
    $processIndex = $len - 1;

    while ($nowNum > $c) {
        $diff = $nowNum - $c;
        $maxCanMove = $processIndex - $nowHasEmptyIndex;
        
        // 移动适当的高度
        $transNum = $diff > $maxCanMove ? $maxCanMove : $diff;
        
        // 移动，当前最高层数量变为0
        $levelNumArr[$processIndex] = 0;
        
        // 移动至的层数量+1
        $levelNumArr[$processIndex - $transNum]++;
        
        // 这里只考虑了 $diff > $maxCanMove 的情况， 因为如果diff较小的话，肯定是最后一次移动，也不需要再处理对应层节点数量达到最大的情况了
        if ($levelNumArr[$nowHasEmptyIndex] >= $nowMaxEmptyNum) {
            $nowHasEmptyIndex++;
            $nowMaxEmptyNum *= 2;
        }
        
        $nowNum -= $transNum;
        $processIndex--;
    }
    
    // 中根遍历，生成每个节点的pivot值
    $nowValue = 1;
    $tree = [];
    generatePivot($levelNumArr, $tree, 0, 0, $nowValue);
    
    // 先根遍历，得到结果
    $result = [];
    getResult($result, $tree, 0, 0);
    
    return $result;
}

// 中根遍历，生成每个节点的pivot值
function generatePivot(&$levelNumArr, &$tree, $level, $index, &$nowValue)
{
    $leftIndex = $index << 1;
    $rightIndex = $leftIndex | 1;
    
    if ($leftIndex < $levelNumArr[$level + 1]) { // 存在左子树
        generatePivot($levelNumArr, $tree, $level + 1, $leftIndex, $nowValue);
    }
    
    $tree[$level][$index] = $nowValue++;
    
    if ($rightIndex < $levelNumArr[$level + 1]) { // 存在右子树
        generatePivot($levelNumArr, $tree, $level + 1, $rightIndex, $nowValue);
    }
}

// 先根遍历，得到结果
function getResult(&$result, &$tree, $level, $index)
{
    $result[] = $tree[$level][$index];
    
    $leftIndex = $index << 1;
    $rightIndex = $leftIndex | 1;
    
    if (isset($tree[$level + 1][$leftIndex])) { // 存在左子树
        getResult($result, $tree, $level + 1, $leftIndex);
    }
    
    if (isset($tree[$level + 1][$rightIndex])) { // 存在右子树
        getResult($result, $tree, $level + 1, $rightIndex);
    }
}


$stdin = fopen("php://stdin", "r");

fscanf($stdin, "%d\n", $q);

for ($q_itr = 0; $q_itr < $q; $q_itr++) {
    fscanf($stdin, "%[^\n]", $lc_temp);
    $lc = explode(' ', $lc_temp);

    $l = intval($lc[0]);

    $c = intval($lc[1]);

    // Write Your Code Here
    $result = lenaSort($l, $c);
    echo implode(" ", $result) . "\n";
}

fclose($stdin);
