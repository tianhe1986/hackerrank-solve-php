<?php
function newYearGame($a) {
    $oneNum = 0;
    $twoNum = 0;
    
    foreach ($a as $num) {
        $mod = $num % 3;
        if ($mod == 1) {
            $oneNum ^= 1;
        } else if ($mod == 2) {
            $twoNum ^= 1;
        }
    }
    
    // 余1和数量和余2的数量都是偶数，则Koca赢，否则Balsa赢
    return ($oneNum | $twoNum) ? "Balsa" : "Koca";
}


$stdin = fopen("php://stdin", "r");

fscanf($stdin, "%d\n", $T);

for ($T_itr = 0; $T_itr < $T; $T_itr++) {
    fscanf($stdin, "%d\n", $n);

    fscanf($stdin, "%[^\n]", $a_temp);

    $a = array_map('intval', preg_split('/ /', $a_temp, -1, PREG_SPLIT_NO_EMPTY));

    // Write Your Code Here
    $result = newYearGame($a);

    echo $result . "\n";
}

fclose($stdin);
