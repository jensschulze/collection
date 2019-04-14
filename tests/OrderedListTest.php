<?php

declare(strict_types=1);

namespace JensSchulze\Collection\Test;

use JensSchulze\Collection\Exception\OutOfBoundsException;
use JensSchulze\Collection\OrderedList;
use PHPUnit\Framework\TestCase;

class OrderedListTest extends TestCase
{

    public function testSize(): void
    {
        $collection = new OrderedList([]);
        $this->assertSame(0, $collection->size());

        $collection = new OrderedList(['a', 'b', 'c', 'd', 'e']);
        $this->assertSame(5, $collection->size());
    }

    public function testIteratorFunctions(): void
    {
        $collection = new OrderedList(['a', 'b', 'c', 'd', 'e']);
        $this->assertSame('a', $collection->current());

        $collection->next();
        $this->assertSame('b', $collection->current());

        $collection->next();
        $this->assertSame(2, $collection->key());

        $collection->rewind();
        $this->assertSame(0, $collection->key());
        $this->assertSame('a', $collection->current());

        $collection = new OrderedList(['a']);
        $collection->next();
        $this->assertFalse($collection->valid());
    }

    public function testIteratorLoop(): void
    {
        $values = ['a', 'b', 'c', 'd', 'e'];
        $collection = new OrderedList($values);
        foreach ($collection as $key => $item) {
            $this->assertSame($values[$key], $item);
        }
    }

    public function testAsArray(): void
    {
        $values = ['a', 'b', 'c', 'd', 'e'];
        $collection = new OrderedList($values);
        $this->assertEquals($values, $collection->asArray());
    }

    public function testUp(): void
    {
        $collection0 = new OrderedList(['a', 'b', 'c', 'd', 'e']);

        $collection1 = $collection0->up(1);
        $this->assertEquals(['b', 'a', 'c', 'd', 'e'], $collection1->asArray());

        $collection2 = $collection1->up(4);
        $this->assertEquals(['b', 'a', 'c', 'e', 'd'], $collection2->asArray());

        // But ist it imutable?
        $this->assertEquals(['b', 'a', 'c', 'd', 'e'], $collection1->asArray());
    }

    public function testUpIndexTooLow():void
    {
        $collection = new OrderedList(['a', 'b', 'c', 'd', 'e']);

        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage('Index "0" too low');
        $collection->up(0);
    }

    public function testUpIndexTooHigh():void
    {
        $collection = new OrderedList(['a', 'b', 'c', 'd', 'e']);

        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage('Index "5" too high');
        $collection->up(5);
    }

    public function testInsert(): void
    {
        $collection0 = new OrderedList(['a', 'b', 'c', 'd', 'e']);
        $collection1 = $collection0->insert('f', 4);
        $this->assertEquals(['a', 'b', 'c', 'd', 'f', 'e'], $collection1->asArray());

        $collection2 = $collection1->insert('g', 0);
        $this->assertEquals(['g', 'a', 'b', 'c', 'd', 'f', 'e'], $collection2->asArray());

        $collection3 = $collection2->insert('h', 7);
        $this->assertEquals(['g', 'a', 'b', 'c', 'd', 'f', 'e', 'h'], $collection3->asArray());

        // But is it immutable?
        $this->assertEquals(['g', 'a', 'b', 'c', 'd', 'f', 'e'], $collection2->asArray());
    }

    public function testInsertIndexTooLow(): void
    {
        $collection = new OrderedList(['a', 'b', 'c', 'd', 'e']);

        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage('Index "-1" too low');
        $collection->insert('g', -1);
    }

    public function testInsertIndexTooHigh(): void
    {
        $collection = new OrderedList(['a', 'b', 'c', 'd', 'e']);

        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage('Index "6" too high');
        $collection->insert('g', 6);
    }

    public function testDown(): void
    {
        $collection0 = new OrderedList(['a', 'b', 'c', 'd', 'e']);

        $collection1 = $collection0->down(0);
        $this->assertEquals(['b', 'a', 'c', 'd', 'e'], $collection1->asArray());

        $collection2 = $collection1->down(3);
        $this->assertEquals(['b', 'a', 'c', 'e', 'd'], $collection2->asArray());

        // But is it immutable?
        $this->assertEquals(['b', 'a', 'c', 'd', 'e'], $collection1->asArray());
    }

    public function testDownIndexTooLow(): void
    {
        $collection = new OrderedList(['a', 'b', 'c', 'd', 'e']);

        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage('Index "-1" too low');
        $collection->down(-1);
    }

    public function testDownIndexTooHigh(): void
    {
        $collection = new OrderedList(['a', 'b', 'c', 'd', 'e']);

        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage('Index "4" too high');
        $collection->down(4);
    }

    public function testDelete(): void
    {
        $collection0 = new OrderedList(['a', 'b', 'c', 'd', 'e']);

        $collection1 = $collection0->delete(3);
        $this->assertEquals(['a', 'b', 'c', 'e'], $collection1->asArray());

        $collection2 = $collection1->delete(0);
        $this->assertEquals(['b', 'c', 'e'], $collection2->asArray());

        // But is it immutable?
        $this->assertEquals(['a', 'b', 'c', 'e'], $collection1->asArray());
    }
}
