<?php

namespace App\Livewire\AboutUs;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class AboutUs extends Component {

    #[Layout('layouts.app')]
    #[Title('Tentang Kami')]

    public function render()
    {
        return view('livewire.about-us.about-us');
    }
}