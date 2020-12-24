<?php


namespace common\lib;

use Yii;

/**
 * mongodb 操作二次封装便于调用
 * Class MongoLib
 * @package common\lib
 */
class MongoLib
{
    /**
     * mongo原生扩展连接的静态变量，非yii2-mongodb的连接
     * @var null
     */
    public static $mongoManager = null;

    /**
     * 获取monogo唯一id 字符串
     * @return string
     */
    public static function getMongoIdStr()
    {
        $idObj = new \MongoDB\BSON\ObjectID();
        return strval($idObj);
    }

    /**
     * 获取自增数字id
     * @return int
     */
    public static function getAutoIncId($databaseName, $collectionName)
    {
        $update = [
            '$inc' => ["id" => 1]
        ];

        $query = ['_id' => $collectionName];
        $options = [
            'upsert' => true,
        ];
        $res = Yii::$app->mongodb->createCommand([], $databaseName)->findAndModify('auto_ids', $query,$update, $options);
        $id = 0;
        if(!empty($res) && isset($res['id'])){
            $id = intval($res['id']);
        }
        return ++$id;
    }

    /**
     * 批量获取数据
     * @param string $databaseName 数据库
     * @param string $collectionName 集合
     * @param array $where 查询条件
     * @param array $fields 获取字段 ['_id','uid'] 默认为所有
     * @param null $sort 排序规则 ['_id'=>-1,'uid'=>1] -1倒叙 1正序
     * @param null $limit limit ['skip' => 0, 'limit' => 2] skip跳过数目 limit获取数目
     * @return mixed
     */
    public static function getAll($databaseName, $collectionName, $where = [], $fields = [], $sort = null, $limit = null)
    {
        $options = [];
        if (!empty($sort)) {
            $options['sort'] = $sort;
        }

        if (!empty($fields)) {
            $options['projection'] = $fields;
        }

        $options['skip'] = $limit['skip'] ?? 0;
        $options['limit'] = $limit['limit'] ?? 10000;
        return Yii::$app->mongodb->createCommand([], $databaseName)->find($collectionName, $where, $options)->toArray();
    }

    /**
     * 获取mongo数据当 objectid对象为字符串时使用
     * @param $databaseName
     * @param $collectionName
     * @param array $where
     * @param array $fields
     * @param null $sort
     * @param null $limit
     * @return mixed
     */
    public static function getAllForObjectIdString($databaseName, $collectionName, $where = [], $fields = [], $sort = null, $limit = null){
        $options = [];
        if (!empty($sort)) {
            $options['sort'] = $sort;
        }

        if (!empty($fields)) {
            $options['projection'] = $fields;
        }

        $options['skip'] = $limit['skip'] ?? 0;
        $options['limit'] = $limit['limit'] ?? 10000;

        self::setMongoManager();
        $query = new \MongoDB\Driver\Query($where,$options);
        return self::$mongoManager->executeQuery($databaseName.'.'.$collectionName, $query, [])->toArray();
    }

    /**
     * 获取单个数据
     * @param $databaseName
     * @param $collectionName
     * @param array $where
     * @param array $fields
     * @param null $sort
     * @return mixed
     */
    public static function getOne($databaseName, $collectionName, $where = [], $fields = [], $sort = null)
    {
        $options = [];
        if (!empty($sort)) {
            $options['sort'] = $sort;
        }

        if (!empty($fields)) {
            $options['projection'] = $fields;
        }

        $options['limit'] = 1;
        $options['skip'] = 0;

        $list = Yii::$app->mongodb->createCommand([], $databaseName)->find($collectionName, $where, $options)->toArray();
        return current($list);
    }

    /**
     * 获取单个数据 当 objectid对象为字符串时使用
     * @param $databaseName
     * @param $collectionName
     * @param array $where
     * @param array $fields
     * @param null $sort
     * @return mixed
     */
    public static function getOneForObjectIdString($databaseName, $collectionName, $where = [], $fields = [], $sort = null)
    {
        $options = [];
        if (!empty($sort)) {
            $options['sort'] = $sort;
        }

        if (!empty($fields)) {
            $options['projection'] = $fields;
        }

        $options['limit'] = 1;
        $options['skip'] = 0;

        self::setMongoManager();
        $query = new \MongoDB\Driver\Query($where,$options);
        $list = self::$mongoManager->executeQuery($databaseName.'.'.$collectionName, $query, [])->toArray();
        if(!empty($list)){
            return current($list);
        }else{
            return [];
        }
    }

    /**
     * 获取满足条件的总数
     * @param $databaseName
     * @param $collectionName
     * @param array $where
     */
    public static function count($databaseName, $collectionName, $where = [])
    {
        return Yii::$app->mongodb->createCommand([], $databaseName)->count($collectionName, $where);
    }

