<?php

namespace App\QueryBuilders;

use App\Models\AuditLog;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class AuditLogQueryBuilder
{
    public function build(): QueryBuilder
    {
        return QueryBuilder::for(AuditLog::class)
            ->allowedFilters([
                AllowedFilter::exact('user_id'),
                AllowedFilter::exact('entity_id'),
                AllowedFilter::exact('entity_type'),
                AllowedFilter::exact('action'),
                AllowedFilter::exact('session_id'),
                AllowedFilter::scope('date_from'),
                AllowedFilter::scope('date_to'),
            ])
            ->defaultSort('-created_at')
            ->allowedSorts('created_at');
    }
}
