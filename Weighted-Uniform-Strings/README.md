# Weighted Uniform Strings
原题见[这里](https://www.hackerrank.com/challenges/weighted-uniform-string/problem)

对于字母a到z，它们对应的weight是1到26。

有如下两项定义：
* 一个字符串的weight，等于它每个位置的字母的weight之和。
* 一个字符串被称作是uniform的，如果它仅由单个字母重复多次组成，例如ccc,a都是uniform的，而bcb和cd则不是。

现在，给定一个字符串s，集合U由s的所有uniform的连续子串构成。

给定n个数，输出它们在不在集合U中。

# 分析
这个我的想法就是莽，把所有可能出现的值都缓存起来。

首先初始化从头到尾遍历字符串s，得到每个字母对应的最长uniform子串的长度，然后根据它计算出全部可能出现的值。

最后对于每个要判断的数，直接在缓存中查询即可。

具体代码见[solve.php](./solve.php)