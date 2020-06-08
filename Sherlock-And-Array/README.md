# Sherlock-And-Array
原题见[这里](https://www.hackerrank.com/challenges/sherlock-and-array/problem)

给定一个数组，问能不能找到一项，它左边的所有项之和等于右边的所有项之和。

# 分析

这个我就非常单刀直入的求解了，先算出所有项的和，然后从前往后依次遍历，求前面所有项的累加和，如果（所有项和） - (当前项) = 2 * (前面项累加和)，则返回YES，否则返回NO。

具体代码见[solve.php](./solve.php)