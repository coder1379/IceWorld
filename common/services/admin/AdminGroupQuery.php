<?php

namespace common\services\admin;

/**
 * This is the ActiveQuery class for [[AdminGroupModel]].
 *
 * @see AdminGroupModel
 */
class AdminGroupQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return AdminGroupModel[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AdminGroupModel|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
