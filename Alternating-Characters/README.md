# Alternating Characters
原题见[这里](https://www.hackerrank.com/challenges/alternating-characters/problem)

有中文，就不翻译了。

# 分析
这道题的关键就是一句话“如果相邻两个字符串相同，那么需要删除其中一个”。

在这里，始终选择删除后面那个。连续进行比较，碰到相邻相同的，就删除数量加1即可。

具体代码见[solve.php](./solve.php)
