# Jumping on the Clouds: Revisited
原题见[这里](https://www.hackerrank.com/challenges/jumping-on-the-clouds-revisited/problem)
一开始有100点能量， n朵云，序号从0到n-1， 云有带电和不带电两种。  
每一步，花费1点能量， 向前跳k朵云， 云是循环的， 也就是说 第n-1朵云后面又跟着第0朵云。这一步降落的云，如果带电，则额外扣2点能量。  
问从第0朵云出发，下一次回到第0朵云时，还有多少点能量。

# 分析
假设现在在第i朵云，则下一步降落的云序号为 (i + k) % n， 依次遍历直到回到出发点就好。

具体代码见[solve.php](./solve.php)