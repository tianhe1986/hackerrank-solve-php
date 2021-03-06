# Crab Graphs
原题见[这里](https://www.hackerrank.com/challenges/crab-graphs/problem)

Crab，螃蟹，这个词真的是挺形象的emmmmmmmmmmm

Crab是一个无向图，由以下两种节点构成，1个头，K只脚，然后K条边将这K只脚与头相连。 其中1 <= K <= T，T是给定的值。

例如，如果给定T = 8，那么，1只脚，2只脚。。。8只脚，都是Crab，但是9只脚就不行了。

给定一个无向图，可以找出里面的若干个Crab子图，使得每个Crab之间不能有共同节点。

现在目标是，使得找出的所有子图节点数之和最大。

# 分析

这道题我提交了两种解法的代码，其中一种，理论上是错的，但是也能通过所有的测试用例，真是凝重啊。

## 我的错误的解法
其实要说错误，也没有完全错误，只是少了最后一步抢占式的处理。

首先，根据原图中的孤点（不与任何节点相连），叶节点（只与一个节点相连）和非叶节点（与两个或更多节点相连）类别，映射为Crab的对应部位。
* 即不能当头，也不能当脚。孤点就这样的。
* 只能当脚。叶节点只能当脚，其实有一种例外，就是两个叶节点相连，但这时它们俩可以互为头和脚，因此也算在里面。
* 只能当头。与叶节点相连的非叶节点只能当头。
* 头和脚都可以当。只与非叶节点相连的非叶节点属于此类。

注意，上面说的“只能”当头和脚并不是Crab规则的限制，而是要达到节点数之和最大的限制。可以这样理解：
* 如果叶节点当头，那么它就把跟它连接的节点占用了，而跟它连接的节点，有可能还连接着其他的叶节点，这一部分本来至少有一个可以归入Crab的。
* 如果与叶节点相连的非叶节点当脚，跟上一条类似，那些叶节点本来至少有一个可以归入Crab的。

然后对应的处理方法也有了：
1. 预处理全部节点，区分出孤点，叶节点和非叶节点。
2. 遍历每个节点，对于节点t的遍历过程process(t)如下：
    1. 如果是孤点，非叶节点，或者已有Crab，或当前正在处理链中，返回。
    2. 标记t为正在处理链中。
    3. t中需要记录以下信息
        * 连接的叶节点数，初始化为0
        * 连接的还没有Crab的非叶节点数组，初始化为空。
        * 连接的有了Crab的非叶节点数组，初始化为空。
    4. 对于t连接的每个节点n，作如下处理：
        1. 调用process(n).
        2. 对n进行判断，分别处理：
            * 如果n是叶节点，t记录的叶节点数+1
            * 如果n不是叶节点
                * n没有Crab，插入到t记录的没有Crab的非叶节点数组中。
                * n有Crab，插入到t记录的有了Crab的非叶节点数组中。
    5. 对t的记录做如下判断：
        * 如果连接的叶节点数>0， 则将t作为头，然后优先将叶节点作为脚，再将连接的还没有Crab的非叶节点作为脚，构建Crab，当然要保证脚数量不超过T
        * 否则，如果还没有Crab的非叶节点数组不为空，将t作为头，将连接的还没有Crab的非叶节点作为脚，构建Crab，当然要保证脚数量不超过T
        * 否则，如果有了Crab的非叶节点数组不为空，从中选一个之前是脚或还有余量的节点a，将t作为a的脚，并更新a的脚数量以及a之前的头（如果a之前是脚）的脚数量。
    6. 标记t不在处理链中。

那么，所谓的错误错在哪里呢？错在第5步的第三小步处理，对于没有余量的相邻节点就跳过了。

而理论上，假设b是一个没有余量的相邻节点，但是b的某个脚或许可以找到其他的头，这样就能腾出来一个位置让n连接进来，如果对应的头也没有余量，还可以再继续递归遍历。

但是之前的代码已经写了很多行了，我不想再改了，头痛emmmmmmmmm

先看看这份解法错误却也能通过的代码吧，见[solve_error.php](./solve_error.php)

## 正确的解法

正确的解法当然是，将题目转化成一个最大流问题来解。

首先，自然是创建两个点，源点s和汇点t，所有流量都是从s出发，最后流入汇点。

对原图中的每个点a，都拆成两个点a[in]和a[out]，a[in]表示a作为Crab的脚，a[out]表示a[in]作为Crab的头。

对于每个a[in]，都增加一条s->a[in]的边，容量为1.

对于每个a[out]，都增加一条a[out]->t的边，容量为T。

对于原图中的每条边，假设连接的是a和b两个点，则增加一条a[in]->b[out]的边和b[in]->a[out]的边。

最终最大流，可以这样理解，不断的将Crab脚与Crab头相连，直到没有更多连接为止。

但是，有没有注意到这里有个问题，对于所有的Crab脚，这样是对的，但是对于任一Crab头a，也会找到一条s->a[in]->x[out]->t的流量，这样会不会多计算了？

答案是，不会，因为对于所有Crab脚作为in的节点的情况，流量加在一起，也只是Crab脚的数量之和，并没有将Crab头计算在内，而上面的对Crab头的流量，正好补上了这个数量。

所以，直接跑最大流算法，就能得到正确的结果了。

最大流算法，在另一篇解答中有讲，见[这里](../Real-Estate-Broker/README.md)， 甚至处理的代码都一模一样，只是边构建的方式不同，我会考虑将它整个封装成一个类放到其他项目中去。

具体代码见[solve.php](./solve.php)