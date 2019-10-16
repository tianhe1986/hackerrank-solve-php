# Correctness and the Loop Invariant
原题见[这里](https://www.hackerrank.com/challenges/correctness-invariant/problem)

就是给了个插入排序的函数，但是有问题，要你改。

# 分析
就是第一个元素没比较处理， 将j变量判断从 j > 0改成 j >= 0就好。

具体代码见[solve.php](./solve.php)
