<?php
function playingHanoi($arr)
{
    $n = count($arr);
    
    // 用于储存走到某个状态需要的最小步数
    $minDisMap = [];
    
    // 开始状态
    $startState = 0;
    
    // 结束状态
    $endState = 0;
    for ($i = 0; $i < $n; $i++) {
        $endState |= (($arr[$i] - 1) << 2 * $i);
    }
    
    // 宽度优先搜索，到了终点状态，则break
    $queue = [];
    $startIndex = $endIndex = 0;
    $queue[$endIndex++] = $startState;
    $minDisMap[$startState] = 0;
    
    while ($startIndex < $endIndex) {
        $nowState = $queue[$startIndex];
        unset($queue[$startIndex]);
        $startIndex++;
        
        $topArr = [10, 10, 10, 10];
        for ($i = $n - 1; $i >= 0; $i--) {
            $topArr[($nowState >> 2 * $i) & 3] = $i;
        }
        
        for ($i = 0; $i < 3; $i++) {
            for ($j = $i + 1; $j <= 3; $j++) {
                // 将小的移到大的上面
                $newState = null;
                if ($topArr[$i] < $topArr[$j]) {
                    $newState = ($nowState &(~(3 << 2 * $topArr[$i]))) | ($j << 2 * $topArr[$i]);
                } else if ($topArr[$i] > $topArr[$j]) {
                    $newState = ($nowState &(~(3 << 2 * $topArr[$j]))) | ($i << 2 * $topArr[$j]);
                } else { // 只有两个棍子都没有放圆盘时，才会相等
                    continue;
                }

                if ( ! isset($minDisMap[$newState])) { // 到了一个新的状态，加入到队列中继续遍历
                    $minDisMap[$newState] = $minDisMap[$nowState] + 1;
                    $queue[$endIndex++] = $newState;
                    if ($newState == $endState) { // 到了结束状态，返回
                        return $minDisMap[$newState];
                    }
                }
            }
        }
    }
}


$stdin = fopen("php://stdin", "r");

fscanf($stdin, "%d\n", $N);

fscanf($stdin, "%[^\n]", $a_temp);

$a = array_map('intval', preg_split('/ /', $a_temp, -1, PREG_SPLIT_NO_EMPTY));

fclose($stdin);

$result = playingHanoi($a);

$fptr = fopen(getenv("OUTPUT_PATH"), "w");
fwrite($fptr, $result . "\n");
fclose($fptr);