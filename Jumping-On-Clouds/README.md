# Jumping on the Clouds
原题见[这里](https://www.hackerrank.com/challenges/jumping-on-the-clouds/problem)

n朵云，需要从第1朵跳到最后一朵，每一步可以向前进1朵或2朵云。

但是云是有不同类型的，只能落在对应值为0的云（积云）上，不能落在值为1的云(闪电云)上。

问最快需要多少步完成。

# 分析
题目给了说明，一定是可以跳到终点的，因此不用考虑到不了的情况。

很简单暴力的作法，每一步，都尝试跳2朵云，如果不行，就只跳1朵。

具体代码见[solve.php](./solve.php)