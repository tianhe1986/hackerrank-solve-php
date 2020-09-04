# The Love-Letter Mystery
原题见[这里](https://www.hackerrank.com/challenges/the-love-letter-mystery/problem)

有中文，就不翻译了。

# 分析
这个没有啥特别的技巧，既然是要变成回文，那就两个指针a和b，一个指向首位，一个指向尾位，比较对应位置字符是否相等，不相等则将较大的减少至较小的，将减少值加入到最终结果。

然后a++,b--，继续比较对应位置，直到a >= b为止。

具体代码见[solve.php](./solve.php)