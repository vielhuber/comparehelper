<?php
use vielhuber\comparehelper\comparehelper;

class BasicTest extends \PHPUnit\Framework\TestCase
{
    function test__compare()
    {
        $this->assertSame(comparehelper::compare('foo', 'foo'), true);

        $this->assertSame(comparehelper::compare(42, 42), true);

        $this->assertSame(comparehelper::compare(42, '42'), false);

        $this->assertSame(
            comparehelper::compare(
                [
                    'foo' => 'bar'
                ],
                [
                    'foo' => 'bar',
                    'foo2' => 'bar'
                ]
            ),
            false
        );

        $this->assertSame(
            comparehelper::compare(
                [
                    'foo' => 'bar',
                    'bar' => ['baz', 42]
                ],
                [
                    '#INT#' => 'bar',
                    'bar' => ['#STR#', '#INT#']
                ]
            ),
            false
        );

        $this->assertSame(
            comparehelper::compare(
                [
                    'foo' => 'bar',
                    'bar' => ['baz', 42]
                ],
                [
                    'foo' => '*',
                    'bar' => ['#INT#', '#STR#']
                ]
            ),
            false
        );

        $this->assertSame(
            comparehelper::compare(
                [
                    'foo' => 'bar',
                    'bar' => ['baz', 42]
                ],
                [
                    '*' => '*',
                    'bar' => ['#STR#', '#INT#']
                ]
            ),
            true
        );

        $this->assertSame(
            comparehelper::compare(
                [
                    'foo' => 'bar',
                    'bar' => ['baz', 42]
                ],
                [
                    '*' => '*',
                    'bar' => [42, 'baz']
                ]
            ),
            true
        );

        $this->assertSame(comparehelper::compare(['foo', 'bar'], ['bar', 'foo']), true);

        $this->assertSame(
            comparehelper::compare(['#INT#' => true, '#STR#' => true], [42 => true, 'foo' => true]),
            true
        );

        $this->assertSame(
            comparehelper::compare(['#STR#' => true, '#INT#' => true], [42 => true, 'foo' => true]),
            false
        );

        $this->assertSame(comparehelper::compare(['#INT#', '#STR#'], [42, 'foo']), true);

        $this->assertSame(comparehelper::compare(['#STR#', '#INT#'], [42, 'foo']), false);

        $this->assertSame(comparehelper::compare(['foo' => 7, 'bar' => 42], ['bar' => 42, 'foo' => 7]), true);

        $this->assertSame(comparehelper::compare(['#INT#' => 7, '#STR#' => 42], [7 => 7, 'foo' => 42]), true);

        $this->assertSame(comparehelper::compare(['#INT#' => 7, '#STR#' => 42], ['foo' => 42, 7 => 7]), false);

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
        // Test successful assertion
        comparehelper::assertEquals(['id' => '#INT#', 'name' => '#STR#'], ['id' => 42, 'name' => 'John']);

        // Test with wildcards
        comparehelper::assertEquals(['*' => '*', 'status' => 'active'], ['foo' => 'bar', 'status' => 'active']);

        $this->assertTrue(true); // Dummy assertion to make test pass
    }
}
