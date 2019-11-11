<?php

namespace common\services\site;

/**
 * This is the ActiveQuery class for [[SiteModel]].
 *
 * @see SiteModel
 */
class SiteQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return SiteModel[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SiteModel|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
