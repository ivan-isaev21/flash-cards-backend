<?php

namespace App\Application\Cards\Queries;

class GetCardsQuery
{
    public function __construct(
        public readonly int $page = 1,
        public readonly int $perPage = 15
    ) {}
}
