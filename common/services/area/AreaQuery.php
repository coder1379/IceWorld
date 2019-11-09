<?php

namespace common\services\area;

/**
 * This is the ActiveQuery class for [[AreaModel]].
 *
 * @see AreaModel
 */
class AreaQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return AreaModel[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AreaModel|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
