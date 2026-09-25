<?php

namespace App\Livewire\Bible;

use App\Services\Bible\SonnyLabBibleService;
use Livewire\Component;
use Throwable;

class Reader extends Component
{
    public string $version = 'tb';

    public string $book = 'Kejadian';

    public int $chapter = 1;

    public array $verses = [];

    public ?string $error = null;

    public bool $loading = false;

    public function mount(): void
    {
        $this->loadChapter();
    }

    public function loadChapter(): void
    {
        $this->loading = true;
        $this->error = null;

        try {
            $service = app(SonnyLabBibleService::class);

            $this->verses = $service->getChapter(
                book: $this->book,
                chapter: $this->chapter,
                version: $this->version
            );
        } catch (Throwable $e) {
            $this->verses = [];

            $this->error = $e->getMessage();
        } finally {
            $this->loading = false;
        }
    }

    public function updatedBook(): void
    {
        $this->chapter = 1;

        $this->loadChapter();
    }

    public function updatedVersion(): void
    {
        $this->loadChapter();
    }

    public function previousChapter(): void
    {
        if ($this->chapter <= 1) {
            return;
        }

        $this->chapter--;

        $this->loadChapter();
    }

    public function nextChapter(): void
    {
        $this->chapter++;

        $this->loadChapter();
    }

    public function render()
    {
        return view('livewire.bible.reader');
    }
}
