# Play on benders
原题见[这里](https://www.hackerrank.com/challenges/benders-play/problem)

我没有按原文翻译，转换了一下。

给定一张图，由点和有向边构成。 一开始，将n颗石子放在随机选择的点上，一个点上允许多颗石子。

接下来，两位玩家轮流操作，每一轮，玩家必须将一颗石子沿着一条有向边的正方向移动到下一个点。

如果某一轮该玩家没有办法再移动了，则对方获胜。

给定点，边和n颗石子的初始位置，问在双方都采取最优策略的情况下，谁会赢。

# 分析

这题的本质还是Sprague-Grundy theorem，之前的题目有讲过，见[这里](../Stones-Game)

每个石子互不干扰，看做单局游戏，而对于每个单局游戏，就是要计算石子所在点对应的Nimber值。

这个就沿着每条有向边往后遍历即可，对于任意点i，具体计算方法如下：
* 如果从i没有有向边到其他节点，则Nimber[i] = 0。
* 否则， 分别计算出i出发的有向边指向的其他节点的Nimber值集合S，Nimber[i] = Mex(S)。 这个Mex，上面的其他题目讲解中有，意思是最小的未在集合中出现的自然数。

最后，谁会赢，就是每个石子的起点Nimber值求异或了，不为0则先手赢，为0则后手赢。
  
具体代码见[solve.php](./solve.php)