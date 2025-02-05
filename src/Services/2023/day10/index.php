<?php

declare(strict_types=1);

use App\Services\Logger;
use App\Controller\AocController;

include_once __DIR__ . "/../../../../src/autoloader.php";

(new AocController(__DIR__))->index(Logger::read());
