<?php
// 符号定义
const ALPHA_EMPTY = 0;
const ALPHA_A = 1;
const ALPHA_B = 2;

// 操作符类型定义
const OP_AND = 1;
const OP_OR = 2;
const OP_REPEAT = 3;

// 正则表达式对应的非确定状态机图
class GraphNfa
{
    // 当前最大索引
    public static $nowIndex = 0;
    
    // 生成新索引并返回
    public static function getNewIndex() {
        return self::$nowIndex++;
    }
    
    // 所有的边，即状态转移图
    // key为点的index
    // 值为一个list，每一项包括两个元素，第一个是要跳转的状态，第二个是对应的输入符号
    public $edges = [];
    
    // 起点
    public $head = null;
    
    // 终点
    public $tail = null;
    
    // 新建一个状态机图时，总是单个输入符号
    public function __construct($alpha) {
        $this->head = self::getNewIndex();
        $this->tail = self::getNewIndex();
        
        $this->edges[$this->head] = [[$this->tail, $alpha]];
        
        // 也初始化，避免之后使用时为空
        $this->edges[$this->tail] = [];
    }
    
    // 根据AND关系更新状态机
    public function graphAdd($other) {
        if ($other === null) {
            return;
        }
        
        // 将other中除以起点开始外的边全部加入到此状态机
        foreach ($other->edges as $node => $list) {
            if ($node == $other->head) {
                continue;
            }
            $this->edges[$node] = $list;
        }
        
        // 原终点与other的起点合并
        $this->edges[$this->tail] = $other->edges[$other->head];

        // 终点变更为other的终点
        $this->tail = $other->tail;
        
        // other没用了，可以删除掉
        unset($other);
    }
    
    // 根据OR关系更新状态机
    public function graphOr($other) {
        if ($other === null) {
            return;
        }
        
        // 将other中的边全部加入到此状态机
        foreach ($other->edges as $node => $list) {
            $this->edges[$node] = $list;
        }
        
        // 新增两个节点作为起点和终点
        $newHead = self::getNewIndex();
        $newTail = self::getNewIndex();
        
        // 新起点指向原有的两个起点
        $this->edges[$newHead] = [
            [$this->head, ALPHA_EMPTY],
            [$other->head, ALPHA_EMPTY]
        ];
        $this->edges[$newTail] = [];
        
        // 原有的两个终点指向新终点
        $this->edges[$this->tail][] = [$newTail, ALPHA_EMPTY];
        $this->edges[$other->tail][] = [$newTail, ALPHA_EMPTY];
        
        // 起点和终点变更为新的
        $this->head = $newHead;
        $this->tail = $newTail;
        
        // other没用了，可以删除掉
        unset($other);
    }
    
    // 根据REPEAT更新状态机
    public function graphRepeat() {
        // 新增两个节点作为新起点和终点
        $newHead = self::getNewIndex();
        $newTail = self::getNewIndex();
        
        // 新起点有两条边，分别指向原起点和新终点
        $this->edges[$newHead] = [
            [$this->head, ALPHA_EMPTY],
            [$newTail, ALPHA_EMPTY]
        ];
        $this->edges[$newTail] = [];
        
        // 原有终点，增加两条边，分别指向原起点和新终点
        $this->edges[$this->tail][] = [$this->head, ALPHA_EMPTY];
        $this->edges[$this->tail][] = [$newTail, ALPHA_EMPTY];
        
        // 起点和终点变更为新的
        $this->head = $newHead;
        $this->tail = $newTail;
    }
    
    // 获取一系列状态经过单个有效字符转换后得到的新状态集
    public function getTranToSet(&$originSet, $char)
    {
        $result = [];
        
        // 这里穷举遍历即可，最后再统一处理zero闭包
        foreach ($originSet as $node => $dummy) {
            foreach ($this->edges[$node] as $item) {
                if ($item[1] == $char) {
                    $result[$item[0]] = true;
                }
            }
        }
        
        return $this->getZeroCloureSet($result);
    }
    
