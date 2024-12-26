<?php

use App\Helpers\ThrowHelper;
use Tests\TestCase;

class ThrowHelperTest extends TestCase
{
    public function test_should_throw_exception()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Something went wrong');

        $name = null;

        if (empty($name)) {
            ThrowHelper::exception('Something went wrong');
        }
    }
}
