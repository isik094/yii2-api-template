<?php

declare(strict_types=1);

namespace common\base;

use yii\db\ActiveRecord as YiiActiveRecord;

/**
 * Базовый класс ActiveRecord
 * все модели db должны быть отнаследованы от него
 */
class ActiveRecord extends YiiActiveRecord
{
}