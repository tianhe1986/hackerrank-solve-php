# Extremum Permutations
原题见[这里](https://www.hackerrank.com/challenges/extremum-permutations/problem)

有中文，就不翻译了。

# 分析
这个，通过全部测试用例的过程有点痛苦，PHP的内存使用方式真的，一言难尽。

假设现在在处理位置i，它前面的位置都已经放好了，那么，要继续遍历的话，需要拿出当前剩余整数集合中的一个数字，那么关键的问题就是，哪些数字现在可以选择呢？

要考虑的，只有跟前一个位置i-1选择的数字的关系，假设在第i-1位选择的时候，剩余集合有t个数字，那么：
* 如果第i位必须大于第i-1位，则若第i-1位选择的是这t个数字中第j大的，则第i位可以分别选择第1大，第2大，第3大。。。第j-1大的。
* 如果第i位必须小于第i-1位，与上面的情况正好相反，若第i-1位选择的是这t个数字中第j小的，则第i位可以分别选择第1小，第2小，第3小。。。第j-1小的。
* 如果没有要求，则每一个都可以。

注意一点，如果知道第i位选择的数字是t个数字中第j大（或是第j小），结合第i-1位的选择，就可以递推求出这个数字是在t-1个数字中的大小排行，接着就可以再遍历第i+1位了。

设总位数为n， 位数编号从0开始。令动态规划函数dynamic(location, canChooseNum)表示需要选择第location位数字，且此位有canChooseNum种选择方式时的全部可能排列数量，递推算法如下：
1. 如果location等于n，则说明已经遍历到头且生成的数组满足条件，返回1。
2. 如果canChooseNum等于0，则这种方法走不通，返回0。
3. 当前全部数字数量为total = n - location, 全部可能排列数结果初始化result = 0
4. 如果第location位必须比第location-1位大，说明canChooseNum对应取的是前canChooseNum大的，需要继续考虑第location+1位与第location位的关系：
    * 如果第location+1位要比第location位小，则
        * for (i = 1; i <= canChooseNum; i++) result += dynamic(location + 1, total - i); 如果第location位取第i大的，那么比其小的数就有total - i个，下一位就有这么多种选择方式。
    * 如果第location+1位要比第location位大，则
        * for (i = 1; i <= canChooseNum; i++) result += dynamic(location + 1, i - 1); 如果第location位取第i大的，那么比其大的数就是i-1个，下一位就有这么多种选择方式。
    * 如果没有要求，则
        * result += canChooseNum * dynamic(location + 1, total - 1); 下一位始终有total - 1种选择方式。
5. 正好是第4步反过来，canChooseNum对应取的是前canChooseNum小的（注意，在这里把没有要求的情况也划分进来了，此时canChooseNum正好是剩余数字集合的个数），需要继续考虑第location+1位与第location位的关系：
    * 如果第location+1位要比第location位大，则
        * for (i = 1; i <= canChooseNum; i++) result += dynamic(location + 1, total - i); 如果第location位取第i小的，那么比其大的数就有total - i个，下一位就有这么多种选择方式。
    * 如果第location+1位要比第location位小，则
        * for (i = 1; i <= canChooseNum; i++) result += dynamic(location + 1, i - 1); 如果第location位取第i小的，那么比其小的数就是i-1个，下一位就有这么多种选择方式。
    * 如果没有要求，则
        * result += canChooseNum * dynamic(location + 1, total - 1); 下一位始终有total - 1种选择方式。

这个算法思想本身是没有问题的，但是，超时了，为什么呢？问题就出在这个for循环里，假设对于每一对location和canChooseNum都要计算一遍的话，就需要n * canChooseNum * canChooseNum次求和，还是在使用缓存的情况下，这样时间复杂度达到了n的3次方。

那么，怎么消去这个for循环呢？这里只以其中一种情况为例，其他的情况可以同样的处理。

以4.1为例，即第location位必须比第location-1位大，同时第location+1位要比第location位小时：
* for (i = 1; i <= canChooseNum; i++) result += dynamic(location + 1, total - i);

这就是dynamic(location, canChooseNum)的值，那么，对于dynamic(location, canChooseNum ＋1)呢？就是：
* for (i = 1; i <= canChooseNum + 1; i++) result += dynamic(location + 1, total - i);

也就是说 dynamic(location, canChooseNum ＋1) = dynamic(location, canChooseNum) + dynamic(location + 1, total - (canChooseNum + 1));

这样的话，就没有循环，可以依次递推求出了。而location的前后关系是固定的，某个location能够进入的情况是唯一确定的，所以不会有问题。

其他情况的消去循环的处理，我这里就不一一列举了。

但是！提交的时候，有三个测试用例run time error，内存超了。为什么呢？因为dynamic的缓存，我使用的是php二维数组，很占内存，怎么办呢，将它强行变成一维，再使用SplFixedArray代替默认数组。

当然，实际处理时，包括一些预处理，计算相邻两个位置之间的需要大小关系，如果相邻两个位置都要求比对方大或是比对方小，则是矛盾的，直接返回0。

具体代码见[solve.php](./solve.php)