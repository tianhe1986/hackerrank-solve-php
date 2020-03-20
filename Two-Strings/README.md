# Two Strings
原题见[这里](https://www.hackerrank.com/challenges/two-strings/problem)

对于两个字符串，如果有公共子串，输出YES，否则输出NO

# 分析
题目说了所有的字符都是小写英文字母。

所以，“有公共子串”这句话应该这样理解，“有一个字母同时出现在两个字符串中”。

这就很简单了，遍历记录两个字符串中出现的字母集，再检查它们是否有交集即可。

具体代码见[solve.php](./solve.php)