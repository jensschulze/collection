<?php

declare(strict_types=1);

namespace JensSchulze\Collection;

use Iterator;

interface Collection extends Iterator
{
    public function size(): int;

    public function up(int $index): Collection;

    public function down(int $index): Collection;

    public function delete(int $index): Collection;

    public function insert($element, int $index): Collection;

    public function asArray(): array;
}