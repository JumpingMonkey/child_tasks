<?php

namespace App\Filament\Resources\DefaultTaskResource\Pages;

use App\Filament\Resources\DefaultTaskResource;
use App\Models\Schedule;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateDefaultTask extends CreateRecord
{
    protected static string $resource = DefaultTaskResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $receivedSchedule = array_intersect_key($data,array_flip([
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
            'saturday',
            'sunday',
        ]));

        $schedule = Schedule::query()->firstOrCreate($receivedSchedule);
        $data['schedule_id'] = $schedule->id;
        return static::getModel()::create($data);
    }
}
