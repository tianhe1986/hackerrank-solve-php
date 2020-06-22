<?php
function verticalRooks($r1, $r2) {
    $n = count($r1);
    
    $grundy = 0;
    
    for ($i = 0; $i < $n; $i++) {
        // 每一列的grundy值是距离 - 1
        $diff = abs($r1[$i] - $r2[$i]) - 1;
        // 再对每列grundy值取异或
        $grundy = $grundy ^ $diff;
    }

    return $grundy == 0 ? 'player-1' : 'player-2';
}