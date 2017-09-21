<?php

/*
 * This file is part of https://github.com/laravel-validation-rules/timezone
 *
 *  (c) Scott Wilcox <scott@dor.ky>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 */

namespace LVR\UKMobilePhone\Tests;

use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use LVR\UKMobilePhone\UKMobilePhone;
use PHPUnit\Framework\TestCase;
use Illuminate\Validation\Factory as Validator;

final class UKMobilePhoneTest extends TestCase
{
    public function buildValidator($uk_phone)
    {
        $app = new Container();
        $app->singleton('app', 'Illuminate\Container\Container');
        $translator     = new Translator(new FileLoader(new Filesystem(), null), 'en');
        $validator      = (new Validator($translator))->make(
            [ 'uk_phone' => $uk_phone ],
            [
            'uk_phone'  => [ 'required', new UKMobilePhone ],
            ]
        );

        return $validator;
    }

    /**
     * @test
     */
    public function testValidPhoneNumberWithPlusPasses()
    {
        $validator = $this->buildValidator("+447890123456");
        $this->assertTrue($validator->passes());
    }

    /**
     * @test
     */
    public function testValidPhoneNumberWithLeadingZeroPasses()
    {
        $validator = $this->buildValidator("07890123456");
        $this->assertTrue($validator->passes());
    }

    
    /**
     * @test
     */
    public function testInvalidPhoneNumberFails()
    {
        $validator = $this->buildValidator("I am not a phone number");
        $this->assertTrue($validator->fails());
    }
}
