<?php

namespace common\services\log;

/**
 * This is the ActiveQuery class for [[LogModel]].
 *
 * @see LogModel
 */
class LogQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return LogModel[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return LogModel|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
