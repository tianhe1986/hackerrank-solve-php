<?php
function workbook($n, $k, $arr) {
    $result = 0;
    
    $page = 0;
    foreach ($arr as $num) {
        // 该章能占据的页数
        $chapterPage = ceil($num / $k);
        
        $start = $end = 0;
        for ($i = 1 ; $i <= $chapterPage; $i++) {
            $page++; // 页数加1
            // 如果此页有题目编号等于当前页数，则结果加1
            
            // 此页开始题号，为上一页结束题号+k
            $start = $end + 1;
            
            // 此页结束题号，为上一页结束题号+k,或是该章最大题号
            $end = $end + $k;
            if ($end > $num) {
                $end = $num;
            }
            if ($start <= $page && $page <= $end) {
                $result++;
            }
        }
    }
    
    return $result;
}
