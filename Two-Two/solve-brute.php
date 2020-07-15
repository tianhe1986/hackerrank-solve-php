<?php
function twoTwo($s)
{
    $result = 0;
    for ($i = 0; $i <= 800; $i++) {
        $searchStr = gmp_strval(gmp_pow(2, $i)); // string
        $pos = 0;
        while (($pos = strpos($s, $searchStr, $pos)) !== false) {
            $pos++;
            $result++;
        }
    }

    return $result;
}
