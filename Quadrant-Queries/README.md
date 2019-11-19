# Quadrant Queries
原题见[这里](https://www.hackerrank.com/challenges/quadrant-queries/problem)

二维平面坐标系内有n个点，，每个点P[i]的坐标是[x[i], y[i]]。

定义三种操作：
1 X i j， 将p[i]和p[j]之间（包括这两者）的点沿x轴进行翻转.
2 Y i j， 将p[i]和p[j]之间（包括这两者）的点沿y轴进行翻转.
3 C i j， 打印p[i]和p[j]之间（包括这两者）所有点在4个象限上的分布数量。

现在给定点和一系列操作，要求输出所有的C操作结果。

# 分析
这道题的关键词，区间，统计，会让我一下子想到一个数据结构，线段树，而且，这次似乎没有想错，就是应该用线段树来解。

那么，是时候结合这道题讲讲线段树了。

## 线段树
线段树，是用来针对一个可数的离散区间，储存此区间的统计信息，最终能够达到的效果是较快的求出任一子区间的统计信息。 
千万要注意一点， 一段区间的统计信息，必须是能够通过其不相交的各子区间的统计信息得出。 在这里，我们这道题就是这样， i和j之间点的象限分布， 是能够通过统计i和j之间各子区间点的象限分布，再累计各象限点数量得到的。


### 线段树的构造
考虑更通常的情况，在这里， 以整数区间[m, n]，对应数组为a，线段树为t，统计信息为区间元素求和为例。

树的根节点，index为1， 为什么不从0开始呢，因为从1开始会比较好算， 线段树是一颗二叉树， 每个节点i的左子节点就是 2*i， 右子节点就是2*j。

继续上面没说完的话，树的根节点t[1]，储存的就是整个区间的元素之和，即a[m] + a[m+1] + ... + a[n]。

继续，将[m, n]区间对半分成两部分，如果此区间是奇数个元素，则左半部分比右半部分少1，即 [m, (m+n)/2],[(m+n)/2+1, n]，这两部分的统计信息分别储存在t[2]和t[3]上，即t[1]的左右子节点上。

再继续， 对于每个节点t[k]，对应的区间为[i, j]，如果i=j，则说明已经是单个元素了，无需再拆分，否则，拆分成[i, (i+j)/2], [(i+j)/2+1, j]两部分，统计信息分别储存在t[2*k]和t[2*k + 1]上。

实际构造的时候，使用的是后根遍历的方式，先将子节点的区间元素和求出来，然后此节点的区间元素和就是两个子节点的元素和相加。 这一部分，示例代码如下：
```
/**
 * 
 * @param type $segTree 要构造的线段树
 * @param type $a 原始数组
 * @param type $root 遍历的线段树节点下标
 * @param type $left 对应的原始数组区间起始下标
 * @param type $right 对应的原始数组区间终下标
 * @return null
 */

function buildSegTree(&$segTree, &$a, $root, $left, $right)
{
    if ($left == $right) { //叶节点
        $segTree[$root] = $a[$left];
        return;
    }

    $mid = intval(($left + $right)/2);
    $leftIndex = $root << 1;
    $rightIndex = $leftIndex + 1;
    // 继续递归处理左右区间
    buildSegTree($segTree, $a, $leftIndex, $left, $mid);
    buildSegTree($segTree, $a, $rightIndex, $mid + 1, $right);

    // 此节点存储的区间元素和为两子节点存储值之和
    $segTree[$root] = $segTree[$leftIndex] + $segTree[$rightIndex];
}
```

### 线段树的查询和更新
前面说了，线段树主要的作用是用来加速查询任一子区间的统计信息的。假设现在想求[l, r]区间内数组元素的和，怎么办呢？

从根节点开始递归遍历，假设当前遍历的节点为s，对应的区间为[u, v],对于每个节点的遍历f(s, u, v, l, r)，处理如下：
1. 如果[u, v]与[l, r]没有交集，返回0. 否则转2.
2. 如果[u, v]包含在[l, r]区间内，返回t节点存储的值。否则转3.
3. 令mid = (u+v)/2, lindex = 2*s, rindex = 2*s + 1，返回f(lindex, u, mid, l, r) + f(rindex, mid+1, v, l, r)，即继续遍历两个子节点区间，分别统计[l, r]在两个子节点区间内的元素之和。

这里本来应该立马给一段示例代码的，但是有一项处理，跟更新是紧密结合的，因此，接下来先讲对于更新的处理。

对于单个值的更新很简单，就是先通过上述的查询，找到对应的节点，将节点的值设为目标值，再用构造线段树时的方式，自底向上，更新相应的区间元素和即可。

对于一个区间的更新怎么办呢，很明显，不会去对区间内的每个元素，找到对应的节点，再用上一行的方式处理。在这里，需要引入一个新的标志，“懒惰更新标识”，用于加速区间更新过程。

具体的处理方式是，如果某一个节点对应的区间完全包含在要更新的区间内，则将此节点打上更新标识，并将此节点对应的存储值修正为更新操作后的值。否则，继续遍历左右子节点，更新相应区间。
这样做的好处是，在之后的查询操作时，如果此节点完全包含在要查询的区间内，对此节点的子节点的更新处理，就可以不用完成了。

那么，如果之后的查询操作，只是跟此节点区间有交集呢？那就得继续更新它的子节点了，但是，也是用同样的更新方法，用更新标识，尽可能少的更新之后的子节点。
如果之后的更新，跟此节点区间有交集，也是同样的处理方式，先更新子节点，再处理新的更新。这个更新子节点的操作叫做“下推”。

假设更新操作的方式是给某个区间的每个元素加上一个固定的数value，查询和更新的处理demo如下：
```
/**
 * 
 * @param type $segTree 线段树
 * @param type $lazyFlag 懒惰更新标识数组
 * @param type $rangeLeft 要查询的区间起点
 * @param type $rangeRight 要查询的区间终点
 * @param type $root 当前遍历的节点index
 * @param type $left 当前遍历节点对应的区间起点
 * @param type $right 当前遍历节点对应的区间终点
 * @return type 返回当前节点中包含在要查询区间内的元素之和
 */

function getSegTreeRange(&$segTree, &$lazyFlag, $rangeLeft, $rangeRight, $root, $left, $right)
{
    // 如果整个包含在区间内， 直接返回
    if ($left >= $rangeLeft && $right <= $rangeRight) {
        return $segTree[$root];
    }
    
    // 查询前，先下推现有flag
    pushDown($segTree, $lazyFlag, $root, $left, $right);
    
    $result = 0;
    
    // 继续查询左右子区间
    $mid = intval(($left + $right)/2);
    $leftIndex = $root << 1;
    $rightIndex = $leftIndex + 1;

    if ($mid >= $rangeLeft) { // 与左子节点有交集，继续查询
        $result += getSegTreeRange($segTree, $lazyFlag, $rangeLeft, $rangeRight, $leftIndex, $left, $mid);
    }
    
    if ($mid < $rangeRight) { // 与右子节点有交集，继续查询
        $result += getSegTreeRange($segTree, $lazyFlag, $rangeLeft, $rangeRight, $rightIndex, $mid + 1, $right);
    }
    
    return $result;
}

/**
 * 
 * @param type $segTree 线段树
 * @param type $lazyFlag 懒惰更新标识数组
 * @param type $rangeLeft 要查询的区间起点
 * @param type $rangeRight 要查询的区间终点
 * @param type $updateValue 要更新的值，即每个元素变化的值
 * @param type $root 当前遍历的节点index
 * @param type $left 当前遍历节点对应的区间起点
 * @param type $right 当前遍历节点对应的区间终点
 * @return type
 */

function updateSegTree(&$segTree, &$lazyFlag, $rangeLeft, $rangeRight, $updateValue, $root, $left, $right)
{
    // 如果整个包含在区间内， 设置flag， 更新当前值
    if ($left >= $rangeLeft && $right <= $rangeRight) {
        $lazyFlag[$root] = empty($lazyFlag[$root]) ? $updateValue : $lazyFlag[$root] + $updateValue;
        $segTree[$root] += $updateValue * ($right - $left + 1);
        return;
    }
    
    // 先下推现有flag
    pushDown($segTree, $lazyFlag, $root, $left, $right);
    
    // 继续处理左右子区间
    $mid = intval(($left + $right)/2);
    $leftIndex = $root << 1;
    $rightIndex = $leftIndex + 1;
    // 继续递归处理左右区间
    if ($mid >= $rangeLeft) {
        updateSegTree($segTree, $lazyFlag, $rangeLeft, $rangeRight, $updateValue, $leftIndex, $left, $mid);
    }
    if ($mid < $rangeRight) {
        updateSegTree($segTree, $lazyFlag, $rangeLeft, $rangeRight, $updateValue, $rightIndex, $mid + 1, $right);
    }
    
    // 更新当前区间值
    $segTree[$root] = $segTree[$leftIndex] + $segTree[$rightIndex];
}

/**
 * 下推操作
 * 
 * @param type $segTree 线段树
 * @param type $lazyFlag 懒惰更新标识数组
 * @param type $root 要处理下推的节点
 * @param type $left 当前节点对应的区间起点
 * @param type $right 当前节点对应的区间终点
 * @return type
 */

function pushDown(&$segTree, &$lazyFlag, $root, $left, $right)
{
    // 没有flag， 不需要下推
    if (empty($lazyFlag[$root])) {
        return;
    }
    
    if ($left == $right) { //已经到了叶节点，没有可下推的了
        return;
    }
    
    $mid = intval(($left + $right)/2);
    $leftIndex = $root << 1;
    $rightIndex = $leftIndex + 1;
    
    // 对于左右节点，更新相应的值，打上懒惰更新标识
    $value = $lazyFlag[$root]; // 每个元素变化的值
    
    $segTree[$leftIndex] += $value * ($mid - $left + 1); // 左子节点区间变化值
    $lazyFlag[$leftIndex] = empty($lazyFlag[$leftIndex]) ? $value : $value + $lazyFlag[$leftIndex]; // 累加到原有更新标识上
    
    $segTree[$rightIndex] += $value * ($right - $mid); // 右子节点区间变化值
    $lazyFlag[$rightIndex] = empty($lazyFlag[$rightIndex]) ? $value : $value + $lazyFlag[$rightIndex]; // 累加到原有更新标识上
    
    unset($lazyFlag[$root]);
}
```
思考下，如果更新操作方式是直接设置为某个值， 应该怎么写？

## 此题解法
线段树的处理原理就是上面那样，但是注意以下几点：
1. 储存的值是一个四元组，即每个象限的节点数量。
2. 更新的操作有两种，一种是沿X轴翻转，另一种是沿Y轴翻转，则对于某一段区间，进行多次更新操作时，最终可以简化为一种翻转，沿X轴翻转，沿Y轴翻转，或是两轴同时翻转。想想为什么~~~
3. 更新对应的值更改，就是相应象限的值进行交换。

具体代码见[solve.php](./solve.php)