    // 处理zero闭包，得到全部可能的状态集
    public function getZeroCloureSet(&$originSet)
    {
        $result = $originSet;
        // 防止重复处理
        $processFlag = [];
        
        // 用队列
        $queue = new SplQueue();
        foreach ($originSet as $node => $dummy) {
            $queue->enqueue($node);
        }
        
        while (! $queue->isEmpty()) {
            $nextNode = $queue->dequeue();
            if (isset($processFlag[$nextNode])) {
                continue;
            }
            
            $processFlag[$nextNode] = true;
            
            foreach ($this->edges[$nextNode] as $item) {
                if ($item[1] == ALPHA_EMPTY) {
                    $result[$item[0]] = true;
                    if ( ! isset($processFlag[$item[0]])) {
                        $queue->enqueue($item[0]);
                    }
                }
            }
        }
        
        return $result;
    }
}

// 用于协助计算生成非确定状态机
class ResultSet
{
    // 左状态机图
    private $left = null;
    
    // 右状态机图
    private $right = null;
    
    // 操作符
    private $op = null;
    
    public function __construct() {
        // 默认操作类型为and
        $this->op = OP_AND;
    }
    
    public function setOr() {
        $this->op = OP_OR;
    }
    
    public function setRepeat() {
        $this->op = OP_REPEAT;
    }
    
    // 计算结果并返回计算后的nfa
    public function calcuResult() {
        switch ($this->op) {
            case OP_AND:
                $this->left->graphAdd($this->right);
                break;
            case OP_OR:
                $this->left->graphOr($this->right);
                break;
            case OP_REPEAT:
                $this->left->graphRepeat();
                break;
        }
        
        // 计算后的结果放在左值中
        return $this->left;
    }
    
    // 插入新的状态机图，先插入左边，再插入右边
    public function insert($graphNfa) {
        if ($this->left === null) {
            $this->left = $graphNfa;
        } else {
            $this->right = $graphNfa;
        }
    }
}

// 对应的DFA
class GraphDfa
{
    // 起点
    private $head;
    
    // 有效终点
    private $validEnds;
    
    // 邻接矩阵
    private $matrix;
    
    private $n;
    
    public function __construct($head, &$edges, &$validEnds) {
        $this->matrix = [];
        $this->head = $head;
        $this->validEnds = $validEnds;
        $this->calcuMatrix($edges);
    }
    
    public function calcuMatrix(&$edges) {
        $this->n = count($edges);

        for ($i = 0; $i < $this->n; $i++) {
            for ($j = 0; $j < $this->n; $j++) {
                $this->matrix[$i][$j] = 0;
            }
        }
        
        foreach ($edges as $node => $item) {
            foreach ($item as $char => $to) {
                if ($to !== null) {
                    $this->matrix[$node][$to] = 1;
                }
            }
        }
    }
    
    // 计算对应长度的不同字符串数量
    public function calueLengthNum($num) {
        $mod = 1000000007;
        $resultMatrix = [];
        for ($i = 0; $i < $this->n; $i++) {
            for ($j = 0; $j < $this->n; $j++) {
                $resultMatrix[$i][$j] = 0;
            }
            $resultMatrix[$i][$i] = 1;
        }
        
        $processMatrix = $this->matrix;
        
        while ($num) {
            if ($num & 1) {
                $tempMatrix = [];
                for ($i = 0; $i < $this->n; $i++) {
                    for ($j = 0; $j < $this->n; $j++) {
                        $temp = 0;
                        for ($k = 0; $k < $this->n; $k++) {
                            $temp = ($temp + $resultMatrix[$i][$k] * $processMatrix[$k][$j]) % $mod;
                        }
                        $tempMatrix[$i][$j] = $temp;
                    }
                }
                $resultMatrix = $tempMatrix;
            }
            
            $tempMatrix = [];
            for ($i = 0; $i < $this->n; $i++) {
                for ($j = 0; $j < $this->n; $j++) {
                    $temp = 0;
                    for ($k = 0; $k < $this->n; $k++) {
                        $temp = ($temp + $processMatrix[$i][$k] * $processMatrix[$k][$j]) % $mod;
                    }
                    $tempMatrix[$i][$j] = $temp;
                }
            }
            
            $processMatrix = $tempMatrix;
            
            $num = $num >> 1;
        }

        $result = 0;
        foreach ($this->validEnds as $endNode) {
            $result = ($result + $resultMatrix[$this->head][$endNode]) % $mod;
        }
        
        return $result;
    }
}

// 用于将nfa转为dfa
class TranslateTool
{
    // 当前最大索引
    private $nowIndex = 0;
    
