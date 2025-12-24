<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;

class CustomerPhoneSearchField extends Field
{
    protected string $view = 'filament.forms.components.customer-phone-search-field';

    protected string | callable | null $getOptionLabelUsing = null;

    public function getOptionLabel(string | callable | null $callback): static
    {
        $this->getOptionLabelUsing = $callback;

        return $this;
    }

    public function getResolvedState(): mixed
    {
        return $this->getState();
    }
}
