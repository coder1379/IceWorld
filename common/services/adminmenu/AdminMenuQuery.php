<?php

namespace common\services\adminmenu;

/**
 * This is the ActiveQuery class for [[AdminMenuModel]].
 *
 * @see AdminMenuModel
 */
class AdminMenuQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return AdminMenuModel[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AdminMenuModel|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
