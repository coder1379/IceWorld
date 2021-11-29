<?php

namespace common\services\sourcechannel;

/**
 * This is the ActiveQuery class for [[SourceChannelModel]].
 *
 * @see SourceChannelModel
 */
class SourceChannelQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return SourceChannelModel[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SourceChannelModel|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
