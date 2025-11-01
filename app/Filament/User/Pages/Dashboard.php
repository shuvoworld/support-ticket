<?php

declare(strict_types=1);

namespace App\Filament\User\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class Dashboard extends BaseDashboard
{
    protected static ?int $navigationSort = 0;

    public function getTitle(): string|Htmlable
    {
        return 'Dashboard';
    }

    protected function getHeaderActions(): array
    {
        return [
            FilterAction::make()
                ->filters([
                    //
                ]),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // Add user dashboard widgets here if needed
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            // Add footer widgets here if needed
        ];
    }

    public function getWidgets(): array
    {
        return [
            // Add dashboard widgets here
        ];
    }

    public function getVisibleWidgets(): array
    {
        return $this->filterVisibleWidgets($this->getWidgets());
    }

    public function getColumns(): int|array
    {
        return [
            'md' => 2,
            'lg' => 3,
        ];
    }
}