<?php

namespace common\services\application;

/**
 * This is the ActiveQuery class for [[AppModel]].
 *
 * @see AppModel
 */
class AppQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return AppModel[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AppModel|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
