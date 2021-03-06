# Alex vs Fedor
原题见[这里](https://www.hackerrank.com/challenges/alex-vs-fedor/problem)

有中文翻译，就不一句句讲了。

简而言之，就是给定一张无向图，两个节点之间允许多条边，求在这张图的所有生成树中任选一颗，选中的生成树是最小生成树的概率。

也就是要求 最小生成树的数量/生成树的数量。

# 分析
## 生成树数量
求生成树的数量，是有现成的定理的，那就是[Kirchhoff定理](https://en.wikipedia.org/wiki/Kirchhoff%27s_theorem)

无法科学上网的话，我这里简单讲一下处理方式。

首先定义几个矩阵，其实都是n*n的方阵，n为图中的节点数：
* degree矩阵D，D[i][i]的值为节点i的度数，也就是节点i连接的边数，但是要注意，自环形成的边不算。矩阵的其他位置值都是0，只有主角线的元素不为0.
* adjacency矩阵A，A[i][j]的值为节点i与j之间的边数，同样的，自环不算，因此A[i][i]始终为0。
* Laplacian矩阵L， L = D - A，就是以上两个矩阵之差。

然后，生成树的数量就是对应的Laplacian矩阵L去掉任意一行和任意一列后构成的n-1方阵的行列式。

行列式是啥我就不讲了，我这里的作法是用高斯消元法求行列式，消成上三角矩阵后直接对角线元素相乘即可。

具体处理的时候，我是直接把每个值当浮点数计算的，然后用PHP的BC Math系列函数来处理用于保证精度。这一部分看具体代码部分吧

## 最小生成树数量
这道题的编辑给了一种方法，其基本原理是乘法原理。

先求出一颗最小生成树，再按其中的边的权重从小到大进行遍历。设当前总数量为1
1. 求出原图中，仅由权重最小的边所构成的子图部分，生成树的数量。当前总数量乘上各子图部分生成树的数量。子图部分的生成数的数量，还是用上面的生成树的数量求。
2. 然后，调整原图，将上面的各子图缩成一个点，再继续求由最小生成树中权重第二小的边所能构成的子图部分的生成树数量，乘到总数量中去。
3. 继续以上过程，直到所有的权重值都处理完毕。

但是，其实前辈们早就有了更好的方法，具体的详细的阐述论证可以看看[这篇文章](./paper.pdf)

我这边简要的讲一下处理过程。

首先，定义一项操作，边的移动，其处理过程如下。

对于两条有公共节点的边e1和e2，假设e1连接的是u和v，e2连接的是v和w。如果e1的权值小于e2，则将e2从原图中移除，再在原图中增加一条边e2'，它的权值等于e2的权值，然后连接的是u和w。

可以看做，将e2沿着旁边一条权值更小的边，向另一个方向移动。

求最小生成树的数量，就需要进行这样的移边操作：
1. 求出一颗最小生成树。
2. 任选一个节点作为根，将上述最小生成树转化成父子节点的表示。
3. 遍历最小生成树中的每条边e，假设连接的是u和v，其中u为v的父节点。则尝试将连接到v的除e之外的所有边进行移动，即某边f连接了v和另一个节点w，则f的权值小于e，则将f移动为连接u和w。
由于e自己也有可能被移动，则采用自底向上的方式进行遍历，保证先处理跟e连接的其他边，再处理e。
当然移动过程的处理也是有优化的，每条边不是一步步移动，而是直接找到沿着到根节点的路线最终会移动到的位置。
4. 最终移动完成形成的新图，它的生成树的数量就是原图的最小生成树的数量了。

具体代码见[solve.php](./solve.php)