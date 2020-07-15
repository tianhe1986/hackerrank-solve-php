<?php
class FatAhoCorasick
{
    protected $maxState = 0;
    
    // keyword list
    protected $keywords = [];
    
    // goto table
    protected $goto = [];
    
    // output table
    protected $output = [];
    
    //failure table
    protected $failure = [];
    
    //next table
    protected $next = [];
    
    public function __construct()
    {

    }
    
    public function addKeyword($keyword)
    {
        if (is_array($keyword)) {
            foreach ($keyword as $realKeyword) {
                $this->keywords[] = (string)$realKeyword;
            }
        } else {
            $this->keywords[] = (string)$keyword;
        }
    }
    
    public function compute()
    {
        $this->reset();
        $this->computeGoto();
        $this->computeFailure();
        $this->computeNext();
    }
    
    protected function reset()
    {
        $this->goto = $this->failure = $this->output = $this->next = [];
    }
    
    protected function computeGoto()
    {
        $this->maxState = 0;
        foreach ($this->keywords as $keyword) {
            $this->enter($keyword);
        }
    }
    
    protected function enter(string $keyword)
    {
        $state = 0;
        $len = strlen($keyword);
        $i = 0;
        for ($i = 0; $i < $len; $i++) {
            $state = $this->goto[$state][$keyword[$i]] ?? ($this->goto[$state][$keyword[$i]] = ++$this->maxState);
        }

        // 增加该状态对应结尾的字符串个数
        if ( ! isset($this->output[$state])) {
            $this->output[$state] = 1;
        } else {
            $this->output[$state]++;
        }
    }
    
    protected function computeFailure()
    {
        $queue = [];
        $nowIndex = $endIndex = 0;
        foreach ($this->goto[0] as $char => $toState) {
            $this->failure[$toState] = 0;
            $queue[$endIndex++] = $toState;
        }
        while ($nowIndex != $endIndex) {
            $nextState = $queue[$nowIndex];
            if ( ! isset($this->goto[$nextState])) {
                unset($queue[$nowIndex++]);
                continue;
            }
            foreach ($this->goto[$nextState] as $char => $toState) {
                $queue[$endIndex++] = $toState;
                $tempState = $this->failure[$nextState];
                while($tempState !== 0 && ! isset($this->goto[$tempState][$char])) {
                    $tempState = $this->failure[$tempState];
                }
                $this->failure[$toState] = $this->goto[$tempState][$char] ?? 0;
                if (isset($this->output[$this->failure[$toState]])) {
                    if ( ! isset($this->output[$toState])) {
                        $this->output[$toState] = 0;
                    }
                    $this->output[$toState] += $this->output[$this->failure[$toState]];
                }
            }
            unset($queue[$nowIndex++]);
        }
    }
    
    protected function computeNext()
    {
        $queue = [0];
        $nowIndex = 0;
        $endIndex = 1;
        while ($nowIndex != $endIndex) {
            $nextState = $queue[$nowIndex];
            $failureState = $this->failure[$nextState] ?? 0;
            $this->next[$nextState] = ($this->goto[$nextState] ?? []) + ($this->next[$failureState] ?? []);
            if ( isset($this->goto[$nextState])) {
                foreach ($this->goto[$nextState] as $toState) {
                    $queue[$endIndex++] = $toState;
                }
            }
            unset($queue[$nowIndex++]);
        }
    }
    
    public function search($string)
    {
        return $this->searchByNext($string, $this->output, $this->next);
    }
    
    protected function searchByNext($string, &$output, &$next)
    {
        $result = 0;
        $state = 0;
        $len = strlen($string);
        
        for ($i = 0; $i < $len; $i++) {
            $state = $next[$state][$string[$i]] ?? 0;
            if (isset($output[$state])) {
                $result += $output[$state];
            }
        }
        
        return $result;
    }
}

function twoTwo($a) {
    static $ac = null;
    
    if ($ac === null) {
        $ac = new FatAhoCorasick();
        for ($i = 0; $i <= 800; $i++) {
            $searchStr = gmp_strval(gmp_pow(2, $i)); // string
            $ac->addKeyword($searchStr);
        }
        
        $ac->compute();
    }
    
    return $ac->search($a);
}
