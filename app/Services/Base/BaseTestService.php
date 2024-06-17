<?php

namespace App\Services\Base;

class BaseTestService
{
    public static function assertDontSeeErrors($response): void
    {
        $response->assertDontSeeText('ErrorException');
        $response->assertDontSeeText('Undefined variable');
        $response->assertDontSeeText('Trying to get property');
        $response->assertDontSeeText('Trying to get property of non-object');
        $response->assertDontSeeText('Call to a member function');
        $response->assertDontSeeText('Call to undefined method');
        $response->assertDontSeeText('Call to undefined function');
        $response->assertDontSeeText('Class not found');
        $response->assertDontSeeText('syntax error');
        $response->assertDontSeeText('SQLSTATE');
        $response->assertDontSeeText('SQLSTATE[HY000]');
        $response->assertDontSeeText('SQLSTATE[42S22]');
        $response->assertDontSeeText('SQLSTATE[23000]');
        $response->assertDontSeeText('SQLSTATE[23505]');
        $response->assertDontSeeText('SQLSTATE[HY000]');
        $response->assertDontSeeText('SQLSTATE[42S22]');
        $response->assertDontSeeText('SQLSTATE[23000]');
        $response->assertDontSeeText('SQLSTATE[23505]');
        $response->assertDontSeeText('Route [] not defined');
        $response->assertDontSeeText('] not defined.');


    }

    public static function assertSee($response, array $textArray): void
    {
        if(!empty($textArray)) {
            foreach ($textArray as $text) {
                $response->assertSee($text, false);
            }
        }
    }

    public static function assertViewHas($response, array $viewArray): void
    {
        if(!empty($viewArray)) {
            foreach ($viewArray as $view) {
                $response->assertViewHas($view);
            }
        }
    }
}
