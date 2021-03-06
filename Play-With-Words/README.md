# Play with words
原题见[这里](https://www.hackerrank.com/challenges/strplay/problem)

有中文，就不翻译了。

# 分析
这个问题详细分解下就是：
1. 将原字符串分成前后两部分。
    1. 分别求出前半部分和后半部分的最长回文子序列t1和t2。
    2. 计算len(t1) * len(t2)的值。
2. 对第一步的分成前后两部分的所有分法计算出的值，取最大值。

而求一段范围内的最长回文子序列长度，是有现成的动态规划算法的。

假设字符串为s，dynamic(i, j)表示从s[i]至s[j]这一段（包括i和j两个位置的字符）子串对应的最长回文子序列的长度，则有：
1. 若 i > j，返回0. 意思是空串对应的最长回文子序列长度为0。
2. 若i == j，返回1. 只有单个字符，构成一个长度为1的回文。
3. 若s[i] == s[j]，则返回 2 + dynamic(i + 1, j - 1)。表示首末串匹配，能够构成回文的两端，再继续遍历中间部分。
4. 返回dynamic(i + 1, j)和dynamic(i, j - 1)的最大值。表示在去掉首个字符，和去掉末尾字符，两种情况下，继续进行查找，取其中较大者。
  
具体代码见[solve.php](./solve.php)