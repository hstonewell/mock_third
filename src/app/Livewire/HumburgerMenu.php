<?php

namespace App\Livewire;

use Livewire\Component;

class HumburgerMenu extends Component
{
    public $showMenu = false;

    public function render()
    {
        return view('livewire.humburger-menu');
    }

    public function openMenu()
    {
        $this->showMenu = true;
    }

    public function closeMenu()
    {
        $this->showMenu = false;
    }
}
