<?php
use vielhuber\comparehelper\comparehelper;

class BasicTest extends \PHPUnit\Framework\TestCase
{
    function test__compare()
    {
        $this->assertSame(true, comparehelper::compare('foo', 'foo'));

        $this->assertSame(true, comparehelper::compare(42, 42));

        $this->assertSame(false, comparehelper::compare(42, '42'));

        $this->assertSame(
            false,
            comparehelper::compare(
                [
                    'foo' => 'bar'
                ],
                [
                    'foo' => 'bar',
                    'foo2' => 'bar'
                ]
            )
        );

        $this->assertSame(
            false,
            comparehelper::compare(
                [
                    'foo' => 'bar',
                    'bar' => ['baz', 42]
                ],
                [
                    '#INT#' => 'bar',
                    'bar' => ['#STR#', '#INT#']
                ]
            )
        );

        $this->assertSame(
            false,
            comparehelper::compare(
                [
                    'foo' => 'bar',
                    'bar' => ['baz', 42]
                ],
                [
                    'foo' => '*',
                    'bar' => ['#INT#', '#STR#']
                ]
            )
        );

        $this->assertSame(
            true,
            comparehelper::compare(
                [
                    'foo' => 'bar',
                    'bar' => ['baz', 42]
                ],
                [
                    '*' => '*',
                    'bar' => ['#STR#', '#INT#']
                ]
            )
        );

        $this->assertSame(
            true,
            comparehelper::compare(
                [
                    'foo' => 'bar',
                    'bar' => ['baz', 42]
                ],
                [
                    '*' => '*',
                    'bar' => [42, 'baz']
                ]
            )
        );

        $this->assertSame(true, comparehelper::compare(['foo', 'bar'], ['bar', 'foo']));

        $this->assertSame(
            true,
            comparehelper::compare(['#INT#' => true, '#STR#' => true], [42 => true, 'foo' => true])
        );

        $this->assertSame(
            false,
            comparehelper::compare(['#STR#' => true, '#INT#' => true], [42 => true, 'foo' => true])
        );

        $this->assertSame(true, comparehelper::compare(['#INT#', '#STR#'], [42, 'foo']));

        $this->assertSame(false, comparehelper::compare(['#STR#', '#INT#'], [42, 'foo']));

        $this->assertSame(true, comparehelper::compare(['foo' => 7, 'bar' => 42], ['bar' => 42, 'foo' => 7]));

        $this->assertSame(true, comparehelper::compare(['#INT#' => 7, '#STR#' => 42], [7 => 7, 'foo' => 42]));

        $this->assertSame(false, comparehelper::compare(['#INT#' => 7, '#STR#' => 42], ['foo' => 42, 7 => 7]));

        $this->assertTrue(comparehelper::compare(json_decode('{"pages":"*"}'), json_decode('{"pages":{"1":"foo"}}')));

        $this->assertTrue(
            comparehelper::compare(
                (object) [
                    'foo' => null
                ],
                (object) [
                    'foo' => '*'
                ]
            )
        );
    }

    function test__assertEquals()
    {
        comparehelper::assertEquals(['id' => '#INT#', 'name' => '#STR#'], ['id' => 42, 'name' => 'John']);

        comparehelper::assertEquals(['*' => '*', 'status' => 'active'], ['foo' => 'bar', 'status' => 'active']);

        $this->assertTrue(true);
    }
}
