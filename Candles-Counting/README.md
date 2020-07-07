# Candles Counting
原题见[这里](https://www.hackerrank.com/challenges/candles-2/problem)

有中文，就不翻译了。

# 分析
我的第一反应是平白的动态规划，也就是，dynamic(color, i)表示以第i根蜡烛作为结尾的单调递增子序列，且组成的颜色组合为color(用二进制表示，每一位表示一种颜色)的数量。

当然，理所当然的，就超时了。

然后看到评论里有人说要用BIT，binary index tree，也叫Fenwick Tree，中文翻译是“树状数组”，用来求区间累加和。

关于树状数组，我觉得我讲不好，请参考[这篇文章](https://www.geeksforgeeks.org/binary-indexed-tree-or-fenwick-tree-2/)吧。

但是我这里对于怎么使用它，进入了误区，我的想法还是原始数据是记录以第i根蜡烛作为结尾的序列数量，然后想着，这不是每次还得遍历前面么，就算是累加求和，也不能直接算前i根蜡烛序列数量之和。

后来不知怎么就想通了，啊，原来应该以蜡烛的高度来作为其中一个维度，让原始数据t(color, h)表示以高度h作为结尾，且组成颜色组合为color的数量，这样就可以依次进行遍历了。

同样的，让s(color, h)表示组成颜色组合为color，且高度小于等于h的数量，每次遍历第i根蜡烛时，若其高度为h[i]，颜色为c[i]，则：
* 对于每一个可能的颜色j：
    1. 若j与c[i]没有交集，即 j & c[i] == 0，则c[i]不可能为t(j, h[i])作出任何贡献，因为j不包含第i根蜡烛的颜色。
    2. 若有交集，则t(j, h[i]) += s(j, h[i] - 1) + s(j ^ (~c[i]), h[i] - 1)。若组成颜色组合为j，则分两种情况：
        1. 之前颜色组合已经为j，现在c[i]再放进来，仍然为j。
        2. 之前颜色组合为j去掉c[i]，现在c[i]放进来，正好组成j。
        所以，以h[i]为结尾，且组成颜色为j的数量，要多加上"结尾高度小于h[i]，且组成颜色为j的数量"，以及"结尾高度小于h[i]，且组成颜色为j去掉c[i]的数量"

而对t的更新，实际上就是处理树状数组。

当然，这里我作了两点优化：
1. 新增了一个函数，同时求两个树状数组中对应同一值的累加和。
2. 提前缓存每单个颜色需要处理对应的颜色。

最后，返回s(全颜色， 最大高度)即可。

具体代码见[solve.php](./solve.php)