# Similar Pair
原题见[这里](https://www.hackerrank.com/challenges/similarpair/problem)

对于一棵树中的一对节点(a, b)，它们被称作是similar pair，如果满足以下两个条件：
1. 节点a是b的祖先。
2. abs(a - b) <= k

给定一棵树，节点的值从1到n唯一， 问树中一共有多少similar pair

# 分析

这个题目是要找对合适的数据结构。

处理的方法是，从树的根节点开始，使用先根遍历，维护一份祖先列表，在遍历过程中动态改变此列表，使得处理某个节点时，此列表中包含的是其全部祖先。假设祖先列表为L，具体处理过程如下：
* 假设当前遍历节点是i，则：
    1. 查找祖先列表中在[i - k, i + k]范围内的，将数量累加至最后结果。
    2. 若i有子节点。
        * 将i加入祖先列表
        * 遍历i的所有子节点。
        * 将i从祖先列表移除。

这样，在遍历每个节点之前，列表中包含的就仅是其全部祖先了。

那么， 查找在某范围内的祖先数呢？ 将其转为范围求和即可。

假设对于数组a，如果某节点i在祖先列表中，则a[i] = 1，否则a[i] = 0，这样，某个范围内的祖先数，就是数组a中对应范围求和了。

而范围求和嘛，有两种数据结构都挺合适的， 一种是线段树，之前的文章有讲，见[这里](../Quadrant-Queries)， 另一种是BIT,请参考[这篇文章](https://www.geeksforgeeks.org/binary-indexed-tree-or-fenwick-tree-2/)。

线段树的解法代码见[solve-segtree.php](./solve-segtree.php)

BIT代码见[solve.php](./solve.php)