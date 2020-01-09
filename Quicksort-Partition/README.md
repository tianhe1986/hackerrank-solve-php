# Quicksort 1 - Partition
原题见[这里](https://www.hackerrank.com/challenges/quicksort1/problem)

这是快速排序的步骤之一：划分。

选定数组中的一个特定元素p，然后将数组划分成三个子集,left,right和equal。

left中元素都小于p，equal中的元素都等于p，right中的元素都大于p。

然后依次输出left,equal,以及right中的元素，以空格分隔。

# 分析
这道题，我就直接按照题意给的方法来了，没有使用快速排序中的交换处理，依次处理每个元素，放到对应集合中。

具体代码见[solve.php](./solve.php)