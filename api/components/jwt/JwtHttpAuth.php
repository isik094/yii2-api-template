<?php

declare(strict_types=1);

namespace api\components\jwt;

use yii\filters\auth\HttpBearerAuth;

/**
 * JWT аутентификация
 */
class JwtHttpAuth extends HttpBearerAuth
{

}