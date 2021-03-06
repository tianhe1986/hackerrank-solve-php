# Cards Permutation
原题见[这里](https://www.hackerrank.com/challenges/cards-permutation/problem)

给定从1到n的n个正整数，爱丽丝会写下它们可能的全排列，按词典顺序增序，并从1开始编号。例如，给定1到3，所有的全排列为
1. [1 ,2, 3]
2. [1, 3, 2]
3. [2, 1, 3]
4. [2, 3, 1]
5. [3, 1, 2]
6. [3, 2, 1]

并选择一个作为她最喜欢的排列。

不幸的是，有一天，她尝试将最喜欢的那一个排列写出来的时候，忘记了其中某些元素的位置，于是她在不记得的位置上写0.

现在，对于所有可能满足的排列，求它们的序号之和。

继续以上面的为例，假设爱丽丝写下的最喜欢的排列是 [0 , 0, 2]， 则可能满足的有2个，[1, 3, 2]和[3, 1, 2]，对应的序号分别是2和5，输出它们的和7.

这个和可能很大，因此最终结果需要对1000000007取模。


# 分析
这个题目，我得承认我是看了编辑的答案的，虽然我的想法就差了那么一步，但是没有想到就是没有想到。

首先，由于求的是序号之和，那么问题来了，对于一个给定的排列，如何求出它的序号？

排列一共有n位，假设编号从0开始，即第0位，第1位，第2位...第n-1位。

排列的序号 = 第0位可能比当前数小的所有选择 * (n-1)!（后面n-1位全排列，由于每个数都不同，因此是n-1的阶乘）  + 第0位固定，第1位可能比当前数小的所有选择 * (n-2)! + 前2位固定
 + 第2位可能比当前数小的所有选择 * (n-3)! + ... + 前n-1位固定， 第n-1位可能比当前数小的所有选择 * 1 + 1

是不是听起来很晦涩难懂，那么，举个实际的例子。就以上面的[3, 1, 2]为例
* 第0位是3， 而如果不是3的话，一共有3种选择，其中比3小的有2种， 因此得到 2 * (3-1) ! = 4
* 第1位是1， 而如果不是1的话，一共有2种选择，注意，为什么只有2种呢，因为前面的数要固定，即第0位已经是3了。2种选择里，比1小的没有，因此得到0
* 第2位是2， 因为第2位是最后一位，始终只有一种选择，没有更小的了， 得到0.

最终序号 = 4 + 0 + 0 + 1 = 5

再想想我们的日常10进制数，计算的方法其实也是类似的。假设编号从0开始，对于任意一个正整数，它的序号也是这样算的，不妨思考下9071这个数。
* 第0位是9， 这一位一共有10种选择， 比9小的一共9种，而后面3位的全排列就是10的3次方，得到 9000.
* 第1位是0， 比0小的没有，得到0.
* 第2位是7， 比7小的一共7种，后面1位的全排列是10，得到70.
* 第3位是1， 比1小的只有0， 得到1

最后再加上1， 9071的序号 = 9000 + 0 + 70 + 1 + 1 = 9072。对吧，只是我们平时没有意识到计算方法是这样的emmmmmmmmmm

好了，那么继续， 既然每个数的序号可以这样算，那么题目给的序号之和呢，当然，就是对于所有可能的排列，都这样算一下，再加起来落。

重点来了，加的时候，我们换一种方法，按每一位来遍历，即分别算出第0位可能比当前数小的选择之和 * (n-1)!， 第1位，第2位...第n-1位。再加起来即可。

而且注意到，所有的这些阶乘，都是可以提取公因式，最后一起相乘的，而最后末尾的1也一样。

假设现在是n位，其中有k位是0，即未知，则所有的可能排列一共是k!。现在考虑对于每个位置，如何完成可能比当前数小的选择之和的累加。

再注意到一点，每一位的数字是不重复的，因此，对于任一排列的第i位来说，可能比当前数小的选择 = n个数里比当前数小的个数 - 前i-1位里比当前数小的个数。 

假设当前数为t，则n个数里比当前数小的个数为t-1，但是这样看着有点烦，为方便起见，我们将原排列中的每个数都减去1，则若当前数为和，则n个数里比当前数小的个数就是t，计算起来会容易一些。

再次提醒，用的是这个公式
* 可能比当前数小的选择 = n个数里比当前数小的个数 - 前i-1位里比当前数小的个数

那么下面就是求累加的具体做法了。设给定的数组为a对于第i位：
1. 如果对应的数不是-1。上面我们已经说过了每个数减去1，则-1对应的是未知。现在这个数不是未知，假设是t， n个数里比当前数小的个数就是t，所有排列里，公式的前半部分累加就是 t * k!。我们再拿前i-1位跟它比较。假设对于第j位(0<=j<i)
    1. 如果对应的数不是-1，那么a[i]和a[j]的大小是一定的。那么在所有k!可能性中，这一位比a[i]小的可能数是：如果a[i] > a[j]，则k!，否则为0.
    2. 如果对应的数是-1，那么，在所有k!种可能性中，这一位比a[i]小的可能数是，这一位任选一个比a[i]小的数，其他未知位任意排列。即（所有未出现的数字中比a[i]小的个数） * (k-1)!
那么，对于所有的j位，将这两项累加，有：
    1. 所有对应数不是-1的位，可能数累加结果 = 前i-1位中已经出现的数字里比a[i]小的个数 * k!
    2. 所有对应数是-1的位，可能数累加结果 = 前i-1位中-1的个数 * （所有未出现的数字中比a[i]小的个数） * (k-1)!
则最终可能比当前数小的选择数是
* t(当前数) * k! -  前i-1位中已经出现的数字里比a[i]小的个数 * k! - (前i-1位中-1的个数) * （所有未出现的数字中比a[i]小的个数） * (k-1)!
2. 如果对应的数是-1，n个数里，比当前数小的个数就是当前选择的数，对于k!种排列来说，每个数出现的机会均等，累加之和就是（所有未出现的数之和）*(k-1)！。 同样的，拿前i-1位跟它比较。假设对于第j位(0<=j<i)
    1. 如果对应的数不是-1，那么在所有k!可能性中，这一位比a[i]小的可能数是，a[i]位任选一个比a[j]大的数，其他未知位任意排列。即(所有未出现数字中比a[j]大的个数) * (k-1)!
    2. 如果对应的数是-1，那么在所有k!可能性中，这一位比a[j]小的可能数是，在所有未出现数字中任选2个数，大的放在a[i]，小的放在a[j]，其他未短位任意排列。即（c(k, 2)）* (k-2)! = k!/2
同样的，对于所有的j位，将这两项累加，有：
    1. 所有对应数不是-1的位，累加结果 = (未出现数字中比当前每个已出现数字大的个数之和) * (k-1)!
    2. 所有对应数是-1的位，累加结果 = （前i-1位中-1的个数)* k! / 2
