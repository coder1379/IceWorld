<?php

namespace common\services\admin;

/**
 * This is the ActiveQuery class for [[AdminAuthModel]].
 *
 * @see AdminAuthModel
 */
class AdminAuthQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return AdminAuthModel[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AdminAuthModel|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
