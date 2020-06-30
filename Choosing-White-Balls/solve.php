<?php
function whiteBalls($balls, $k)
{
    $n = strlen($balls);
    $flag = $balls[0] == 'W' ? 1 : 0;
    
    // 移位缓存，用于帮助计算某个二进制数，去掉其中一位，其左边所有位右移，得到的值
    $shiftCache = [1];
    
    for ($i = 1; $i < $n; $i++) {
        $flag <<= 1;
        $flag |= ($balls[$i] == 'W' ? 1 : 0);
        
        $shiftCache[$i] = ($shiftCache[$i - 1] << 1) | 1;
    }

    return dynamic($flag, $shiftCache, $n, $k);
}

function removeLoc($flag, &$shiftCache, $loc)
{
    if ($loc == 0) {
        return $flag >> 1;
    }
    // 此位之后的全部保留
    $result = $flag & ($shiftCache[$loc - 1]);
    
    // 右移一位，此位之前的全部保留
    $keepItem = ($flag >> 1) & (~$shiftCache[$loc - 1]);
    
    return $result | $keepItem;
}

// 在n位内进行二进制逆序
function getRe($flag, $n)
{
    $result = $flag;
    $nextn = $n - 1;
    for ($i = ($n >> 1) - 1; $i >= 0; $i--) {
        $end = $nextn - $i;
        $temp = (($result >> $i) & 1) ^ (($result >> $end) & 1);
        if ($temp == 0) {
            continue;
        }
        
        $result = $result ^ ((1 << $i) | (1 << $end));
    }
    
    return $result;
}

function dynamic($flag, &$shiftCache, $n, $t)
{
    static $smallCache = null;
    if (null === $smallCache) { // 减少使用空间
        $smallCache = new SplFixedArray(1 << 24);
    }
    static $cache = [];
    
    if ($n < 24) {
        $cacheKey = (1 << $n) | $flag;
        if (isset($smallCache[$cacheKey])) {
            return $smallCache[$cacheKey];
        }
    } else {
        if (isset($cache[$flag][$t])) {
            return $cache[$flag][$t];
        }
    }
    
    if ($t == 0) {
        return 0;
    }
    
    $result = 0;
    $nextn = $n - 1;
    $nextt = $t - 1;
    
    for ($i = ($n >> 1) - 1; $i >= 0; $i--) {
        $end = $nextn - $i;
        
        $frontWhite = ($flag & (1 << $i)) ? 1 : 0;
        $endWhite = ($flag & (1 << $end)) ? 1 : 0;
        
        $temp1 = dynamic(removeLoc($flag, $shiftCache, $i), $shiftCache, $nextn, $nextt) + $frontWhite;
        $temp2 = dynamic(removeLoc($flag, $shiftCache, $end), $shiftCache, $nextn, $nextt) + $endWhite;

        // 前后取，选择两者中的更大者
        $result += ($temp1 > $temp2 ? 2 * $temp1 : 2 * $temp2);
    }
    
    if ($n % 2 == 1) { // 长度为奇数时，中间1位只有一种取法，直接加上
        $temp = ($n >> 1);
        $result += (($flag & (1 << $temp)) ? 1 : 0) + dynamic(removeLoc($flag, $shiftCache, $temp), $shiftCache, $nextn, $nextt);
    }
    
    if ($n < 24) {
        $secondCacheKey = getRe($flag, $n) | (1 << $n);
        return $smallCache[$cacheKey] = $smallCache[$secondCacheKey] = $result / $n;
    } else {
        return $cache[$flag][$t] = $cache[getRe($flag, $n)][$t] = $result / $n;
    }
}


$stdin = fopen("php://stdin", "r");

fscanf($stdin, "%[^\n]", $nk_temp);
$nk = explode(' ', $nk_temp);

$n = intval($nk[0]);

$k = intval($nk[1]);

$balls = '';
fscanf($stdin, "%[^\n]", $balls);

// Write Your Code Here
$result = whiteBalls($balls, $k);
echo $result . "\n";

fclose($stdin);