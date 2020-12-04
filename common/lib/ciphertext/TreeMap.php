<?php


namespace common\lib\ciphertext;

/**
 * 树字典
 * Class TreeMap
 * @package common\lib\ciphertext
 */
class TreeMap
{
    public $data;  // 节点字符
    public $children = [];  // 存放子节点引用（因为有任意个子节点，所以靠数组来存储）
    public $isEndingChar = false;  // 是否是字符串结束字符

    public function __construct($data)
    {
        $this->data = $data;
    }
}