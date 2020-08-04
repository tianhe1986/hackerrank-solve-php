# Lonely Integer
原题见[这里](https://www.hackerrank.com/challenges/lonely-integer/problem)

给定一个数组，数组里的数字，只有一个仅出现一次，其他都出现两次。找出这个仅出现一次的数字。

例如[1, 2, 3, 4, 3, 2, 1]，其中只有4仅出现一次，其他都出现了两次。

# 分析
这个题目，就是用来考你异或操作怎么用的，有这样三条重要的性质：
* 异或操作满足交换律，即 a xor b xor c = a xor c xor b
* 异或操作满足结合律，即 (a xor b) xor c = a xor (b xor c)
* a xor a = 0

所以，就依次异或，最后得出的数就是仅出现一次的，因为其他数出现两次， 在异或操作中变为了0。

当然，用加法操作也是可以的，题目中数字的范围不会溢出。

具体代码见[solve.php](./solve.php)