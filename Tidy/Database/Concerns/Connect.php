<?php

namespace Tidy\Database\Concerns;

use Tidy\Database\Managers\Main\DatabaseManager;

trait Connect {
    public static function connect(DatabaseManager $manager)  {
        return $manager->connect();
    }
}