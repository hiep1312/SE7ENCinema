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
    public $genreFilter = '';
    public $sortOrder = '';
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

    public function resetFilters()
    {
        $this->search = '';
        $this->genreFilter = '';
        $this->sortOrder = '';
        $this->resetPage(); // Reset to the first page of pagination
    }

    #[Layout('components.layouts.admin')]
    #[Title('Danh sách thể loại')]
    public function render()
    {
        $genres = Genre::withCount('movies')
            ->when($this->search, function ($query) {
                // Chỉ tìm kiếm theo chữ cái đầu của tên thể loại, không phân biệt chữ thường/chữ in hoa
                $searchTerm = strtoupper(trim($this->search));
                if (strlen($searchTerm) === 1) {
                    $query->whereRaw('UPPER(LEFT(name, 1)) = ?', [$searchTerm]);
                }
            })
            ->when($this->genreFilter, function ($query) {
                $query->where('name', $this->genreFilter);
            })
            ->when($this->sortOrder, function ($query) {
                if ($this->sortOrder === 'newest') {
                    $query->orderBy('created_at', 'desc');
                } elseif ($this->sortOrder === 'oldest') {
                    $query->orderBy('created_at', 'asc');
                }
            }, function ($query) {
                $query->orderBy('created_at', 'desc'); // Mặc định sắp xếp mới nhất
            })
            ->paginate(10);
        return view('livewire.admin.genres.genre-index', compact('genres'));
    }
}