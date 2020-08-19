# Misère Nim
原题见[这里](https://www.hackerrank.com/challenges/misere-nim-1/problem)

这个就没有必要使劲翻译了, 就是取石子的游戏, 但是原始取石子是取到最后一个石子的人输, 这里是取到最后一个石子的人获胜。

# 分析
参照[wiki](https://en.wikipedia.org/wiki/Nim)。

如果不能科学上网的话，看看这句话：
* When played as a misère game, Nim strategy is different only when the normal play move would leave only heaps of size one. In that case, the correct move is to leave an odd number of heaps of size one (in normal play, the correct move would be to leave an even number of such heaps).

翻译一下就是，只有当全部堆的石子数量都为1时需要特殊考虑。

如果全部堆的石子数量都为1，且堆数为奇数，则先操作的玩家输，否则先操作的玩家赢。因为两个玩家没有什么选择，只能一次取走一堆石子。

除此之外的情况，就是跟普通的Nim处理方式相同了，用Sprague-Grundy theorem，之前的题目有讲过，见[这里](../Stones-Game)

具体代码见[solve.php](./solve.php)