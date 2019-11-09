<?php

namespace common\services\user;

/**
 * This is the ActiveQuery class for [[UserModel]].
 *
 * @see UserModel
 */
class UserQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return UserModel[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UserModel|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
