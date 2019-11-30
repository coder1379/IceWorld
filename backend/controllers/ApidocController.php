<?php

namespace backend\controllers;

use common\ComBase;
use Yii;
use yii\helpers\FileHelper;
use yii\db\Schema;

class ApidocController extends AuthController
{
    /**
     * @inheritdoc
     */
    public $layout = 'main-iframe';

    /**
     * Lists all WebsiteNewsModel models.
     * @return mixed
     */
    public function actionIndex()
    {


        $clickName = $this->get('cname', '');

        $docList = [];
        $except = ['IndexController.php',];
        $path = FileHelper::normalizePath(Yii::getAlias('@api') . '/controllers');

        $options = [
            'filter' => function ($path) {
                if (is_file($path)) {
                    $file = basename($path);
                    if ($file[0] < 'A' || $file[0] > 'Z') {
                        return false;
                    }
                }

                return null;
            },
            'only' => ['*.php'],
            'except' => $except,
        ];

        $controllers = FileHelper::findFiles($path, $options);

        foreach ($controllers as $c) {
            $carr = explode("/", $c);
            $carr = explode("\\", end($carr));
            $name = str_replace('.php', '', end($carr));

            $ref = new \ReflectionClass('\\api\\controllers\\' . $name);
            $parendRef = $ref->getParentClass();
            $controllerDoc = $this->parseDocCommentTags($ref)['description'] ?? $name;
            $controllerDesString = explode("\n", $controllerDoc)[0];
            $controllerDoc = explode("\r\n", $controllerDesString)[0];
            $methods = $ref->getMethods(\ReflectionMethod::IS_PUBLIC);
            $parentMethods = $parendRef->getMethods(\ReflectionMethod::IS_PUBLIC);
            $ownMethods = array_diff($methods, $parentMethods);
            $methodList = [];
            if (!empty($ownMethods)) {
                foreach ($ownMethods as $own) {
                    $parm = [];
                    $actionName = strtolower($own->getName());
                    $apiName = '';
                    if (strlen($actionName) > 6) {
                        $apiName = substr($actionName, 0, 6);
                    }

                    if ($apiName != 'action') {
                        continue;
                    }

                    $parm['name'] = substr($actionName, 6, strlen($actionName) - 6);
                    $parm['tags'] = $this->parseDocCommentTags($own);
                    if (empty($parm['tags']['description']) === false && strpos($parm['tags']['description'], 'targetDoc->') !== false) {
                        $newDocArr = explode('->', $parm['tags']['description']);
                        $apiDocClass = $newDocArr[1] ?? '';
                        $apiDocMethod = $newDocArr[2] ?? '';

                        if (empty($apiDocClass) == false && empty($apiDocMethod) == false) {
                            $targetMethod = new \ReflectionMethod($apiDocClass, $apiDocMethod);
                            $parm['tags'] = $this->parseDocCommentTags($targetMethod);
                        }

                    }

                    $parm['params'] = $own->getParameters();
                    $formatParams = [];

                    if (!empty($parm['tags']['param'])) {
                        $params = [];
                        if (is_array($parm['tags']['param'])) {
                            $params = $parm['tags']['param'];
                        } else {
                            $params[] = $parm['tags']['param'];
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
                                    $requireStr = '是';
                                } else if ($requireStr == '0' || $requireStr == 'false' || $requireStr == '非必填') {
                                    $requireStr = '否';
                                }
                                $newPam['require'] = $requireStr;
                            }
                            if (!empty($tmpArr2[4])) {
                                $newPam['default'] = $tmpArr2[4];
                            }

                            $formatParams[] = $newPam;
                        }
                    }

                    $parm['tags']['param'] = $formatParams;

                    $formatReturn = [];
                    $paramsReturn = [];
                    $returnList = [];

                    if (!empty($parm['tags']['return'])) {
                        $returns = $parm['tags']['return'];
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
                                if ($fm[0] == 'json') {
                                    $returnStatus = $fm[1] ?? '';
                                    $returnStatus = strtolower($returnStatus);
                                    if ($returnStatus == 'yes' || $returnStatus == '1' || $returnStatus == 'true' || $returnStatus == '成功' || $returnStatus == 'ok') {
                                        $returnStatus = true;
                                    } else {
                                        $returnStatus = false;
                                    }
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
                                        $returnList[] = $thisData;
                                    } else {
                                        $returnList[] = implode(' ', $fm);
                                    }
                                } else {
                                    $returnList[] = implode(' ', $fm);
                                }

                            }
                        }
                    }
                    $parm['tags']['return'] = $returnList;
                    $methodList[$parm['name']] = $parm;
                }
            }
            $name = str_replace('controller', '', strtolower($name));
            $docList[$name] = ['name' => $name, 'description' => $controllerDoc, 'methods' => $methodList];
        }

        return $this->render('index', ['docList' => $docList, 'cname' => $clickName]);
    }

    private function getReturnJsonFormat($json)
    {

        $reurnArr = [];
        if (!empty($json)) {
            foreach ($json as $key => $val) {
                if ($key == '@model') {
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

    private function getFeildType($type)
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

    protected function parseDocCommentTags($reflection)
    {
        $comment = $reflection->getDocComment();
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

    /**
     * Displays a single WebsiteNewsModel model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

}
