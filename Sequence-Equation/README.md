# Sequence Equation
原题见[这里](https://www.hackerrank.com/challenges/permutation-equation/problem)
就是给了A和B两个集合，以及一个它俩一一映射关系p。两个集合都是n个元素，包括从1到n的整数。
现在的问题是，对于1到n的每个整数x, 希望能够找到对应的y， 使得p(p(y)) = x。

# 分析
使用反映射关系查找即可。  
假设q是p的反映射关系， 那么，对于每个整数x, q(q(x))就是对应的值。

具体代码见[solve.php](./solve.php)