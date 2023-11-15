<?php

namespace App\Filament\Resources\DefaultTaskResource\Pages;

use App\Filament\Resources\DefaultTaskResource;
use App\Models\Schedule;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class CreateDefaultTask extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = DefaultTaskResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $record = app(static::getModel());

        $translatableAttributes = $record->getTranslatableAttributes();

        $receivedSchedule = array_intersect_key(Arr::first($data),array_flip([
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
            'saturday',
            'sunday',
        ]));
        $schedule = Schedule::query()->firstOrCreate($receivedSchedule);
        $firstElement = array_keys($data)[0];
        $data[$firstElement]['schedule_id'] = $schedule->id;
        
        $record->fill(Arr::except(Arr::first($data), $translatableAttributes));
        
        foreach ($data as $locale => $localeData) {
            if ($locale === $this->activeLocale) {
                $localeData = Arr::only(
                    $localeData,
                    app(static::getModel())->getTranslatableAttributes(),
                );
            }

            foreach ($localeData as $key => $value) {
                $record->setTranslation($key, $locale, $value);
            }
        }

        $record->save();

        return $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            // ...
        ];
    }
}
