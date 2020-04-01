# Game of Thrones - I
原题见[这里](https://www.hackerrank.com/challenges/game-of-thrones/problem)

简单来说，就是给定一个字符串，判断能够将字符重新排列，变成一个回文串（正读反读长一样）

# 分析

既然是回文，那就是左右对称的，那么，除了最中间的字母（当整个字符串长度为奇数时）可以出现奇数次之外，其他位置字母都只能出现偶数次。

因此，只要记录每种字母出现的次数，最后判断是否出现奇数次的字母数量小于等于1即可。

具体代码见[solve.php](./solve.php)