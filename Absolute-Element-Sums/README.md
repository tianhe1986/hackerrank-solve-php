# Absolute Element Sums
原题见[这里](https://www.hackerrank.com/challenges/playing-with-numbers/problem)

自带中文，我就不翻译了。

# 分析
题目的要求是，把数组中每个元素加上x，并报告数组中所有元素绝对值的和。

因为有很多轮查询，把处理方式稍微变换一下，变成“当前轮，数组每个元素需要加上x”，再输出数组中所有元素绝对值的和。

显然，不可能真的每轮都去加一遍，而是得有更快的计算的方法。

首先，肯定一开始，数组中所有元素绝对值之和是固定值，直接累加算出来就好。

然后，对于每个元素都加上x的情况，来考虑它对元素绝对值的影响：

## x=0的情况
这个，也不用过多解释吧，没有影响。

## x>0的情况
这里又可以分为三类，分别讨论,假设对于任一元素i：
* 若 i > 0， 则其绝对值增加x。都是正数，绝对值肯定是变大。
* 若 i < -x，则其绝对值减少x。i是负数，且更向0靠近，绝对值肯定是变小。
* 若 -x <= i <= 0， 绝对值增加 x + 2i。 这里可以分为两步计算，第一步，从i变为0，第二步，从i变为 x+i，第一步绝对值变化量为 i，第二步变化量为x+i，因此总变化是x + 2i。

组合一下，绝对值之和的变化之和可以由以下三部分组成：
* （数组中 >= -x 的数量 )*x
* （数组中 < -x的数量）* (-x)
*  (数组中-x <= i <= 0的元素之和)*2

## x<0的情况
跟上面x>0的情况类似。假设对于任一元素i：
* 若i < 0，则其绝对值增加 -x。都是负数，绝对值变大。
* 若i > -x，则其绝对值减少-x。i是正数，且更向0靠近，绝对值变小。
* 若0 <= i <= -x，绝对值增加 -x - 2i。 分两步计算，第一步，从i变为0，增加 -i， 第二步，从0变为 x + i，增加 -x - i，因此总共增加 -x - 2i

组合一下，绝对值之和的变化之和可以由以下三部分组成：
* (数组中 <= -x 的数量) * (-x)
* (数组中 > -x的数量) * x
* (数组中 0 <= i <= -x的元素之和) * (-2)

## 总结
因此，只需要对每个值t，一开始做以下两项额外的记录，就可以根据上面的计算方式在每一步得到结果：
* 数组中 <= t的数量。
* 数组中 [0,t](对于t < 0 则是 [t, 0]) 范围内元素之和。

具体代码见[solve.php](./solve.php)