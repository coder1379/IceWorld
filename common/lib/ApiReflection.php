<?php


namespace common\lib;

use common\ComBase;
use Yii;
use yii\db\Schema;

class ApiReflection
{
    //返回参数的替换字符串，直接进行替换即可
    public $replaceArray =[
        '@pagination'=>'"pagination": {"page_size": "[number] 每页条数","total_page": "[number] 总页数","page": "[number] 当前页数","total_count": "[number] 数据总数"}',//分页替换变量 @pagination
    ];

    public function formatParams($tagsParm){
        $formatParams = [];
        if (!empty($tagsParm)) {
            $params = [];
            if (is_array($tagsParm)) {
                $params = $tagsParm;
            } else {
                $params[] = $tagsParm;
            }
            foreach ($params as $p) {
                $tmpArr = explode(' ', $p);
                $tmpArr2 = [];
                $newPam = [];
                if (!empty($tmpArr)) {
                    foreach ($tmpArr as $t) {
                        if (trim($t) != '') {
                            $tmpArr2[] = trim($t);
                        }
                    }
                }

                if(!empty($tmpArr2[0]) && $tmpArr2[0]=='@model'){
                    $modelClass = $tmpArr2[1];
                    $scenarios = $tmpArr2[2];

                    if(empty($modelClass) || empty($scenarios)){
                        continue;
                    }
                    try{
                        $apiModel = new $modelClass();
                        $scenariosList = $apiModel->scenarios()[$scenarios] ?? [];
                        if(!empty($scenariosList)){
                            $apiModel->loadDefaultValues();
                            $typeList = $apiModel->getTableSchema()->columns;
                            foreach ($scenariosList as $sname){
                                $newPam['require'] = $apiModel->isAttributeRequired($sname);
                                $newPam['default'] = $apiModel->$sname;
                                $newPam['desc'] = $apiModel->getAttributeLabel($sname);
                                $typeObj = $typeList[$sname] ?? null;
                                $newPam['name'] = $sname;
                                $newPam['type'] = '';
                                if (!empty($typeObj)) {
                                    $newPam['type'] = $this->getFeildType($typeObj->type);
                                }
                                $formatParams[] = $newPam;
                            }
                        }
                    }catch (\Exception $ex){

                    }
                }else{
                    if (!empty($tmpArr2[0])) {
                        $newPam['type'] = $tmpArr2[0];
                    }

                    if (!empty($tmpArr2[1])) {
                        $newPam['name'] = str_replace('$', '', $tmpArr2[1]);
                    }

                    if (!empty($tmpArr2[2])) {
                        $newPam['desc'] = $tmpArr2[2];
                    }
                    if (!empty($tmpArr2[3])) {
                        $requireStr = strval($tmpArr2[3]);
                        if ($requireStr == '1' || $requireStr == 'true' || $requireStr == '必填') {
                            $requireStr = true;
                        } else if ($requireStr == '0' || $requireStr == 'false' || $requireStr == '非必填') {
                            $requireStr = false;
                        }
                        $newPam['require'] = $requireStr;
                    }
                    if (!empty($tmpArr2[4])) {
                        $newPam['default'] = $tmpArr2[4];
                    }
                    $formatParams[] = $newPam;
                }




            }
        }
        return $formatParams;
    }

