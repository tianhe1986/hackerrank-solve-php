# Mark and Toys
原题见[这里](https://www.hackerrank.com/challenges/mark-and-toys/problem)

Mark生了个娃，这娃喜欢玩具，那就得去给他买。

这里有很多玩具，价格各不相同，Mark兜里的钱有限，他想要买尽可能多不同的玩具。

给定玩具的价格和Mark拥有的钱，输出他最多能买到的玩具种类数。

# 分析
这个，就是最最最基本的贪心算法了，“买价格最低且从未买过的玩具”。

所以，算法就是，将玩具按价格从小到大排序，然后依次遍历，将能买的都买下来即可。

具体代码见[solve.php](./solve.php)