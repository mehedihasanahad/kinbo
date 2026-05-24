<?php

declare(strict_types=1);

namespace App\Filament\Resources\CourierOrderResource\Pages;

use App\Filament\Resources\CourierOrderResource;
use Filament\Resources\Pages\ListRecords;

class ListCourierOrders extends ListRecords
{
    protected static string $resource = CourierOrderResource::class;
}
