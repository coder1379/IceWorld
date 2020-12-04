<?php

namespace common\services\message;

/**
 * This is the ActiveQuery class for [[MobileSmsModel]].
 *
 * @see MobileSmsModel
 */
class MobileSmsQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return MobileSmsModel[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return MobileSmsModel|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
