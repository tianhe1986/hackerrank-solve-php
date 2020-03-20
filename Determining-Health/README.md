# Determining DNA Health
原题见[这里](https://www.hackerrank.com/challenges/determining-dna-health/problem)

已知n个基因序列串数组genes，和其对应的健康值数组health，数组index从0开始。

对于一个DNA三元组[start, end, d]，它的总健康值的求法是，对于基因genes[start],genes[start+1],genes[start+2], ... genes[end]，如果出现在d中，则增加相应的健康值。如果多次出现，则健康值也增加多次。

例如，现在给定基因串数组[a, b, a]，健康值数组[1, 2, 4]，对于DNA三元组[1, 2, abaa]，则
* 由于start为1，end为2，因此只考虑基因串数组的后两项。
* 对于b，在abaa中出现1次，健康值为2 * 1 = 2
* 对于a，在abaa中出现3次，健康值为4 * 3 = 12

因此总健康值是14。

现在给定一系列DNA三元组，要求输出计算出的最小总健康值和最大总健康值。


# 分析
看到这题，我的第一反应就是用多模式匹配Aho-Corasick算法来计算DNA中每个基因出现的次数。

我又差点忍不住讲AC算法自己到网上搜一下就知道了，当然，这是不对的嗯。

首先给出Aho-Corasick算法[原文](https://www.uio.no/studier/emner/matnat/ifi/INF3800/v13/undervisningsmateriale/aho_corasick.pdf)， 然后简单的讲解一下。

## Aho-Corasick算法
此算法解决的主要问题是，给定若干字符串，之后对于任意输入字符串，找出这些字符串在输入字符串出现的全部位置。 

算法的主要思想是，先对需要匹配的这若干个给定的字符串作预处理，构建有限自动状态机，实际匹配的过程就是在自动机进行转移并统计相应匹配信息。
跟KMP算法很类似，都是利用了给定的字符串本身的信息，在进行查找时无需回头进行查找。

理论上来说，自动机只是由一个goto表构成的，其定义是goto(s, c)，表示在状态s下，碰到了字符c，将转移到哪个状态。
可以想像一下，每个状态对应的都是唯一的字符串，就是从起始状态依次碰到哪些字符才会走到此状态，将这些字符拼接起来就是对应的字符串。

但在这里，需要扩展一下，包含三个表：
* goto。跟上面说的一致，goto(s, c)，表示在状态s下碰到字符c将转移到哪个状态。
* fail。定义是fail(s)，表示在状态s下，碰到了无法转移的字符，将转移到哪个状态，即作为goto的补充。 当然fail和goto是可以合并为一个函数的，但是在这里先将其分开，便于理解。
* output。定义是output(s)，表示在转移到状态s时，匹配到的字符串数组。

整体匹配处理过程如下：

那么问题来了，这三个表要如何构造呢？

### goto表构造
假设初始状态为0， 给定的需要用来检查是否出现的共有k个字符串，分别是y[1],y[2],y[2],...y[k]。处理过程如下：
1. 初始化全局newstate = 0，表示当前正在使用的最大状态id
2. 对于从1到k的每个值i， 调用enter(y[i])
3. 对于任意字符a，如果goto(0, a)不存在，则设置goto(0, a) = a;

enter处理过程如下，原型是enter(str)，str是一个字符串,可以通过str[j]取到str中的第j个字符（下标从0开始）：
1. 令m = str的长度, state = 0, j = 1
2. 如果goto(state, str[j])存在，则：
    * state = goto(state, str[j])
    * j++
    * 转2.
    也就是重用之前已有的状态，找到最长公共前缀。
3. 对于p = j, j+ 1, j+2,...m，依次做：
    * newstate = newstate + 1
    * goto(state, str[p]) = newstate
    * state = newstate
    也就是构建新的状态。
4. output(state)增加一项str，意味着如果走到了state，则说明输入字符串中完全包含了str字符串。

### fail表构造
这一部分在我看来是整个算法的核心所在，使用了广度优先迭代的方式进行处理，理解起来可能不太容易，就跟KMP算法中的next数组类似。处理过程如下：
1. 初始化待处理队列queue为空。
2. 对于每个字符a， 如果状态s = goto(0, a) != 0，则
    * fail(s) = 0
    * 将s插入队列queue队尾。
3. 如果queue不为空，则：
    * 取queue的队首，假设为状态r，将r移出queue
    * 对于每个字符a， 如果 goto(r, a)存在，假设为s，则：
        * 将s插入队列queue队尾
        * 令state = fail(r)
        * 只要 goto(state, a)不存在，则 state = fail(state)
        * fail(s) = goto(state, a)
        * 将output(fail(s))中的所有项加入到output(s)
    * 继续转3，处理队列

看下来有没有点懵呢。可以这样理解，假设走到某个状态j对应的已匹配字符串为t，则fail(j)对应的字符串就是比t短且为t后缀的最长字符串。

举个例子，如果状态j对应的已匹配字符串为abcd，则fail(j)对应的可能就是bcd(如果存在沿着b,c,d的状态转移)，也可能是cd，是d甚至可能是空字符串，也就是对应状态0。

而处理的方法就是从前一个状态已找到的fail状态继续遍历。

例如上面的abcd，现在要找abcd的最长后缀对应的状态，则从abc最长后缀对应的状态继续遍历。假设abc对应的状态为i，
* 若fail(i)状态碰见字符d有转移的话，那么由于fail(i)对应的是abc的最长后缀，则goto(fail(i), d)就是abcd的最长后缀
* 不然，就要继续向前，直到找到另一个作为abc后缀的状态i'，且goto(i', d)存在，对应的才是与abcd的最长后缀。

同时，如果某个状态i对应的fail(i)状态对应的output不为空，则说明走到fail(i)状态时，完全匹配了某些字符串。而由于fail(i)为i的后缀，因此到达状态i时，也完全匹配了这些字符串，也应该输出相应的匹配结果。

### next表构造
前面说了，goto表和fail表是可以合二为一的。

实际匹配的过程，对当前字符在goto表没有相应状态时，就会触发fail操作，转移到相应的状态，再继续处理当前字符。如果还触发fail，就继续转移，直到goto表有相应处理为止。

那么，其实，对于每个字符在每个状态，是否触发fail操作，以及最终到哪个状态才能进行goto转移，是固定的，next表就是把中间的这个过程跳过，记录在某个状态，碰到某字符时，在中间经历fail的情况下，最终会goto转移到哪个状态。

next表构造方式如下，同样的，需要使用广度优先的方式，逐步迭代：
1. 初始化待处理队列queue为空。
2. 对于所有可能的符号a
    * next(0, a) = goto(0, a)
    * 如果goto(0, a) != 0, 则将goto(0, a)插入到queue队列队尾
3. 如果queue不为空，则：
    * 取queue的队首，假设为状态r，将r移出queue
    * 对于每个字符a：
        * 如果goto(r, a)存在，则next(r, a) = goto(r, a)，将goto(r, a)插入queue队列队尾
        * 否则，next(r, a) = next(fail(r), a)
    * 继续转3，处理队列

### 查找处理过程
现在已经构造出了完整的自动机，可以用来在任意字符串中查找了，假设输入字符串为x，长度为n，从前往后每个字符分别是x[0], x[1], ... x[n-1]。查找处理过程如下：
1. 设置state = 0
2. 对于i = 0, 1, 2,...n-1，依次做：
    * state = next(state, x[i])
    * 如果output(state)不为空，则将i以及output(state)增加到最终结果，表示output(state)中所有的字符串都在x中完全匹配了，并且x[i]对应最后一个字符。

## 题目解法
虽然上面说了AC算法，但是！但是！但是！我这边真正用的话，内存会超（大概是php的原因，每个字符也会当作一个zval对象进行处理，占用大量内存）。

没有办法，只好另辟蹊径。当然了，这个我也是看了讨论中其他人的方案写出来的，我本来以为这个方案占用的内存会更大的，但是并没有。

直接以每个基因字符串作为key，储存相应的健康值信息。由于基因字符串有可能重复，因此这里的健康值信息用了两个数组来保存。比较巧妙。
* 一个数组用来保存同一个基因字符串，在原始输入中出现的各个index。
* 另一个数组，与上面的对应，储存到对应的index为止，所有该同一个基因字符串的健康值之和。不过为了便于计算，此数组多增加一个0，放在数组的开头。

举个例子，现在基因串数组为[ab, cc, ab, dc, jk, ab, kk]，对应的健康值为[1, 2, 3, 4, 5, 6, 7]。则对于字符串ab。
* 第一个数组保存它出现的各个index，即[0, 2, 5]
* 第二个数组保存到对应位置的健康值之和， 即[0, 1, 4, 10]，第1个值0是开头，第2个值表示到index 0为止，所有ab的健康值之和，因此是1， 第3个值表示到index 2为止，所有ab的健康值之和，即 1 + 3 = 4，同理 1 + 3 + 6 = 10

这样保存的好处就是，对于给定的start和end，能够用二分查找法计算出某个基因在给定的start和end区间内的健康值之和。

假设要处理的基因第一个数组为p，第二个数组为q。做法就是，在第一个数组p中找到大于等于start的最小值对应的索引i（没有则返回数组长度+1），找到大于end的最小值对应的索引j（若没有则返回数组长度+1），则健康值之各就是q[j] - q[i]

还以ab为例，假设 start = 2, end = 4， 则在[0, 2, 5]中大于等于2的最小值就是2，对应索引为1，大于4的最小值是5，对应索引为2，则对应健康值之和为q[2] - q[1] = 4 - 1 = 3

再换个例子，若 start = 1, end = 6， 则start对应索引为1， end对应索引为3，对应健康值为q[3] - q[1] = 10 - 1 = 9。

若start = 0, end = 5, 则为 q[3] - q[0] = 10 - 0 = 10

除此之外，还存储每个基因字符串的每个前缀是否存在，用于提前跳出不必要的遍历。

具体代码见[solve.php](./solve.php)