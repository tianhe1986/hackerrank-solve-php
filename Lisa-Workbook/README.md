# Lisa's Workbook
原题见[这里](https://www.hackerrank.com/challenges/lisa-workbook/problem)

Lisa有一本数学题册，里面的题目是按章节划分的。Lisa认为某道题是特别的，如果这道题的序号（章节内序号）跟当前页码相同。数学题册的格式是这样的：
* 一共有n个章节，章节序号从1到n。
* 第i个章节有arr[i]道题，序号从1到arr[i]。
* 每一页，最多容纳k道题。每一章都要从新的一页开始，页序号也是从1开始。

给定格式，求问有多少道特别的题。

# 分析

我的想法是，遍历每个章节，在内部，按页来处理，在第i页，假设其包含的题目起止序号为a和b，若 a <= i且 i <= b，则特殊题数量+1。

具体算法如下：
1. 设置特殊题数量result = 0，当前页 page = 0。
2. 对于每个章节i：
    1. 计算此章节占据的页数 ceil(arr[i] / k)
    2. 对于此章节的每一页j：
        1. 当前页数 page 加1.
        2. 计算当前页包含的题目起止序号start = (j-1) * k + 1, end = min(j*k, arr[i])
        3. 若 start <= page且 page <= end，result 加1。

具体代码见[solve.php](./solve.php)