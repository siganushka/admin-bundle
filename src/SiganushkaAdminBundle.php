<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SiganushkaAdminBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
