<?php
function libraryFine($d1, $m1, $y1, $d2, $m2, $y2) {
    if ($y2 > $y1) { // 年份提前
        return 0;
    } else if ($y1 > $y2) { // 跨年了
        return 10000;
    } else if ($m1 < $m2) { // 月份提前
        return 0;
    } else if ($m1 > $m2) { //跨月了
        return 500 * ($m1 - $m2);
    } else { // 比较日
        return $d1 > $d2 ? ($d1 - $d2) * 15 : 0;
    }
}
