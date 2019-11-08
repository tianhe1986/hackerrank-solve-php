# Extra Long Factorials
原题见[这里](https://www.hackerrank.com/challenges/extra-long-factorials/problem)

求一个大数的阶乘，输出结果。

# 分析
我还是用笨办法，用数组存每1位的值，由于题目限制n <= 100，因此用201项就可以完全存下。假设数组为arr，则arr[0]存个位数字,arr[1]存十位数字，以此类推。输出的时候将数组逆序过来，并去除开头的0即可。

具体代码见[solve.php](./solve.php)