# Two Two
原题见[这里](https://www.hackerrank.com/challenges/two-two/problem)

有中文，就不翻译了。

二幂教授这个翻译不错，二幂教授喜欢二hhhhhhhh

# 分析
理论上，这应该是生成800个字符串，然后用多模式匹配来处理的。

但是，PHP可以直接莽，不会超时，见[solve-brute.php](./solve-brute.php)

好吧，多模式匹配，自然是AC算法，这个在之前的解题中也有讲到过，就不重复了，见[这里](../Determining-Health)

第一步自然就是初始化AC类，构造goto，output，failure和next表，当然，这里的output要改一下，改成到达此状态时，成功匹配到的字符串总数。

然后就是针对每个输入的字符进行查找了。

具体代码见[solve.php](./solve.php)