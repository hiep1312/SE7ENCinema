<?php

namespace App\Livewire\Admin\Genres;

use App\Models\Genre;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class GenreIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $showDeleteModal = false;
    public $genreIdToDelete;
    public $selectedGenres = [];
    public $selectAll = false;

    protected $queryString = ['search' => ['except' => '']];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        $this->selectedGenres = $value ? Genre::pluck('id')->toArray() : [];
    }

    public function confirmDelete($id = null)
    {
        $this->genreIdToDelete = $id;
        $this->showDeleteModal = true;
        $this->dispatch('show-delete-modal');
    }

    public function confirmBulkDelete()
    {
        if (empty($this->selectedGenres)) {
            session()->flash('error', 'Chọn ít nhất một thể loại.');
            return;
        }
        $this->genreIdToDelete = null;
        $this->showDeleteModal = true;
        $this->dispatch('show-delete-modal');
    }

    public function deleteGenre(array $status, int $id) 
    {
        if(!$status['isConfirmed']) return;
        $genre = Genre::find($id);
        if ($genre) {
            $genre->movies()->detach();
            $genre->delete();
        }
        session()->flash('success', 'Xóa thể loại thành công!');
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->genreIdToDelete = null;
        $this->dispatch('hide-delete-modal');
    }

    #[Layout('components.layouts.admin')]
    #[Title('Danh sách thể loại')]
    public function render()
    {
        $genres = Genre::withCount('movies')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('livewire.admin.genres.genre-index', compact('genres'));
    }
}