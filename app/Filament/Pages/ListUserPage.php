<?php

namespace App\Filament\Pages;

//use App\Exports\UsersExport;
//use App\Models\Roles;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Actions\Action;
use Filament\Pages\Page;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Builder;

class ListUserPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $layout = 'layouts.app';

    protected static string $view = 'filament.pages.list-user-page';

    protected static ?string $title = 'Users';

    protected function getActions(): array
    {
        return [
            Action::make('add')->action('addUser'),
        ];
    }

    public function addUser(): void
    {
        dd('asdfa');
    }

    protected function getTableQuery(): Builder
    {
        return User::withoutGlobalScopes();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('id')->searchable()->sortable()->label('ID'),
            TextColumn::make('sforceid')->toggleable(isToggledHiddenByDefault: true)->label('Salesforce ID'),
            TextColumn::make('first_name')->searchable()->label('First Name'),
            TextColumn::make('last_name')->searchable()->label('Last Name'),
            TagsColumn::make('roles_as_string')->separator(',')->visibleFrom('lg')->label('Roles'),
            TextColumn::make('email')->searchable()->label('Email'),
        ];
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'id';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }

    protected function getTableFilters(): array
    {
        return [
            Filter::make('created_at')
                ->form([
                    DatePicker::make('created_from'),
                    DatePicker::make('created_until'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['created_from'],
                            fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['created_until'],
                            fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                })->indicateUsing(function (array $data): array {
                    $indicators = [];

                    if ($data['created_from'] ?? null) {
                        $indicators['created_from'] = 'Created from ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                    }

                    if ($data['created_until'] ?? null) {
                        $indicators['created_until'] = 'Created until ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                    }

                    return $indicators;
                }),

        ];
    }

    protected function getTableBulkActions(): array
    {
        return [
            BulkAction::make('Export')->action('export')
        ];
    }

    public function export()
    {
        // could add code to make sure we're showing visible columns in the export
        $users = $this->getSelectedTableRecords();
        $filename = now()->format('Y-m-d_hi') . '-users.csv';

//        return (new UsersExport($users))->download($filename);
    }

}
