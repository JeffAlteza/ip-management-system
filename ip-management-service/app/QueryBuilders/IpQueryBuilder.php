<?php

namespace App\QueryBuilders;

use App\Models\Ip;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IpQueryBuilder
{
    public function build(): QueryBuilder
    {
        return QueryBuilder::for(Ip::class)
            ->allowedFilters([
                AllowedFilter::exact('created_by'),
                AllowedFilter::partial('label'),
                AllowedFilter::partial('ip_address'),
            ])
            ->defaultSort('-created_at')
            ->allowedSorts('created_at', 'ip_address', 'label');
    }
}
