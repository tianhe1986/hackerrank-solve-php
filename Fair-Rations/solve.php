<?php
function fairRations($B) {
    //前一个人手中是否是奇数块面包
    $flag = false;
    
    $result = 0;
    foreach ($B as $num) {
        if ($flag) { //前一个人手中是否是奇数块面包
            // 不论此人奇偶，都要放发
            $result += 2;
            
            if ($num % 2) { // 此人也是奇数， 手里都变成偶数
                $flag = false;
            } else { // 前一个人手里变偶数，此人变奇数，继续处理
                
            }
        } else {
            if ($num % 2) { // 此人是奇数， 记录
                $flag = true;
            } else { // 不用管， 跳过
                
            }
        }
    }
    
    //最后一个人手里是奇数块， 不可能
    return $flag ? 'NO' : $result;
}

