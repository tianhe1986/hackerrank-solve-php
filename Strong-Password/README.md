# Strong Password
原题见[这里](https://www.hackerrank.com/challenges/strong-password/problem)

一个密码被认为是强密码，如果它满足以下条件：
* 长度大于等于6.
* 包含至少一个数字。
* 包含至少一个小写英文字母。
* 包含至少一个大写英文字母。
* 包含至少一个特殊字符。

现在给定一个密码字符串，问最少需要添加多少个字符，才能变为强密码。

# 分析
我的想法是，将长度和剩余四种条件区别开来，分别判断。

假设diffLength表示至少需要添加多少字符，才满足长度要求。diffType表示至少需要添加多少字符，才满足包含各类字符的要求，最终返回max(diffLength, diffType)。

diffLength很简单就是 6 - 输入字符串的长度。

diffType就是未出现字符类型的总数，需要遍历字符串，检查每种字符是否出现，在这里我用的bit位进行标识。

具体代码见[solve.php](./solve.php)