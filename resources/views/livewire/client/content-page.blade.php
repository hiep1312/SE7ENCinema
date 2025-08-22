<div>
    <div class="product-heading">
        <div class="con flex gap-2">
            <select wire:model="selectedCategory" class="border rounded px-2 py-1">
                <option value="">Tìm kiếm Phim</option>
                <option value="movie">Phim hot</option>
                <option value="video">Phim đang chiếu</option>
                <option value="music">Phim Sắp chiếu</option>
            </select>

            <input wire:model.debounce.300ms="searchKeyword"
                type="text" placeholder="Search Movie, Video, Music"
                class="border rounded px-2 py-1 w-64" />

            <button type="button" class="px-3 bg-gray-200 border rounded">
                <i class="flaticon-tool"></i>
            </button>
            {{-- Notification Bell --}}
            <div style="display: inline-block; position: relative; margin-left: 18px; vertical-align: middle;" >
                @livewire('client.notifications.notification-index')
            </div>
        </div>
    </div>
</div>
