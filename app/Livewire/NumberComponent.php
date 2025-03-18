<?php

namespace App\Livewire;

use App\Models\Number;
use Livewire\Component;

class NumberComponent extends Component
{
    public Number $number;

    public function getListeners()
    {
        return [
            "echo:number.{number.id},UpdateNumberStatus" => 'update',
        ];
    }

    public function update(): void
    {
        $this->number->refresh();
    }

    public function render()
    {
        return view('livewire.number-component');
    }
}
