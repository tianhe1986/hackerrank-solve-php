# String Function Calculation
原题见[这里](https://www.hackerrank.com/challenges/string-function-calculation/problem)

有中文，就不翻译了，不过还是得补充下f(S)的定义：
* f(S) = S的长度 * S在T中出现的次数

# 分析

## LCP array解法
这个也是编辑讲的解法，首先求出后缀数组和最长公共前缀。关于这两者，我之前已经有讲过，见[这里](../Super-Functional-Strings)

然后，求出了这两者之后，再怎么计算呢？考虑一下，假设对于子串ab，它在整个字符串中出现了i次，那么，在后缀数组中以其开头的前i-1项的最长公共前缀长度就至少是2。

如果放在坐标系中表示，x轴为后缀数组的索引，y轴为与下一项的最长公共前缀长度。那么，求最大的f(S)就类似于是求最大的矩形面积。

这样的问题，可以用栈的方式解决，参考下[这篇文章](https://www.geeksforgeeks.org/largest-rectangle-under-histogram/)，真的是，非常精妙。

当然，其实，这道题的完整解法也是有的，见[此文](https://www.geeksforgeeks.org/substring-highest-frequency-length-product/)， 可以仔细看下，不要害怕英文。

当然了，这种解法我也实现了，具体代码见[solve-lcp.php](./solve-lcp.php)

## Ukkonen后缀树的解法
理论上来说，我是应该把Ukkonen后缀树的构造法完整讲一遍的，但是感觉自己也只是大概理解了，而且发现讲不清楚:(

推荐读下[这篇文章](https://www.geeksforgeeks.org/ukkonens-suffix-tree-construction-part-1/)，如果想要透彻的理解，那么一定，一定，请从part1开始读，直到part6。如果只是想知道代码怎么写，那么part6就够了。

然后问题来了，构造出了后缀树之后，要怎么求解呢？

答案就是，对每个节点，求 对应的前缀字符长度 * 子树中叶节点数量， 其中的最大值，就是f(S)的最大值了。 

尝试理解一下，子树中有多少个叶节点，就表示对应的前缀字符是多少个后缀字符串的前缀，也就是出现了多少次。

而只需要对节点求的原因是，中间的边对应的各个前缀字符串，出现次数是跟连接到的节点对应前缀字符一样的，而长度要比这个短，因此算出来的f(S)肯定没有连接到的节点计算出来的大。

具体代码见[solve.php](./solve.php)