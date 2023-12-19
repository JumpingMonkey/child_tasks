<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\TaskIcon;
use Filament\Forms\Form;
use App\Models\ProofType;
use Filament\Tables\Table;
use App\Models\DefaultTask;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\CheckboxList;
use Filament\Resources\Concerns\Translatable;
use App\Models\GeneralAvailableRegularTaskTemplate;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Awcodes\Curator\Components\Tables\CuratorColumn;
use App\Filament\Resources\DefaultTaskResource\Pages;
use App\Filament\Resources\DefaultTaskResource\RelationManagers;

class DefaultTaskResource extends Resource
{
    use Translatable;
    
    protected static ?string $model = GeneralAvailableRegularTaskTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Default tasks';

    protected static ?string $navigationGroup = 'Tasks';

    protected static ?string $label = 'Default tasks';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->maxLength(255)
                    ->columnSpan(2),
                // Forms\Components\TextInput::make('expected_duration')
                //     ->maxLength(255)
                //     ->hint('Number of seconds')
                //     ->default(0),
                Forms\Components\Checkbox::make('is_active')
                    ->default(true),
                Forms\Components\Select::make('proof_type_id')
                    ->label('Proof type')
                    ->relationship('proofType', 'title')
                    //Todo update title label with translation
                    ->getOptionLabelFromRecordUsing(
                        fn (ProofType $record) => $record->title)
                    ->required(),
                    // ->createOptionForm([
                    //     Forms\Components\TextInput::make('title')
                    //         ->required()
                    //         ->maxLength(255),
                    // ]),
                CuratorPicker::make('task_icon_id')
                    ->label('Icon')
                    ->relationship('taskIcon','id')
                    ->directory('task-icons')
                    ->size('lg')
                    ->buttonLabel('Add Icon'),
                
                Forms\Components\Fieldset::make('Image')
                    ->relationship('image')
                    ->schema([
                        FileUpload::make('filename')
                            ->directory('regular-tasks-images'),
                    ]),
                Forms\Components\Fieldset::make('Schedule')
                    ->schema([
                        Forms\Components\Checkbox::make('monday'),
                        Forms\Components\Checkbox::make('tuesday'),
                        Forms\Components\Checkbox::make('wednesday'),
                        Forms\Components\Checkbox::make('thursday'),
                        Forms\Components\Checkbox::make('friday'),
                        Forms\Components\Checkbox::make('saturday'),
                        Forms\Components\Checkbox::make('sunday'),
                    ])->columns(7),
                
                // Forms\Components\Select::make('schedule_id')
                //     ->label('Schedule')
                //     ->relationship(name: 'schedule')
                //     ->getOptionLabelFromRecordUsing(
                //         function (Model $record){
                //             $monday = $record->monday ? 'Mon': '';
                //             $tuesday = $record->tuesday ? 'Tues': '';
                //             $wednesday = $record->wednesday ? 'Wed': '';
                //             $thursday = $record->thursday ? 'Thurs': '';
                //             $friday = $record->friday ? 'Fri': '';
                //             $saturday = $record->saturday ? 'Sat': '';
                //             $sunday = $record->sunday ? 'Sun': '';
                //             return "{$monday} {$tuesday} {$wednesday} {$thursday} {$friday} {$saturday} {$sunday}";
                //         })                 
                    // ->createOptionForm([
                    //     Forms\Components\Checkbox::make('monday'),
                    //     Forms\Components\Checkbox::make('tuesday'),
                    //     Forms\Components\Checkbox::make('wednesday'),
                    //     Forms\Components\Checkbox::make('thursday'),
                    //     Forms\Components\Checkbox::make('friday'),
                    //     Forms\Components\Checkbox::make('saturday'),
                    //     Forms\Components\Checkbox::make('sunday'),
                    // ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('coins')
                    ->searchable(),
                CuratorColumn::make('task_icon_id')
                    ->circular()
                    ->size(60),
                ImageColumn::make('image.filename'),
                    
                Tables\Columns\CheckboxColumn::make('is_active')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            // RelationManagers\ScheduleRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDefaultTasks::route('/'),
            'create' => Pages\CreateDefaultTask::route('/create'),
            'edit' => Pages\EditDefaultTask::route('/{record}/edit'),
        ];
    } 
}
