# Xor and Sum
原题见[这里](https://www.hackerrank.com/challenges/xor-and-sum/problem)

有中文，就不用翻译了。这题目名字真是贴切，异或求和。

# 分析
其实，php里有gmp库，可以直接拿来莽，咳咳，见[solve-gmp.php](./solve-gmp.php)

如果不这样莽呢？

假设a有无穷位（在前面补0），那么，考虑最终结果，针对a的每一位：
* 如果此位是1，则最终b的所有左移操作中，有多少个此位是0（假设是t），则此位会为最终结果贡献 t * （此位对应的数）
* 如果此位是0，与上面相对，看最终b的所有左移操作中，有多少个此位是1(假设是s)，则此位会为最终结果贡献 s * （此位对应的数）

由于最终左移操作总共是314160次，只要记录所有左移操作中，每一位对应产生的1的数量即可，0的数量 = 314160 - 1的数量。

从低位开始考虑的话，第0位1的数量就是看原始b的第0位是不是1了，因为其他的左移都会将此位变为0。

第1位，1的数量，就是原始b的第0位和第1位的1的数量之和，不移动，会计入原始b的第1位，左移1位，会计入原始b的第0位。

同样的，第2位，1的数量，就是原始b的第0位~第2位的1的数量之和，道理同上。

假设原始b共有m位，则第m-1位是原始b的全部位的1的数量之和。

那么第m位开始呢？中间有314160 - 2 * m这么多，1的数量都是原始b的全部位的1的数量之和。为什么呢？因为对于这些位置中任一一个，原始b的每一位都会被左移到此一次。

而最高的m个位，则又跟最低的m位情况对称了，第0高位是原始b的第0高位中1的数量，第1高位是原始b的第0高位~第1高位中1的数量，第k(k < m)高位是原始b的第0高位~第k高位中1的数量。

这样，最终b的所有左移操作中，每一位对应产生的1的数量就计算出来了，再对a进行遍历，依次计算每位的贡献值并累加即可。

具体代码见[solve.php](./solve.php)