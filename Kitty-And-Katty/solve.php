<?php
function kittyAndKatty($n)
{
    // n = 1时额外判断，外加“谁操作最后一步谁赢”
    return ($n % 2 == 0 || $n == 1) ? 'Kitty' : 'Katty';
}
