<?php

namespace common\services\systemconfig;

/**
 * This is the ActiveQuery class for [[SystemConfigModel]].
 *
 * @see SystemConfigModel
 */
class SystemConfigQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return SystemConfigModel[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SystemConfigModel|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
