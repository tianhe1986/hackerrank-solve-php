# Hackerland Radio Transmitters
原题见[这里](https://www.hackerrank.com/challenges/hackerland-radio-transmitters/problem)

假设房子排成一排，每个房子的坐标就是距起点的距离。

现在要在某些房子的烟囱上装上无线电发射器，每个无线电发射器能够覆盖的范围是左右k米。 即假设 k =2, 在距起点距离为5的房子上装上发射器， 则[3, 7]这个区间内都能收到信号。

现在给定每个房子的坐标和无线电发射器能覆盖的范围，求最少需要安装的发射器数量。

# 分析

这道题我用的是贪心迭代法，我是这样想的： 假设现在所有未覆盖的房子坐标中，最小的是x，因为要覆盖全部房子，那么肯定必须覆盖x， 那么， 就先安装一个发射器， 使得它能覆盖到x，而且尽量能覆盖更多的其他房子。

这个发射器安装在哪里呢， 安装在[x, x+k]区间内坐标最大的房子上就好。 

接着， 将新安装的发射器能覆盖的房子移除掉，对所有仍未覆盖的房子继续进行迭代，直到所有房子都被覆盖为止。

具体代码见[solve.php](./solve.php)