# Tripartite Matching
原题见[这里](https://www.hackerrank.com/challenges/tripartite-matching/problem)

给定3个图，G1,G2,G3，每个图都是n个点，编号从1到n。每个图有若干条无向边。

现在，需要求出所有的不同的有序三元组数量(a, b, c)，使得
* 1 <= a,b,c <=n
* a != b, b != c, c != a
* G1中存在边(a, b)，G2中存在边(b, c)，G3中存在边(c, a)

其中每个图中，任意两个点最多只有一条边连接它们。

# 分析
这道题我没有想太多，直接暴力穷举。

对G1中的每条边连接的两个顶点a和b，遍历G2中以b为一端的边，对于另一端的顶点c，检查G3中是否存在边(c, a)。

同样的，由于是无向图，对a也做一次遍历，遍历G2中以a为一端的边，对于另一端的顶点d，检查G3中是否存在边(d, b)。

具体代码见[solve.php](./solve.php)