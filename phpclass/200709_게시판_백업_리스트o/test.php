<?php
// 0 -> 0  1  2  3  4 : default
// 1 -> 5  6  7  8  9
// 2 -> 10 11 12 13 14
// 3 -> 15 16 17 18 19
for ($a = 1; $a < 5; $a++){
    for ($b = 0; $b < 5; $b++){
        echo (($a * 5)  + $b)." , ";
        // 5 6 7 8 9
        // 10
    }
    echo "<br>";
}
// 1 * 4 + 1