    /**
     * 主要用于objectid为字符串使用count被转换为object对象问题
     * @param $databaseName
     * @param $collectionName
     * @param array $where
     */
    public static function countForObjectIdString($databaseName, $collectionName, $where = []){
        self::setMongoManager();
        $command = new \MongoDB\Driver\Command(['count'=>$collectionName,'query'=>$where]);
        $res = self::$mongoManager->executeCommand($databaseName,$command)->toArray();
        return current($res)->n;
    }

    /**
     * 通过原始语句修改mongodb值，注意如果_id为mongoid会被自动转换为objectId导致修改失败，数组id不会存在此类问题,另外的解决方案是单独创建一个唯一id的字段原_id字段继续使用原始objectid
     * 后续考虑通过另外一个monggodb包封装一个update的语句
     * @param $databaseName
     * @param $collectionName
     * @param $where
     * @param $setArr
     * @param bool $multiple
     * @param bool $upsert
     * @return mixed
     * @throws \Exception
     */
    public static function update($databaseName, $collectionName,$where,$setArr, $multiple = true, $upsert = false){
        if(empty($where)){
            throw new \Exception('update where is null');
        }

        if(empty($setArr)){
            throw new \Exception('update setArr is null');
        }

        $options = [
            'multi' => $multiple,
            'upsert' => $upsert,
        ];
       return Yii::$app->mongodb->createCommand([], $databaseName)->update($collectionName, $where,$setArr,$options)->getModifiedCount();
    }

    /**
     * 设置mongodb单例静态类
     */
    private static function setMongoManager(){
        if(self::$mongoManager==null){
            $masterMongoDb = Yii::$app->mongodb;
            self::$mongoManager = new \MongoDB\Driver\Manager($masterMongoDb->dsn,$masterMongoDb->options,$masterMongoDb->driverOptions);
        }
        return true;
    }

    /**
     * 通过原始语句修改mongodb值 主要用在当_id是objectid时 update无法编辑的时候
     * 优先使用update
     * @param $databaseName
     * @param $collectionName
     * @param $where
     * @param $setArr
     * @param bool $multiple
     * @param bool $upsert
     * @return mixed
     * @throws \Exception
     */
    public static function updateForObjectIdString($databaseName, $collectionName,$where,$setArr, $multiple = true, $upsert = false){
        if(empty($where)){
            throw new \Exception('update where is null');
        }

        if(empty($setArr)){
            throw new \Exception('update setArr is null');
        }

        $options = [
            'multi' => $multiple,
            'upsert' => $upsert,
        ];

        self::setMongoManager();
        $bulk = new \MongoDB\Driver\BulkWrite();
        $bulk->update($where,$setArr,$options);
        return self::$mongoManager->executeBulkWrite($databaseName.'.'.$collectionName, $bulk, [])->getModifiedCount();
    }

    /**
     * 删除 $limit=1删除第一条数据，$limit=0删除满足数据 objectid为字符串是需要使用deleteForObjectIdString
     * @param $databaseName
     * @param $collectionName
     * @param $where
     * @param int $limit
     */
    public static function delete($databaseName, $collectionName,$where,$limit=1){
        if(empty($where)){
            throw new \Exception('delete where is null');
        }

        $options = [
            'limit' => $limit,
        ];
        return Yii::$app->mongodb->createCommand([], $databaseName)->delete($collectionName, $where,$options)->getDeletedCount();
    }

    /**
     * 删除 $limit=1删除第一条数据，$limit=0|false删除满足数据 主要用于objectid为字符串使用delete被转换为object对象问题
     * @param $databaseName
     * @param $collectionName
     * @param $where
     * @param int $limit
     */
    public static function deleteForObjectIdString($databaseName, $collectionName,$where,$limit=1){
        if(empty($where)){
            throw new \Exception('delete where is null');
        }

        $options = [
            'limit' => $limit,
        ];

        self::setMongoManager();
        $bulk = new \MongoDB\Driver\BulkWrite();
        $bulk->delete($where,$options);
        return self::$mongoManager->executeBulkWrite($databaseName.'.'.$collectionName, $bulk, [])->getDeletedCount();
    }

    /**
     * 插入内容 yii2未对_id进行处理，所有没有obejctforstring方法均使用insert
     * @param $databaseName
     * @param $collectionName
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public static function insert($databaseName, $collectionName,$data){
        if(empty($data)){
            throw new \Exception('insert data is null');
        }
        return Yii::$app->mongodb->createCommand([], $databaseName)->insert($collectionName, $data);
    }

    /**
     * 批量插入内容 yii2未对_id进行处理，所有没有obejctforstring方法均使用insert
     * @param $databaseName
     * @param $collectionName
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public static function batchInsert($databaseName, $collectionName,$datas){
        if(empty($datas)){
            throw new \Exception('insert datas is null');
        }
        return Yii::$app->mongodb->createCommand([], $databaseName)->batchInsert($collectionName, $datas);
    }

}