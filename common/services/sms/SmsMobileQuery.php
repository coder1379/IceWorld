<?php

namespace common\services\sms;

/**
 * This is the ActiveQuery class for [[SmsMobileModel]].
 *
 * @see SmsMobileModel
 */
class SmsMobileQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return SmsMobileModel[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SmsMobileModel|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
