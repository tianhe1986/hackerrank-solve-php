<?php
function minimumNumber($n, $password) {
    // 长度之差
    $diffLength = 6 - $n;

    // 用位记录四种字符是否出现
    $typeFlag = 0;
    
    $numbers = "0123456789";
    $lowerCase = "abcdefghijklmnopqrstuvwxyz";
    $upperCase = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $specialCharacters = "!@#$%^&*()-+";
    
    for ($i = 0; $i < $n; $i++) {
        if (strpos($numbers, $password[$i]) !== false) {
            $typeFlag |= 1;
        } else if (strpos($lowerCase, $password[$i]) !== false) {
            $typeFlag |= 2;
        } else if (strpos($upperCase, $password[$i]) !== false) {
            $typeFlag |= 4;
        } else if (strpos($specialCharacters, $password[$i]) !== false) {
            $typeFlag |= 8;
        }
    }
    
    $diffType = 0;
    for ($i = 1; $i <= 8; $i *= 2) {
        if ( ! ($typeFlag & $i)) { // 未出现对应类型字符
            $diffType++;
        }
    }
    
    return max($diffLength, $diffType);
}
