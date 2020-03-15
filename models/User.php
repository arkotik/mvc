<?php

namespace models;

use core\BaseModel;

/**
 * Class User
 * @package models
 *
 * @property int $id
 * @property string $username
 * @property string $full_name
 * @property string $info
 * @property string $photo
 * @property string $password_hash
 */
class User extends BaseModel
{
    public static $tableSchema;
}