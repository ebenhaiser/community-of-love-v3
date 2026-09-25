<?php

namespace App\Livewire\AboutUs;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class AboutUs extends Component
{
    #[Layout('layouts.app')]
    #[Title('Tentang Kami')]
    public function render()
    {
        return view('livewire.about-us.about-us');
    }
}
