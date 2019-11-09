<?php

namespace common\services\administrator;

/**
 * This is the ActiveQuery class for [[AdministratorModel]].
 *
 * @see AdministratorModel
 */
class AdministratorQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return AdministratorModel[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AdministratorModel|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
