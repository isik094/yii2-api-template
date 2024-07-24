<?php

declare(strict_types=1);

namespace api\models;

use api\traits\TokenTrait;

/**
 * Модель рефреш токен
 */
class Token extends \common\models\Token
{
    use TokenTrait;
}