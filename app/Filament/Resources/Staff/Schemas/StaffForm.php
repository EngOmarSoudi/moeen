<?php

namespace App\Filament\Resources\Staff\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class StaffForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label(__('resources.staff.fields.user'))
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('employee_id')
                    ->label(__('resources.staff.fields.employee_id'))
                    ->required(),
                TextInput::make('department')
                    ->label(__('resources.staff.fields.department')),
                TextInput::make('job_title')
                    ->label(__('resources.staff.fields.job_title')),
                DatePicker::make('hired_at')
                    ->label(__('resources.staff.fields.hired_at')),
                DatePicker::make('birth_date')
                    ->label(__('resources.staff.fields.birth_date')),
                TextInput::make('salary')
                    ->label(__('resources.staff.fields.salary'))
                    ->numeric(),
                TextInput::make('emergency_contact')
                    ->label(__('resources.staff.fields.emergency_contact')),
                Textarea::make('address')
                    ->label(__('resources.staff.fields.address'))
                    ->columnSpanFull(),
                Textarea::make('notes')
                    ->label(__('resources.staff.fields.notes'))
                    ->columnSpanFull(),
                TextInput::make('status')
                    ->label(__('resources.staff.fields.status'))
                    ->required()
                    ->default('active'),
            ]);
    }
}
