<?php

class BinaryNode {
    /** @var mixed $Value */
    public $Value;

    /** @var BinaryNode[] $Children */
    public $Children;

    /** @var bool $IsTerminal */
    public $IsTerminal;

    public function __construct($value, $isTerminal = false) {
        $this->Value = $value;
        $this->Children = [null, null];
        $this->IsTerminal = $isTerminal;
    }

    /**
     * @return BinaryNode
     */
    public function GetFalseChild() {
        return $this->Children[0];
    }

    /**
     * @return BinaryNode
     */
    public function GetTrueChild() {
        return $this->Children[1];
    }

    /**
     * @param BinaryNode $binaryNode
     */
    public function SetFalseChild($binaryNode) {
        $this->Children[0] = $binaryNode;
    }

    /**
     * @param BinaryNode $binaryNode
     */
    public function SetTrueChild($binaryNode) {
        $this->Children[1] = $binaryNode;
    }

    /**
     * @param 1|0 $whichChild
     * @return BinaryNode
     */
    public function GetChild($whichChild) {
        return $whichChild ? $this->GetTrueChild() : $this->GetFalseChild();
    }

    /**
     * @param 1|0 $whichChild
     * @param BinaryNode $childNode
     */
    public function SetChild($whichChild, $childNode) {
        if ($whichChild) {
            $this->SetTrueChild($childNode);
        } else {
            $this->SetFalseChild($childNode);
        }
    }

    /**
     * @param array[] &$paths
     * @param array $temp
     */
    public function RecursivePath(&$paths, $temp = []) {
        if (is_null($this->Children[0]) && is_null($this->Children[1])) {
            $path = $this->IsTerminal ? new DecisionPath($temp, $this) : new DecisionPath(array_merge($temp, [$this]));
            $paths[] = $path;
        } else {
            if ($this->Children[0]) {
                $newTemp = $temp;
                $this->Children[0]->RecursivePath($paths, $newTemp);
            }
            if ($this->Children[1]) {
                $newTemp = array_merge($temp, [$this]);
                $this->Children[1]->RecursivePath($paths, $newTemp);
            }
        }
    }

    /**
     * @param bool $terminalTrueOnly
     * @param BinaryNode[] $currentPath
     */
    public function RecursivePrintPaths(&$count = 0, $terminalTrueOnly = true, $currentPath = []) {
        if (is_null($this->Children[0]) && is_null($this->Children[1])) {
            if (!$terminalTrueOnly || ($terminalTrueOnly && $this->IsTerminal && $this->Value)) {
                $count++;
                echo $this->IsTerminal ? new DecisionPath($currentPath, $this) : new DecisionPath(array_merge($currentPath, [$this]));
                echo PHP_EOL;
            }
            return;
        }
        if ($this->Children[0]) {
            $this->Children[0]->RecursivePrintPaths($count, $terminalTrueOnly, $currentPath);
        }
        if ($this->Children[1]) {
            $this->Children[1]->RecursivePrintPaths($count, $terminalTrueOnly, array_merge($currentPath, [$this]));
        }
    }

    /**
     * @return string
     */
    public function __toString() {
        if (is_bool($this->Value)) {
            return $this->Value ? 'true' : 'false';
        }

        $str = $this->Value->__toString();
        return is_string($str) ? $str : 'NODE';
    }
}