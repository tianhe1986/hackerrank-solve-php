# The Coin Change Problem
原题见[这里](https://www.hackerrank.com/challenges/coin-change/problem)

你有多种不同面值的硬币，每种硬币数量不限。现在你要给别人n元，问有多少种硬币组合方式？

# 分析
这是一个比较典型的动态规划题。

假设硬币序号从0开始，然后c[index]表示序号为index的硬币对应的面额。

定义d(index, money)为：只使用序号为index及之后的硬币，要给money元，有多少种组合方式。

首先考虑两种特殊的情况。
* 如果money < 0，那肯定没法组合了，返回0.
* 如果money = 0，那只有一种组合方式，就是所有的硬币都不选，返回1.

而对于剩下的情况，在此时，根据是否还要拿index硬币，又可以分成两种情况：
* 要拿，则拿一个，再继续处理， 也就是继续处理d(index, money - c[index])
* 不拿了，也就是跳过index，继续处理d(index+1, money)

也就是 d(index, money) = d(index, money - c[index]) + d(index + 1, money)。这就是动态规划的递推公式了。

然后要注意一点，使用缓存，避免之前已经计算过的再重复计算。

当然，我实际做的时候额外做了些处理，先将硬币从大到小排序，然后处理时避免出现money小于0的情况。

具体代码见[solve.php](./solve.php)