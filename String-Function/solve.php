<?php

class SuffixTreeNode {
    // 子节点跳转，key为首字母，value为另一个SuffixTreeNode
    public $children = [];
    
    // 后缀链接，指向去掉首字母后对应的节点
    public $suffixLink = null;
    
    // 用于表示父节点到此节点对应的字母段起始点
    public $start;
    
    // end为-1时，表示到头为止
    public $end;
    
    public function __construct($start, $end, $suffixLink) {
        $this->start = $start;
        $this->end = $end;
        $this->suffixLink = $suffixLink;
    }
    
    public function edgeLength($lastPos)
    {
        return ($this->end == - 1 ? $lastPos : $this->end) - $this->start + 1;
    }
}

function extendSuffixTree(&$root, &$str, $pos, $activeNode, &$activeEdge, &$activeLength, &$remainingSuffixCount)
{
    $remainingSuffixCount++;
    
    $lastNewNode = null;
    
    while ($remainingSuffixCount > 0) {
        if ($activeLength == 0) { // 还未选择路线
            $activeEdge = $pos;
        }
        
        // 没有这样的路线，新建叶节点
        if ( ! isset($activeNode->children[$str[$activeEdge]])) {
            $activeNode->children[$str[$activeEdge]] = new SuffixTreeNode($pos, -1, $root);
            
            // 能走到这里，一定是最后一步了，前面怎么可能没有边呢
            if ($lastNewNode != null) {
                $lastNewNode->suffixLink = $activeNode;
                
                // 这句似乎没有必要
                $lastNewNode = null;
            }
        } else { // 有，往前走
            $next = $activeNode->children[$str[$activeEdge]];
            
            // walkdown
            $edgeLen = $next->edgeLength($pos);
            if ($activeLength >= $edgeLen) {
                $activeEdge += $edgeLen;
                $activeLength -= $edgeLen;
                $activeNode = $next;
                continue;
            }
            
            // Rule 3, 当前字符已经在路径上
            if ($str[$next->start + $activeLength] == $str[$pos]) {
                if ($lastNewNode != null) {
                    $lastNewNode->suffixLink = $activeNode;

                    // 这句似乎没有必要
                    $lastNewNode = null;
                }
                
                $activeLength++;
                
                break;
            }
            
            // rule 2， 分裂出一个中间节点， 新建一个叶节点
            $split = new SuffixTreeNode($next->start, $next->start + $activeLength - 1, $root);
            $activeNode->children[$str[$activeEdge]] = $split;
            
            $split->children[$str[$pos]] = new SuffixTreeNode($pos, -1, $root);
            $split->children[$str[$next->start + $activeLength]] = $next;
            $next->start += $activeLength;
            
            // 前一个分裂出来的节点，对应的suffixLink就是这次分裂出来的节点
            if ($lastNewNode != null) {
                $lastNewNode->suffixLink = $split;
            }
            
            $lastNewNode = $split;
        }
        
        $remainingSuffixCount--;
        
        // 调整active位置
        
        // 对于root， 换边，长度-1
        if ($activeNode == $root && $activeLength > 0) {
            $activeLength--;
            $activeEdge = $pos - $remainingSuffixCount + 1;
        } else { // 对于非root，直接跳到suffixLink
            $activeNode = $activeNode->suffixLink;
        }
    }
    
    return $activeNode;
}

function dfs($node, $preLen, &$result)
{
    // start不为-1, end为-1的，是叶节点， 只可能出现一次，不用算了
    if ($node->start != -1 && $node->end == -1) {
        return 1;
    }
    
    // 当前节点对应字符串长度
    $nowLen = 0;
    if ($node->start == -1 && $node->end == -1) {

    } else {
        $nowLen = $preLen + $node->end - $node->start + 1;
    }
    
    // 对应字符串出现次数
    $nowNum = 0;
    foreach ($node->children as $child) {
        $nowNum += dfs($child, $nowLen, $result);
    }
    
    $temp = $nowLen * $nowNum;
    if ($temp > $result) {
        $result = $temp;
    }
    
    return $nowNum;
}

function maxValue($t) {
    $t .= '$';
    $len = strlen($t);
    
    // Ukkonen’s Suffix Tree Construction
    
    // active节点
    $activeNode = null;
    // active边
    $activeEdge = -1;
    // 在active边上的长度
    $activeLength = 0;
    
    // 还有多少个后缀需要添加
    $remainingSuffixCount = 0;
    
    // 创建root节点
    $root = new SuffixTreeNode(-1, -1, null);
    
    $activeNode = $root;
    $activeNode->suffixLink = $root;
    
    // 从root开始扩展生成前缀树
    for ($i = 0; $i < $len; $i++) {
        $activeNode = extendSuffixTree($root, $t, $i, $activeNode, $activeEdge, $activeLength, $remainingSuffixCount);
    }
    
    // dfs遍历，计算最大值
    $result = $len - 1;
    dfs($root, 0, $result);
    
    return $result;
}
