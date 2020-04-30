# Array Construction
原题见[这里](https://www.hackerrank.com/challenges/array-construction/problem)

构造一个数组，一共n个元素，每个元素都是非负整数，且满足以下两个条件：
* 所有元素之和等于s。
* 任意两个元素之差的绝对值之和等于k。

打印出这样的逻辑序最小的数组，如果没有，输出-1.

一个数组A的逻辑序比B小的含义是，存在索引i，满足：
* A[i] < B[i]
* 对任意 j < i，都有A[j] = B[j]

# 分析
这道题我本来以为是要找规律，得出最佳选择的策略，花了很多时间研究，分析得越深入，越觉得复杂，脸都苦了。

结果，是要动态规划莽一波啊。然后动态规划时我又犯了个错，以为按最大限制，使用缓存内存会超，就没有用，导致最后一道没能通过。加上缓存后，立马OK。

我的想法是，就很直白的拿这三个参数遍历。
* remainNum： 还需要多少个元素。
* remainSum: s与当前元素之和的差值。
* remainDiff: k与当前元素之差的绝对值的和的差值。

而遍历的时候使用两个数组进行记录：
* valueArr: 要构造的数组，处理的时候按序处理，即对于任意i < j，必有valueArr[i] <= valueArr[j]
* diffArr: 用来辅助计算元素之间的差值之和，对于索引i，记录的是valueArr[i]与之前元素的差值之和。
即diffArr[i] = (valueArr[i] - valueArr[0]) + (valueArr[i] - valueArr[1]) + (valueArr[i] - valueArr[2]) + ... + (valueArr[i] - valueArr[i - 1])

而对于diffArr，有递推公式如下：
* diffArr[i + 1] = diffArr[i] + (i + 1) * (valueArr[i+1] - valueArr[i])

因为
* diffArr[i] = (valueArr[i] - valueArr[0]) + (valueArr[i] - valueArr[1]) + (valueArr[i] - valueArr[2]) + ... + (valueArr[i] - valueArr[i - 1])
* diffArr[i+1] = (valueArr[i+1] - valueArr[0]) + (valueArr[i+1] - valueArr[1]) + (valueArr[i+1] - valueArr[2]) + ... + (valueArr[i+1] - valueArr[i - 1]) + (valueArr[i+1] - valueArr[i])

两者相减 diffArr[i+1] - diffArr[i]，每个括号内对应的 - valueArr[0], - valueArr[1]等都被抵消掉了，变成(valueArr[i+1] - valueArr[i])，前面一共有i项，再加上diffArr[i+1]最后自带的1项，共i+1项。即：
* diffArr[i+1] - diffArr[i] = (i+1) * (valueArr[i+1] - valueArr[i])

很显然，最终就是要valueArr各项之和等于s，diffArr各项之和等于k。

在处理过程中是需要分支定界的，但是这个分支定界的条件我没有严格的证明（应该说不知道怎么证明）：
1. 在如下方案下，元素差之和达到最小值： 接下来的全部元素大小尽量平均
2. 在如下方案下，元素差之和达到最大值： 接下来的全部元素，除倒数第一个外，全部取最小值。

例如，现在要取4个数，使得和为10，则
* 这4个数尽量平均时，差值之和最小，即4个数为 2, 2, 3, 3
* 前面都取最小值时，差值之和最大，即4个数为 0,0,0,10

假设遍历函数为process(remainNum, remainSum, remainDiff)，处理过程如下：
1. 如果remainNum = 1，则令valueArr[n-1] = remainSum， 计算diffArr[n - 1]，更新remainDiff，若 remainDiff = 0，返回true，否则返回false。如果remainNum不等于1，转2.
2. 如果缓存cache[remainNum][remainSum][remainDiff]已经存在，返回false。否则转3
3. 设置缓存cache[remainNum][remainSum][remainDiff]，分支定界判断，即计算如果接下来元素差之和能够增长的最小值和最大值。如果remainDiff不在此范围内，return false
4. 当前要处理的index = n - remainNum， 令minValue为valueArr[index]能够取的最小值，maxValue为能取的最大值，即：
    * minValue = index == 0 ？ 0 ： valueArr[index - 1]。 即不能比前一项小。
    * maxValue = int(remainSum/remainNum)。 因为后面也要保证元素的有序性。
5.从minValue到maxValue依次遍历，令当前遍历项为nowValue：
    1. 设置valueArr[index] = nowValue, diffArr[index] = diffArr[index - 1] + index * (valueArr[index] - valueArr[index-1])
    2. 计算newRemainSum = remainSum - nowValue,  newRemainDiff = remainDiff - diffArr[index]
    3. 令result = process(remainNum - 1, newRemainSum, newRemainDiff)，如果result为true, 返回true。
6. 返回false

具体代码见[solve.php](./solve.php)