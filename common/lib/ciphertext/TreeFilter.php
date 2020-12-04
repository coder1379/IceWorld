<?php

namespace common\lib\ciphertext;

/**
 * 文本树过滤
 * Class TreeFilter
 * @package common\lib\ciphertext
 */
class TreeFilter
{
    /**
     * 敏感词数组
     *
     * @var array
     * @author qpf
     */
    public $trieTreeMap = array();

    public function __construct()
    {
        $this->trieTreeMap = new TreeMap('/');
    }

    /**
     * 获取敏感词Map
     *
     * @return array
     * @author qpf
     */
    public function getTreeMap()
    {
        return $this->trieTreeMap;
    }

    /**
     * 添加敏感词
     *
     * @param array $txtWords
     * @author qpf
     */
    public function addWords(array $wordsList)
    {
        foreach ($wordsList as $words) {
            $trieTreeMap = $this->trieTreeMap;
            $len = mb_strlen($words);
            for ($i = 0; $i < $len; $i++) {
                $word = mb_substr($words, $i, 1);
                if(!isset($trieTreeMap->children[$word])){
                    $newNode = new TreeMap($word);
                    $trieTreeMap->children[$word] = $newNode;
                }
                $trieTreeMap = $trieTreeMap->children[$word];
            }
            $trieTreeMap->isEndingChar = true;
        }
    }

    /**
     * 清空字典
     */
    public function clearWords(){
        unset($this->trieTreeMap);
        $this->trieTreeMap = new TreeMap('/');
    }

    /**
     * 查找对应敏感词
     *
     * @param string $txt
     * @return array
     * @author qpf
     */
    public function search($txt)
    {
        $wordsList = array();
        $txtLength = mb_strlen($txt);
        for ($i = 0; $i < $txtLength; $i++) {
            $wordLength = $this->checkWord($txt, $i, $txtLength);
            if($wordLength > 0) {
                #echo $wordLength;
                $words = mb_substr($txt, $i, $wordLength);
                $wordsList[] = $words;
                $i += $wordLength - 1;
            }
        }
        return $wordsList;
    }

    /**
     * 敏感词检测
     *
     * @param $txt
     * @param $beginIndex
     * @param $length
     * @return int
     */
    private function checkWord($txt, $beginIndex, $length)
    {
        $flag = false;
        $wordLength = 0;
        $trieTree = $this->trieTreeMap; //获取敏感词树
        for ($i = $beginIndex; $i < $length; $i++) {
            $word = mb_substr($txt, $i, 1); //检验单个字
            if (!isset($trieTree->children[$word])) { //如果树中不存在，结束
                break;
            }
            //如果存在
            $wordLength++;
            $trieTree = $trieTree->children[$word];
            if ($trieTree->isEndingChar === true) {
                $flag = true;
                break;
            }
        }
        /*if($beginIndex > 0) {//这里会有第一个的bug
            $flag || $wordLength = 0; //如果$flag == false  赋值$wordLenth为0
        }*/
        if($flag==false){
            $wordLength = 0 ;
        }
        return $wordLength;
    }

    /*
     * 使用方式
     * $wordObj = new TreeFilter();
        $dict = ['a','ab','c'];
        $wordObj->addWords($dict);
    $checkData = $wordObj->search($checkList);
     *
     * */

}