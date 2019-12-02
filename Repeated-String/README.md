# Repeated String
原题见[这里](https://www.hackerrank.com/challenges/repeated-string/problem)

一个字符串s，它无限重复。给定一个数字n，求无限重复后的字符串中前n个字符中，'a'出现多少次。

例如,s = 'abc'， 它无限重复就是 'abcabcabcabc....'， 假设n = 10，则前10个字符串中a出现4次。

# 分析
假设字符串s的长度为m。

前n个字符串，可以分成两部分，一部分是s完全重复若干次，假设重复u次，另一部分是s的前若干个字符(小于m)，假设为v。则有
* n = u * m + v

在给定n和字符串s的情况下，u和v可以求得，v = n % m, u = (n - n % m)/m， 只要知道s中a出现了多少次，以及前v个字符里a出现了多少次，就能得出结果了。

具体代码见[solve.php](./solve.php)