<?php

declare(strict_types=1);

namespace LDG;

use LDG\Debug\Structure;
use LDG\Logger\CloudWatchLog;
use LDG\Logger\File;
use LDG\Util\BackTrace;
use LDG\Util\Configuration;

class Write
{
    public static function local(): Structure
    {
        return Structure::build(File::build(Configuration::route()), BackTrace::build(1))->name(Configuration::name());
    }

    public static function aws(): Structure
    {
        return Structure::build(CloudWatchLog::build([]), BackTrace::build(1))->name('Warning');
    }
}
