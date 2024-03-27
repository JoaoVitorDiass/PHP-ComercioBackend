<?php

namespace Comercio\Api\utils\observer;

use Comercio\Api\utils\observer\Subject;
interface Observer {
    public function update(Subject $sujeito): void;
}