    // 生成新索引并返回
    public function getNewIndex() {
        return $this->nowIndex++;
    }
    
    // 对应dfa的起点
    private $head;
    
    // nfa图
    private $graphNfa;
    
    // 转换后的dfa边图
    private $edges;
    
    // dfa有效状态
    private $validEnds;
    
    // nfa边集->dfa对应转换边map
    private $nfaEdgeSetMap;
    
    // dfa id => nfa边集map
    private $dfaNfaEdgeMap;
    
    private $initArr = [];
    
    public function __construct($graphNfa) {
        $this->graphNfa = $graphNfa;
        $this->edges = [];
        $this->validEnds = [];
        $this->nfaEdgeSetMap = [];
        $this->dfaNfaEdgeMap = [];
        $this->initArr = array_fill(0, GraphNfa::$nowIndex, 0);
    }
    
    public function getDfaId(&$set) {
        if (empty($set)) {
            return null;
        }
        
        $newArr = $this->initArr;
        
        foreach ($set as $node => $dummy) {
            $newArr[$node] = 1;
        }
        
        $str = implode('', $newArr);
        
        if (isset($this->nfaEdgeSetMap[$str])) {
            return $this->nfaEdgeSetMap[$str];
        }
        
        $index = $this->getNewIndex();
        $this->nfaEdgeSetMap[$str] = $index;
        $this->dfaNfaEdgeMap[$index] = $set;
        
        // 包含结束节点，则是有效终结状态
        if (isset($set[$this->graphNfa->tail])) {
            $this->validEnds[] = $index;
        }
        
        return $index;
    }
    
    public function translateToDfa() {
        // 用队列处理
        $queue = new SplQueue();
        $processedFlag = [];
        
        // 从起点开始
        $tempStart = [$this->graphNfa->head => true];
        $startSet = $this->graphNfa->getZeroCloureSet($tempStart);
        $startId = $this->getDfaId($startSet);
        $this->head = $startId;
        
        $queue->enqueue($startId);
        
        while ( ! $queue->isEmpty()) {
            $nextId = $queue->dequeue();
            if (isset($processedFlag[$nextId])) {
                continue;
            }
            $processedFlag[$nextId] = true;
            
            $this->edges[$nextId] = [];
            // 可到的节点集
            foreach ([ALPHA_A, ALPHA_B] as $char) {
                $nextSet = $this->graphNfa->getTranToSet($this->dfaNfaEdgeMap[$nextId], $char);
                
                $dfaId = $this->getDfaId($nextSet);
                //var_dump($nextSet);
                $this->edges[$nextId][$char] = $dfaId;
                if ($dfaId !== null && ! isset($processedFlag[$dfaId])) {
                    $queue->enqueue($dfaId);
                }
            }
        }

        return new GraphDfa($this->head, $this->edges, $this->validEnds);
    }
}

function countStrings($r, $l) {
    // 根据正则表达式构造nfa
    GraphNfa::$nowIndex = 0;
    $transResult = translateRegex($r, 0, strlen($r));
    $graphNfa = $transResult[0];
    
    // nfa转dfa
    $transTool = new TranslateTool($graphNfa);
    $graphDfa = $transTool->translateToDfa();
    
    // 根据dfa邻接矩阵计算满足条件的数量
    return $graphDfa->calueLengthNum($l);
}

function translateRegex(&$r, $index, $maxLen)
{
    $resultSet = new ResultSet();
    while ($index < $maxLen) {
        switch ($r[$index]) {
            case '(': // 左括号，处理子集，将子集加入当前结果集
                $result = translateRegex($r, $index + 1, $maxLen);
                $resultSet->insert($result[0]);
                $index = $result[1];
                break;
            case ')': // 右括号，计算结果并返回
                return [$resultSet->calcuResult(), $index];
            case '|': // 或操作
                $resultSet->setOr();
                break;
            case '*': // *操作
                $resultSet->setRepeat();
                break;
            case 'a': // 字母 、
                $resultSet->insert(new GraphNfa(ALPHA_A));
                break;
            case 'b': // 字母 、
                $resultSet->insert(new GraphNfa(ALPHA_B));
                break;
        }
        $index++;
    }
    
    return [$resultSet->calcuResult(), $index];
}