@assets
<script>
    window.location.hash === "#print" && window.print();
</script>
@endassets
<div>
    @if(is_null($bookingSeat))
        <div class="header-section no-print">
            <div class="container-md">
                <div class="row">
                    <div class="col-12 mb-3 order-1">
                        <h1><i class="fas fa-print me-3"></i>Hệ thống in vé xem phim hàng loạt</h1>
                    </div>

                    <div class="col-lg-8 mb-3 mb-lg-0 order-3 order-lg-2">
                        <div class="control-panel-header">
                            <h4><i class="fas fa-filter me-2"></i>Bộ Lọc & Điều Khiển</h4>

                            <div class="filter-controls">
                                <div class="filter-group">
                                    <label for="statusFilter"><i class="fas fa-info-circle me-1"></i>Trạng thái vé:</label>
                                    <select class="form-select form-select-custom" wire:model.live="statusFilter" id="statusFilter">
                                        <option value="">Tất cả trạng thái</option>
                                        <option value="active">Chưa sử dụng</option>
                                        <option value="used">Đã sử dụng</option>
                                        <option value="canceled">Đã bị hủy</option>
                                    </select>
                                </div>

                                <div class="filter-group">
                                    <label for="takenFilter"><i class="fas fa-box-open me-1"></i>Tình trạng vé:</label>
                                    <select class="form-select form-select-custom" wire:model.live="takenFilter" id="takenFilter">
                                        <option value="">Tất cả tình trạng</option>
                                        <option value="1">Đã lấy vé</option>
                                        <option value="0">Chưa lấy vé</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="action-buttons">
                                <button class="btn btn-print-all" onclick="window.print()">
                                    <i class="fas fa-print me-2"></i>In Tất Cả Vé
                                </button>
                                <button class="btn btn-refresh" wire:click="$refresh">
                                    <i class="fas fa-sync-alt me-2"></i>Làm Mới
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 order-2 order-lg-3">
                        <div class="row">
                            <div class="col-6">
                                <div class="stats-card">
                                    <div class="stats-number">{{ $bookingSeatsOrigin->count() }}</div>
                                    <div class="stats-label">Tổng số vé</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stats-card">
                                    <div class="stats-number">{{ $bookingSeatsOrigin->reduce(function(int $carry, object $bookingSeat) {
                                        return $carry + (int)$bookingSeat->ticket->taken;
                                    }, 0) }}</div>
                                    <div class="stats-label">Đã lấy vé</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column" wire:poll.6s="realtimeUpdate" style="padding-bottom: 2rem; gap: 2.5rem; overflow: hidden;" id="printArea">
            @foreach($bookingSeats as $seat)
                <livewire:client.ticket.item :booking="$booking" :booking-seat="$seat" :key="$seat->id">
            @endforeach
        </div>
    @else
        <div style="padding: 20px;">
            <livewire:client.ticket.item :booking="$booking" :booking-seat="$bookingSeat">
        </div>
    @endif
</div>
