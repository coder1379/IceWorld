<?php

namespace common\services\admin;

/**
 * This is the ActiveQuery class for [[AdminRoleModel]].
 *
 * @see AdminRoleModel
 */
class AdminRoleQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return AdminRoleModel[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AdminRoleModel|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
