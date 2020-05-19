# Grid Challenge
原题见[这里](https://www.hackerrank.com/challenges/grid-challenge/problem)

有中文，我就不翻译了。

# 分析
题目中的条件，可以这样翻译翻译：
* 同一行，字符从小到大排序。
* 相邻的两行，对于同一列，下一行的字符要大于等于上一行的。

再进一步翻译翻译成处理方法：
* 对每行字符排序。
* 检查相邻两行每一列是否满足条件。

实际处理时并不需要一次性全部排好序，而是排好一行，与上一行相比即可。

具体代码见[solve.php](./solve.php)