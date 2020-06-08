<?php
function extremumPermutations($n, $a, $b) {
    $lessFlag = [];
    $moreFlag = [];
    
    // -1: 需要比前面小
    // 0: 无所谓
    // 1: 需要比前面大
    $needFlag = [];
    for ($i = -1; $i <= $n; $i++) {
        $needFlag[$i] = 0;
    }
    
    // 比旁边小的flag
    foreach ($a as $temp) {
        $lessFlag[$temp - 1] = true;
        $needFlag[$temp - 1] = -1;
        $needFlag[$temp] = 1;
    }

    // 比旁边大的flag
    foreach ($b as $temp) {
        if (isset($lessFlag[$temp - 1])) {
            return 0;
        }
        $moreFlag[$temp - 1] = true;
        $needFlag[$temp - 1] = 1;
        $needFlag[$temp] = -1;
    }
    
    // 检查下相邻的有没有同一个类别的
    for ($i = 0; $i < $n; $i++) {
        if (isset($lessFlag[$i])) {
            if (isset($lessFlag[$i + 1])) {
                return 0;
            }
        } else if (isset($moreFlag[$i])) {
            if (isset($moreFlag[$i + 1])) {
                return 0;
            }
        }
    }
    unset($lessFlag, $moreFlag);

    // 内存会超，只好这样:-(
    $arr = new SplFixedArray(($n + 1) * ($n + 1));
    
    return dynamic($arr, 0, $n, $needFlag, $n - 1, $n);
}

function dynamic(&$arr, $location, $canChooseNum, &$needFlag, $end, $n)
{
    $key = $location * ($n + 1) + $canChooseNum;
    if (isset($arr[$key])) {
        return $arr[$key];
    }

    if ($end < 0) {
        return 1;
    } else if ($canChooseNum == 0) {
        return 0;
    }
    
    // 当前有 $end + 1 个数 end = $n - location - 1
    // 按从小到大依次序号为 0 - $end
    
    // 选择当前时只用考虑与前一个的关系
    $result = 0;
    $next = $location + 1;
    $nextEnd = $end - 1;
    
    // 必须比前一个大
    if ($needFlag[$location] == 1) {
        // 检查后面的关系
        if ($needFlag[$next] == -1) { // 比前面小
            $result = dynamic($arr, $next, $end - $canChooseNum + 1, $needFlag, $nextEnd, $n) + dynamic($arr, $location, $canChooseNum - 1, $needFlag, $end, $n);
        } else if ($needFlag[$next] == 1) { // 比前面大
            $result = dynamic($arr, $next, $canChooseNum - 1, $needFlag, $nextEnd, $n) + dynamic($arr, $location, $canChooseNum - 1, $needFlag, $end, $n);
        } else {
            $result = $canChooseNum * dynamic($arr, $next, $end, $needFlag, $nextEnd, $n);
        }
    } else {
        if ($needFlag[$next] == 1) { // 比前面大
            $result = dynamic($arr, $next, $end - $canChooseNum + 1, $needFlag, $nextEnd, $n) + dynamic($arr, $location, $canChooseNum - 1, $needFlag, $end, $n);
        } else if ($needFlag[$next] == -1) { // 比前面小
            $result = dynamic($arr, $next, $canChooseNum - 1, $needFlag, $nextEnd, $n) + dynamic($arr, $location, $canChooseNum - 1, $needFlag, $end, $n);
        } else {
            $result = $canChooseNum * dynamic($arr, $next, $end, $needFlag, $nextEnd, $n);
        }
    }
    
    return $arr[$key] = $result % 1000000007;
}