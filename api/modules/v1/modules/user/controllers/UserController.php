<?php

declare(strict_types=1);

namespace api\modules\v1\modules\user\controllers;

use api\modules\v1\controllers\BaseController;
use api\modules\v1\modules\user\models\data\User;

class UserController extends BaseController
{
    /** @var string Класс пользователь */
    public string $modelClass = User::class;
}