则最终可能比当前数小的选择数是
* （所有未出现的数之和）*(k-1)！- (未出现数字中比当前每个已出现数字大的个数之和) * (k-1)! - （前i-1位中-1的个数)* k! / 2

为了加速计算这些累加值，在这里使用两个线段树。关于线段树是什么，在之前的文章里有讲，可以看看，见[这里](../Quadrant-Queries)
1. 第一个线段树，原始数组长度为n，原始每一位i对应的值是“若i未出现，则为1，若i已出现，则为0”，可以用于统计一段区间内未出现数字的个数，
对应以上四个累加中的"所有未出现的数字中比a[i]小的个数"和"所有未出现数字中比a[j]大的个数"。这个线段树中途不会变化。
2. 第二个线段树，原始数组长度为n，原始每一位i对应的值是“若i已出现，则为1，若i未出现，则为0”，可以用于统计一段区间内已出现数字的个数，对应“前i-1位中已经出现的数字里比a[i]小的个数”。
此线段树在每次i遍历到不是-1的数时会进行更新。

而前i-1位中-1的个数，只需要碰到-1时+1即可，不需要使用线段树。所有未出现的数之和也是求一次即可。

最后，将每1位求出的结果相加，再加上最后末尾的k!，就是答案了

具体代码见[solve.php](./solve.php)