    public function formatReturns($returns){
        $formatReturn = [];
        $paramsReturn = [];
        $returnYesNoList = ['yes'=>[],'no'=>[]];
        $returnList = [];

        if (!empty($returns)) {
            $returns = $returns;
            if (is_array($returns)) {
                foreach ($returns as $r) {
                    $paramsReturn[] = $r;
                }
            } else {
                $paramsReturn[] = $returns;
            }
        }

        if (!empty($paramsReturn)) {
            foreach ($paramsReturn as $fr) {
                $splitArr = explode(' ', $fr);
                $newSplitArr = [];
                if (!empty($splitArr)) {
                    foreach ($splitArr as $s) {
                        if (trim($s) != '') {
                            $newSplitArr[] = $s;
                        }
                    }
                }
                $formatReturn[] = $newSplitArr;
            }
        }

        if (!empty($formatReturn)) {
            foreach ($formatReturn as $fm) {
                if (!empty($fm[0])) {
                    $returnStatus = $fm[1] ?? '';
                    $returnStatus = strtolower($returnStatus);
                    if ($returnStatus == 'yes' || $returnStatus == '1' || $returnStatus == 'true' || $returnStatus == '成功' || $returnStatus == 'ok') {
                        $returnStatus = true;
                    } else {
                        $returnStatus = false;
                    }
                    if ($fm[0] == 'json') {
                        unset($fm[0]);
                        unset($fm[1]);
                        $fm2Str = implode('', $fm);
                        $thisData = [];
                        $fm2Str = str_replace('\\', '\\\\', $fm2Str);
                        $jsonObj = json_decode($fm2Str, true);
                        if (!empty($jsonObj)) {
                            $code = $jsonObj['code'] ?? '';
                            $msg = $jsonObj['msg'] ?? '';
                            if ($returnStatus == true) {
                                if (empty($code)) {
                                    $code = ComBase::CODE_RUN_SUCCESS;
                                }
                                if (empty($msg)) {
                                    $msg = ComBase::MESSAGE_RUN_SUCCESS;
                                }

                            } else {
                                if (empty($code)) {
                                    $code = ComBase::CODE_PARAM_ERROR;
                                }
                                if (empty($msg)) {
                                    $msg = ComBase::MESSAGE_PARAM_ERROR;
                                }
                            }

                            $thisData['code'] = $code;
                            $thisData['msg'] = $msg;

                            $thisDataNew = $this->getReturnJsonFormat($jsonObj);
                            $thisData = array_merge($thisData, $thisDataNew);
                            if(empty($thisData['data'])){
                                $thisData['data'] = new \StdClass();
                            }
                            $returnList = $thisData;
                        } else {
                            $returnList = implode(' ', $fm);
                        }
                    }else if($fm[0] == 'file'){
                        $filePath = '';
                        $filePathStr = '';
                        foreach ($fm as $fmKey =>$fm2){
                            if($fmKey<2){
                                continue;
                            }
                            if(!empty($fm2)){
                                $filePathStr = $fm2;
                                break;
                            }
                        }

                        if(substr($filePathStr,0,1)=='/'){
                            $filePath = Yii::getAlias('@api') . '/document' . $filePathStr;
                        }else{
                            $filePath = Yii::getAlias('@api') . '/document' . '/' . $filePathStr;
                        }
                        $fileContent = '';
                        if(file_exists($filePath)){
                            $fileContent = file_get_contents($filePath);
                        }

                        $returnList = $fileContent;
                    } else {
                        $returnList = implode(' ', $fm);
                    }
                    if($returnStatus){
                        $returnYesNoList['yes'][] = $returnList;
                    }else{
                        $returnYesNoList['no'][] = $returnList;
                    }
                }
            }
        }
        return $returnYesNoList;
    }

    public function parseDocCommentTags($reflection)
    {
        $comment = $reflection->getDocComment();
        foreach ($this->replaceArray as $key=>$re){
            $comment = str_replace($key, $re, $comment);
        }
        $comment = "@description \n" . strtr(trim(preg_replace('/^\s*\**( |\t)?/m', '', trim($comment, '/'))), "\r", '');
        $parts = preg_split('/^\s*@/m', $comment, -1, PREG_SPLIT_NO_EMPTY);
        $tags = [];
        foreach ($parts as $part) {
            if (preg_match('/^(\w+)(.*)/ms', trim($part), $matches)) {
                $name = $matches[1];
                if (!isset($tags[$name])) {
                    $tags[$name] = trim($matches[2]);
                } elseif (is_array($tags[$name])) {
                    $tags[$name][] = trim($matches[2]);
                } else {
                    $tags[$name] = [$tags[$name], trim($matches[2])];
                }
            }
        }

        return $tags;
    }

    public function getReturnJsonFormat($json)
    {

        $reurnArr = [];
        if (!empty($json)) {
            foreach ($json as $key => $val) {
                if ($key === '@model') {
                    $fields = $json['@fields'] ?? 'detail';

                    $model = new $val();
                    $labels = $model->attributeLabels();
                    $typeList = $model->getTableSchema()->columns;
                    $printFields = $model->fieldsScenarios()[$fields];
                    if (!empty($printFields)) {
                        foreach ($printFields as $p) {
                            $fieldsShowType = '';
                            $typeObj = $typeList[$p] ?? null;
                            if (!empty($typeObj)) {
                                $fieldsShowType = $this->getFeildType($typeObj->type);
                            }
                            if (empty($fieldsShowType)) {
                                $fieldsShowType = '';
                            }
                            $reurnArr[$p] = '['.$fieldsShowType.'] '.$labels[$p] ;
                        }
                    }
                } else {
                    if($key === '@fields'){
                        continue;
                    }
                    if (is_array($val)) {
                        $newArr = $this->getReturnJsonFormat($val);
                        if (!empty($newArr)) {
                            $reurnArr[$key] = $newArr;
                        }
                    }else{
                        $reurnArr[$key] = $val;
                    }

                }
            }
        }

        return $reurnArr;
    }

    public function getFeildType($type)
    {
        $typeStr = '';
        switch ($type) {
            case Schema::TYPE_TINYINT:
            case Schema::TYPE_SMALLINT:
            case Schema::TYPE_INTEGER:
            case Schema::TYPE_BIGINT:
                $typeStr = 'number';
                break;
            case Schema::TYPE_BOOLEAN:
                $typeStr = 'boolean';
                break;
            case Schema::TYPE_FLOAT:
            case Schema::TYPE_DOUBLE:
            case Schema::TYPE_DECIMAL:
            case Schema::TYPE_MONEY:
                $typeStr = 'float';
                break;
            case Schema::TYPE_DATE:
            case Schema::TYPE_TIME:
            case Schema::TYPE_DATETIME:
            case Schema::TYPE_TIMESTAMP:
            default:
                $typeStr = 'string';
                break;
        }
        return $typeStr;
    }
}