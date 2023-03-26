<?php

namespace Colorful\Tests\Preacher\Feature;

use Colorful\Preacher\Preacher;
use PHPUnit\Framework\TestCase;

/**
 * Preacher Test
 *
 * @author beta
 */
class PreacherTest extends TestCase
{
    
    public function testIsSucceed()
    {
        $preacher = Preacher::base();
        
        $this->assertTrue($preacher->isSucceed());
    }
    
}
