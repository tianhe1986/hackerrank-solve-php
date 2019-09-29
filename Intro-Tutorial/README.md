# Intro to Tutorial Challenges
原题见[这里](https://www.hackerrank.com/challenges/tutorial-intro/problem)
给定一个排好序的数组, 要求找出某个值在数组中的位置。

# 分析
第一反应就是，二分查找。二分查找我就真的不详细介绍了，每次取区间的中点元素进行比较，根据结果返回或是缩短区间。

二分查找代码见[solve.php](./solve.php)

但是！对于这道题，有一个有意思的方法。根据题意，要查找的值在数组之前提供，而且我们已经知道要查找的值肯定会出现。

那么，可以在读入数组元素时，一个个的处理，如果发现跟要查找的值相同，就直接输出结果。这部分代码，自己思考下怎么写吧hhhhhhhh