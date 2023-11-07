<?php

namespace App\Filament\Resources\DefaultTaskResource\Pages;

use App\Filament\Resources\DefaultTaskResource;
use App\Models\Schedule;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditDefaultTask extends EditRecord
{
    use EditRecord\Concerns\Translatable;
     
    protected static string $resource = DefaultTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        
        $schedule = Schedule::query()
            ->select([
                'monday',
                'tuesday',
                'wednesday',
                'thursday',
                'friday',
                'saturday',
                'sunday',
            ])
            ->findOrFail($data['schedule_id']);
        $data = array_merge($data, $schedule->getAttributes());
        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
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
        $record->update($data);
        return $record;
    }
}
