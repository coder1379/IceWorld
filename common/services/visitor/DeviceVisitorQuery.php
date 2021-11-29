<?php

namespace common\services\visitor;

/**
 * This is the ActiveQuery class for [[DeviceVisitorModel]].
 *
 * @see DeviceVisitorModel
 */
class DeviceVisitorQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return DeviceVisitorModel[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return DeviceVisitorModel|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
