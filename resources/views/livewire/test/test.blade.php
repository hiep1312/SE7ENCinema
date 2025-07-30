<div class="dropdown">
                                        <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-filter me-1"></i>
                                            @switch($revenuePeriod)
                                                @case('3_days')
                                                    3 ngày gần nhất
                                                    @break
                                                @case('7_days')
                                                    7 ngày gần nhất
                                                    @break
                                                @case('30_days')
                                                    30 ngày gần nhất
                                                    @break
                                                @case('1_month')
                                                    1 tháng gần nhất
                                                    @break
                                                @case('3_months')
                                                    3 tháng gần nhất
                                                    @break
                                                @case('1_year')
                                                    1 năm gần nhất
                                                    @break
                                                @case('2_years')
                                                    2 năm gần nhất
                                                    @break
                                                @default
                                                    7 ngày gần nhất
                                            @endswitch
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-dark">
                                            <li><h6 class="dropdown-header text-primary">Ngày</h6></li>
                                            <li><a class="dropdown-item" href="#" wire:click.prevent="changeRevenuePeriod('3_days')">3 ngày gần nhất</a></li>
                                            <li><a class="dropdown-item" href="#" wire:click.prevent="changeRevenuePeriod('7_days')">7 ngày gần nhất</a></li>
                                            <li><a class="dropdown-item" href="#" wire:click.prevent="changeRevenuePeriod('30_days')">30 ngày gần nhất</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><h6 class="dropdown-header text-primary">Tháng</h6></li>
                                            <li><a class="dropdown-item" href="#" wire:click.prevent="changeRevenuePeriod('1_month')">1 tháng gần nhất</a></li>
                                            <li><a class="dropdown-item" href="#" wire:click.prevent="changeRevenuePeriod('3_months')">3 tháng gần nhất</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><h6 class="dropdown-header text-primary">Năm</h6></li>
                                            <li><a class="dropdown-item" href="#" wire:click.prevent="changeRevenuePeriod('1_year')">1 năm gần nhất</a></li>
                                            <li><a class="dropdown-item" href="#" wire:click.prevent="changeRevenuePeriod('2_years')">2 năm gần nhất</a></li>
                                        </ul>
                                    </div>
