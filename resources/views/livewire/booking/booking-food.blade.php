<div class="scRender">

    <div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Thực đơn 🍿</h1>
        <p class="text-lg text-gray-500 mb-8">Chọn món ăn yêu thích của bạn</p>

        {{-- Danh sách món ăn (Giữ nguyên) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
             @foreach ($foodItems as $item)
                <div class="bg-white border rounded-xl shadow-md overflow-hidden transform hover:-translate-y-2 transition-all duration-300 cursor-pointer group"
                    wire:click="selectItem({{ $item->id }})">
                    <img class="h-48 w-full object-cover"
                         src="https://source.unsplash.com/400x300/?food,{{ urlencode($item->name) }}"
                         alt="{{ $item->name }}">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $item->name }}</h3>
                        <p class="text-gray-600 text-sm h-10">{{ Str::limit($item->description, 60) }}</p>
                        <div class="mt-4">
                            <span class="inline-block bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                                Chọn biến thể
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Modal chọn biến thể (PHẦN CẬP NHẬT) --}}
        @if ($showModal && $selectedItem)
            <div 
                x-data="{ show: @entangle('showModal') }" 
                x-show="show" 
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0" 
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" 
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" 
                class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4"
                @keydown.escape.window="show = false"
                >
                <div 
                    x-show="show" 
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] flex flex-col"
                    @click.outside="show = false"
                >
                    {{-- Modal Header --}}
                    <div class="flex justify-between items-center p-6 border-b">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">✨ {{ $selectedItem->name }}</h3>
                            <p class="text-gray-500">Tùy chỉnh lựa chọn của bạn</p>
                        </div>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    {{-- Modal Body (Đã được làm lại) --}}
                    <div class="p-6 overflow-y-auto space-y-6">
                        {{-- Vòng lặp các loại thuộc tính (Size, Vị,...) --}}
                        @foreach ($availableAttributes as $name => $values)
                            <div>
                                <h4 class="text-md font-semibold text-gray-800 mb-2">{{ $name }}:</h4>
                                <div class="flex flex-wrap gap-2">
                                    {{-- Vòng lặp các giá trị (S, M, L,...) --}}
                                    @foreach ($values as $value)
                                        <button 
                                            wire:click="selectAttribute('{{ $name }}', '{{ $value }}')"
                                            class="px-4 py-2 text-sm font-medium border rounded-lg transition-colors duration-200
                                                {{ ($selectedAttributeValues[$name] ?? null) == $value 
                                                    ? 'bg-blue-600 text-white border-blue-600' 
                                                    : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
                                            {{ $value }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                        
                        <hr>
                        
                        {{-- Hiển thị thông tin và chọn số lượng --}}
                        @if ($currentVariant)
                            <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                               <div class="flex justify-between items-center">
                                    <p class="text-gray-600">SKU: <span class="font-mono text-gray-800">{{ $currentVariant->sku }}</span></p>
                                    <p class="text-2xl font-bold text-green-600">{{ number_format($currentVariant->price) }}₫</p>
                               </div>
                               <div class="flex items-center justify-between">
                                    <label class="text-lg font-medium text-gray-700">Số lượng:</label>
                                    <div class="flex items-center border border-gray-300 rounded-lg">
                                        <button wire:click="decrementQuantity" class="px-4 py-2 text-xl text-gray-600 hover:bg-gray-200 rounded-l-lg transition">-</button>
                                        <input wire:model="currentQuantity" type="text" class="w-16 text-center text-lg font-semibold border-x bg-white focus:ring-0 focus:outline-none">
                                        <button wire:click="incrementQuantity" class="px-4 py-2 text-xl text-gray-600 hover:bg-gray-200 rounded-r-lg transition">+</button>
                                    </div>
                                </div>
                            </div>
                        @else
                             <div class="bg-red-50 text-red-700 rounded-lg p-4 text-center">
                                😥 Rất tiếc, lựa chọn này không có sẵn. Vui lòng thử lại.
                            </div>
                        @endif
                    </div>
                    
                    {{-- Modal Footer --}}
                    <div class="px-6 py-4 bg-gray-50 border-t mt-auto text-right">
                        <button 
                            wire:click="addToOrder"
                            @if(!$currentVariant) disabled @endif
                            class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white font-bold px-8 py-3 rounded-lg shadow-md hover:shadow-lg transition-all transform hover:scale-105 disabled:bg-gray-400 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none"
                            >
                            <span wire:loading.remove wire:target="addToOrder">✅ Thêm vào giỏ hàng</span>
                            <span wire:loading wire:target="addToOrder">Đang xử lý...</span>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Giỏ hàng --}}
        @if (!empty($this->orderDetails['items']))
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">🛒 Giỏ hàng của bạn</h2>
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b">
                                <th class="py-2">Món ăn</th>
                                <th class="py-2">Biến thể</th>
                                <th class="py-2 text-center">Số lượng</th>
                                <th class="py-2 text-right">Đơn giá</th>
                                <th class="py-2 text-right">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($this->orderDetails['items'] as $orderItem)
                                <tr class="border-b border-gray-100">
                                    <td class="py-4 font-semibold">{{ $orderItem['food_item_name'] }}</td>
                                    <td class="py-4 text-gray-600">
                                        @foreach ($orderItem['variant']->attributeValues as $attrVal)
                                            {{ $attrVal->value }}@if(!$loop->last), @endif
                                        @endforeach
                                    </td>
                                    <td class="py-4 text-center">{{ $orderItem['quantity'] }}</td>
                                    <td class="py-4 text-right">{{ number_format($orderItem['variant']->price) }}₫</td>
                                    <td class="py-4 text-right font-semibold">{{ number_format($orderItem['subtotal']) }}₫</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="pt-6 text-right font-bold text-lg">Tổng cộng:</td>
                                <td class="pt-6 text-right font-bold text-xl text-red-600">{{ number_format($this->orderDetails['total_price']) }}₫</td>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="text-right mt-6">
                         <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-3 rounded-lg shadow-md hover:shadow-lg transition-all">
                            🚀 Tiến hành đặt hàng
                        </button>
                    </div>
                </div>
            </div>
        @endif

        
    </div>
</div>

</div>