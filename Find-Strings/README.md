# Find Strings
原题见[这里](https://www.hackerrank.com/challenges/find-strings/problem)

给定一系列字符串，把每个字符串的各个子串放在一起组成一个集合，并去重，按字母顺序排序。

给定一个数k，要求输出对应集合中第k个元素对应的子串，计数从1开始，即第1个，第2个，第3个。。。。如果没有对应的子串，输出INVALID。

# 分析
看着这个题感觉碰到过类似的，是用的后缀数组和最长公共前缀的处理方式。[见这里](../Super-Functional-Strings)

这道题，也可以用这两者来解决。具体的后缀数组，倍增算法排序，以及用kasai算法求最长公共前缀，参考上行中的文章中说明吧，这里就不重复了。

那么拿到排序好的后缀数组，以及求出的最长公共前缀，要怎么得到对应的子串呢。

假设后缀数组为suffix,最长公共前缀数组为lcp,lcp[i]表示suffix[i]与suffix[i+1]的最长公共前缀长度。

下面来处理最终集合中每个元素与这两者的对应关系。

先来看suffix[0]，假设对应的子串长度为l[0]，则最终集合中第1个到第l[0]个元素对应的是suffix[0]的相应长度的前缀。换句话说，suffix[0]对应最终集合中第1个到第l[0]个元素

再看suffix[1]，同样的，假设对应的子串长度为l[1]，它对应最终集合元素的方式有些变化了，得用上lcp[0]，因为它的前lcp[0]个前缀子串，在suffix[0]中已经处理过了。

因此，用suffix[1]继续构造集合中的元素时，要从第lcp[0]+1个前缀开始，直到l[1]为止。即suffix[1]对应最终集合中第l[0] + 1到第 l[0] + l[1] - lcp[0]。

当然，要注意，如果lcp[0]等于l[1]，即suffix[1]与suffix[0]相同，则suffix[1]对应l[0]到l[0]，即元素个数不会增长。

这样依次类推，就能得出suffix数组每个元素包含的集合序列的起始值，以及相应的构造方式。

然后，对于给定的数k，用二分查找找到对应的suffix元素，输出相应的前缀子串即可。

具体代码见[solve.php](./solve.php)