<?php

namespace Database\Seeders;

use App\Models\Promotion;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'title' => 'GIẢM 9% - MÙA HÈ NÓNG BỎNG',
                'description' => '
                    <h3>Ưu đãi 9% cho mọi suất chiếu</h3>
                    <p>Chào đón mùa hè rực rỡ cùng những bộ phim bom tấn tại rạp! Nhận ngay <strong>9% giảm giá</strong> cho tất cả các suất chiếu trong thời gian diễn ra chương trình.</p>
                    <ul>
                       <li><strong>Thời gian áp dụng:</strong> Từ '. now()->subDays(14)->format('d/m/Y') .' đến '. now()->addDays(18)->format('d/m/Y') .'</li>
                       <li>Áp dụng cho tất cả các suất chiếu trong hệ thống và tại rạp.</li>
                       <li>Không áp dụng cùng lúc với các chương trình khuyến mãi hoặc mã giảm giá khác.</li>
                       <li>Mỗi khách hàng được sử dụng tối đa 1 lần trong thời gian khuyến mãi.</li>
                       <li>Vé mua rồi không hoàn, hủy hoặc đổi suất chiếu.</li>
                    </ul>
                    <p><em>Hãy đặt vé ngay hôm nay để tận hưởng mùa hè cùng trải nghiệm điện ảnh tuyệt vời nhất!</em></p>
                ',
                'start_date' => now()->subDays(14),
                'end_date' => now()->addDays(18),
                'discount_type' => 'percentage',
                'discount_value' => 9,
                'usage_limit' => 500,
                'min_purchase' => 50000,
                'status' => 'active',
            ],
            [
                'title' => 'GIẢM 30K - COMBO VÉ + BẮP NƯỚC',
                'description' => '
                    <h3>Tiết kiệm 30.000đ cho combo vé + bắp nước</h3>
                    <p>Ưu đãi hấp dẫn dành riêng cho các tín đồ điện ảnh muốn thưởng thức trọn vẹn trải nghiệm xem phim.</p>
                    <ul>
                       <li><strong>Áp dụng khi:</strong> Mua tối thiểu 2 vé xem phim bất kỳ và 1 combo bắp nước.</li>
                       <li><strong>Thời gian áp dụng:</strong> '. now()->subDays(14)->format('d/m/Y') .' - '. now()->addDays(18)->format('d/m/Y') .'.</li>
                       <li>Áp dụng cho tất cả các suất chiếu trong hệ thống và tại rạp.</li>
                       <li>Không áp dụng cùng mã giảm giá khác và các suất chiếu đặc biệt.</li>
                    </ul>
                    <p><em>Thưởng thức phim trọn vẹn hơn với bắp nước thơm ngon và giá ưu đãi!</em></p>
                ',
                'start_date' => now()->subDays(14),
                'end_date' => now()->addDays(18),
                'discount_type' => 'fixed_amount',
                'discount_value' => 30000,
                'usage_limit' => 300,
                'min_purchase' => 100000,
                'status' => 'active',
            ],
            [
                'title' => 'GIẢM 25% - VÉ NHÓM BẠN',
                'description' => '
                    <h3>Ưu đãi cho nhóm bạn từ 4 người trở lên</h3>
                    <p>Rủ hội bạn thân đi xem phim ngay để nhận ưu đãi <strong>giảm 25%</strong> trên tổng giá trị hóa đơn.</p>
                    <ul>
                       <li><strong>Điều kiện:</strong> Đặt tối thiểu 4 vé trong cùng một giao dịch.</li>
                       <li>Áp dụng cho tất cả các bộ phim và suất chiếu.</li>
                       <li>Không áp dụng đồng thời với các chương trình ưu đãi khác.</li>
                       <li>Không giới hạn số lần sử dụng trong thời gian khuyến mãi.</li>
                    </ul>
                    <p><em>Xem phim đông vui hơn, giá lại rẻ hơn!</em></p>
                ',
                'start_date' => now()->subDays(11),
                'end_date' => now()->addDays(21),
                'discount_type' => 'percentage',
                'discount_value' => 25,
                'usage_limit' => 1000,
                'min_purchase' => 200000,
                'status' => 'active',
            ],
            [
                'title' => 'GIẢM 13K - VÉ BUỔI SÁNG',
                'description' => '
                    <h3>Ưu đãi 13.000đ cho các suất chiếu trước 12h</h3>
                    <p>Dành cho những khán giả yêu thích sự yên tĩnh và không gian nhẹ nhàng vào buổi sáng.</p>
                    <ul>
                       <li><strong>Thời gian áp dụng:</strong> Từ thứ 2 đến thứ 6, trong khung giờ trước 12:00.</li>
                       <li>Không áp dụng cho ngày lễ, Tết hoặc suất chiếu đặc biệt.</li>
                       <li>Áp dụng cho tất cả các suất chiếu trong hệ thống và tại rạp.</li>
                    </ul>
                    <p><em>Bắt đầu một ngày mới cùng bộ phim yêu thích và giá vé ưu đãi!</em></p>
                ',
                'start_date' => now()->subDays(11),
                'end_date' => now()->addDays(21),
                'discount_type' => 'fixed_amount',
                'discount_value' => 13000,
                'usage_limit' => 200,
                'min_purchase' => 40000,
                'status' => 'active',
            ],
            [
                'title' => 'GIẢM 23% - VÉ CUỐI TUẦN',
                'description' => '
                    <h3>Ưu đãi cuối tuần</h3>
                    <p>Thư giãn cuối tuần với ưu đãi <strong>giảm 23%</strong> cho tất cả các suất chiếu vào Thứ 7 và Chủ Nhật.</p>
                    <ul>
                       <li>Áp dụng cho tất cả các suất chiếu trong hệ thống và tại rạp.</li>
                       <li>Không áp dụng cho suất chiếu đặc biệt hoặc suất chiếu sớm.</li>
                    </ul>
                    <p><em>Cuối tuần này, cùng gia đình và bạn bè thưởng thức điện ảnh thôi!</em></p>
                ',
                'start_date' => now()->subDays(9),
                'end_date' => now()->addDays(23),
                'discount_type' => 'percentage',
                'discount_value' => 23,
                'usage_limit' => 600,
                'min_purchase' => 170000,
                'status' => 'active',
            ],
            [
                'title' => 'GIẢM 40K - PHIM HOT',
                'description' => '
                    <h3>Ưu đãi cho các phim bom tấn tháng '. date('n') .'</h3>
                    <p>Giảm ngay <strong>40.000đ</strong> cho các phim nằm trong danh sách <strong>phim hot</strong> của tháng.</p>
                    <ul>
                       <li>Danh sách phim hot được cập nhật hàng tuần trên website.</li>
                       <li>Áp dụng cho tất cả các suất chiếu và tại rạp.</li>
                    </ul>
                    <p><em>Không bỏ lỡ những bộ phim đang làm mưa làm gió tại phòng vé!</em></p>
                ',
                'start_date' => now()->subDays(9),
                'end_date' => now()->addDays(23),
                'discount_type' => 'fixed_amount',
                'discount_value' => 40000,
                'usage_limit' => 500,
                'min_purchase' => 120000,
                'status' => 'active',
            ],
            [
                'title' => 'GIẢM 25% - SINH NHẬT THÁNG ' . (date('n')),
                'description' => '
                    <h3>Happy Birthday!</h3>
                    <p>Khách hàng có ngày sinh trong tháng '. date('n') .' được giảm <strong>25%</strong> 1 vé/ngày.</p>
                    <ul>
                       <li>Xuất trình giấy tờ tùy thân.</li>
                       <li>Không cộng dồn ưu đãi.</li>
                    </ul>
                ',
                'start_date' => now()->subDays(6),
                'end_date' => now()->addDays(25),
                'discount_type' => 'percentage',
                'discount_value' => 25,
                'usage_limit' => 3000,
                'min_purchase' => 150000,
                'status' => 'active',
            ],
            [
                'title' => 'FLASH SALE '. date('d/m') .' - GIẢM 100K',
                'description' => '
                    <h3>Trong 24 giờ</h3>
                    <p>Mỗi đơn hàng được giảm thẳng <strong>100.000đ</strong>.</p>
                    <ul>
                       <li>Chỉ áp dụng ngày '. date('d/m/Y') .'.</li>
                       <li>Số lượng mã có hạn.</li>
                    </ul>
                ',
                'start_date' => now(),
                'end_date' => now()->addHours(24),
                'discount_type' => 'fixed_amount',
                'discount_value' => 100000,
                'usage_limit' => 300,
                'min_purchase' => 350000,
                'status' => 'active',
            ],
            [
                'title' => 'GIẢM 10K - VÉ 2D NGÀY THƯỜNG',
                'description' => '
                    <h3>Tiết kiệm cho 2D</h3>
                    <p>Giảm <strong>45.000đ</strong>/vé 2D từ Thứ 2 - Thứ 5.</p>
                    <ul>
                       <li>Không áp dụng suất từ 18:00 - 21:00.</li>
                    </ul>
                ',
                'start_date' => now()->subDays(6),
                'end_date' => now()->addDays(25),
                'discount_type' => 'fixed_amount',
                'discount_value' => 10000,
                'usage_limit' => 1500,
                'min_purchase' => 50000,
                'status' => 'active',
            ],
            [
                'title' => 'GIẢM 22K - ĐẶT VÉ ONLINE',
                'description' => '
                    <h3>Ưu đãi khi đặt vé online</h3>
                    <p>Giảm 60.000đ cho mỗi đơn đặt vé trực tuyến.</p>
                    <ul>
                       <li>Không áp dụng khi mua vé trực tiếp tại quầy.</li>
                       <li>Áp dụng cho mọi suất chiếu.</li>
                    </ul>
                    <p><em>Đặt vé online nhanh chóng, nhận ưu đãi liền tay!</em></p>
                ',
                'start_date' => now()->subDays(4),
                'end_date' => now()->addDays(28),
                'discount_type' => 'fixed_amount',
                'discount_value' => 22000,
                'usage_limit' => 700,
                'min_purchase' => 90000,
                'status' => 'active',
            ],
            [
                'title' => 'GIẢM 20% - PHIM GIA ĐÌNH',
                'description' => '
                    <h3>Ưu đãi cho vé phim gia đình</h3>
                    <p>Áp dụng cho nhóm 2 người lớn + 2 trẻ em trở lên.</p>
                    <ul>
                       <li>Giảm 20% trên tổng hóa đơn.</li>
                       <li>Không áp dụng cho phim chiếu sớm hoặc suất đặc biệt.</li>
                    </ul>
                    <p><em>Đưa cả nhà đi xem phim với giá ưu đãi!</em></p>
                ',
                'start_date' => now()->subDays(4),
                'end_date' => now()->addDays(28),
                'discount_type' => 'percentage',
                'discount_value' => 20,
                'usage_limit' => 300,
                'min_purchase' => 160000,
                'status' => 'inactive',
            ],
            [
                'title' => 'GIẢM 12% - SUẤT HỌC SINH/SINH VIÊN',
                'description' => '
                    <h3>Ưu đãi 12% cho học sinh, sinh viên</h3>
                    <p>Chỉ cần xuất trình thẻ học sinh hoặc thẻ sinh viên khi mua vé.</p>
                    <ul>
                       <li>Áp dụng mọi suất chiếu từ thứ 2 đến thứ 6.</li>
                       <li>Không áp dụng vào ngày lễ, Tết.</li>
                    </ul>
                    <p><em>Xem phim rẻ hơn, vui hơn!</em></p>
                ',
                'start_date' => now()->subDays(1),
                'end_date' => now()->addMonth(),
                'discount_type' => 'percentage',
                'discount_value' => 12,
                'usage_limit' => 800,
                'min_purchase' => 60000,
                'status' => 'active',
            ],
            [
                'title' => 'GIẢM 38K - PHIM HÀNH ĐỘNG THÁNG ' . (date('n')),
                'description' => '
                    <h3>Ưu đãi tháng '. date('n') .' cho phim hành động</h3>
                    <p>Giảm ngay 38K giá vé cho tất cả các suất chiếu phim hành động trong tháng '. date('n') .'.</p>
                    <ul>
                       <li>Áp dụng cho cả vé 2D và 3D.</li>
                       <li>Không áp dụng cho phim chiếu sớm.</li>
                       <li>Chỉ áp dụng khi đặt vé online.</li>
                    </ul>
                    <p><em>Trải nghiệm những pha hành động nghẹt thở với giá siêu hời!</em></p>
                ',
                'start_date' => now()->subDays(1),
                'end_date' => now()->addMonth(),
                'discount_type' => 'fixed_amount',
                'discount_value' => 38,
                'usage_limit' => 500,
                'min_purchase' => 125000,
                'status' => 'active',
            ],
            [
                'title' => 'GIẢM 9K - ĐẶT VÉ SỚM',
                'description' => '
                    <h3>Giảm 9.000đ khi đặt vé sớm</h3>
                    <p>Ưu đãi dành cho khách hàng chủ động lên lịch xem phim.</p>
                    <ul>
                       <li>Áp dụng cho mọi suất chiếu và loại phim.</li>
                       <li>Không áp dụng cho đặt vé vào ngày khuyến mãi khác.</li>
                    </ul>
                    <p><em>Đặt sớm - tiết kiệm nhiều!</em></p>
                ',
                'start_date' => now(),
                'end_date' => now()->addDays(3),
                'discount_type' => 'fixed_amount',
                'discount_value' => 9000,
                'usage_limit' => 2000,
                'min_purchase' => 0,
                'status' => 'active',
            ],
            [
                'title' => 'GIẢM 24K - MUA VÉ TRÊN WEBSITE',
                'description' => '
                    <h3>Đặt vé tiện lợi qua website</h3>
                    <p>Giảm ngay 24.000đ khi đặt vé qua website.</p>
                    <ul>
                       <li>Áp dụng mọi suất chiếu.</li>
                       <li>Không áp dụng khi mua tại quầy.</li>
                    </ul>
                    <p><em>Tiết kiệm thời gian, giảm thêm chi phí!</em></p>
                ',
                'start_date' => now(),
                'end_date' => now()->addMonth(),
                'discount_type' => 'fixed_amount',
                'discount_value' => 24000,
                'usage_limit' => 1000,
                'min_purchase' => 100000,
                'status' => 'inactive',
            ],
            [
                'title' => 'GIẢM 15% - GIỜ VÀNG XEM PHIM',
                'description' => '
                    <h3>Giảm giá đặc biệt trong khung giờ vàng</h3>
                    <p>Giảm 30% cho mọi vé đặt trong khung giờ từ 12h trưa đến 2h chiều mỗi ngày.</p>
                    <ul>
                       <li>Áp dụng cho tất cả suất chiếu và loại vé.</li>
                       <li>Không áp dụng cùng mã khác.</li>
                    </ul>
                    <p><em>Giờ vàng - Giá vàng - Phim hay!</em></p>
                ',
                'start_date' => now()->addDays(3),
                'end_date' => now()->addDays(38),
                'discount_type' => 'percentage',
                'discount_value' => 15,
                'usage_limit' => 5000,
                'min_purchase' => 100000,
                'status' => 'active',
            ],
            [
                'title' => 'MUA 5 TẶNG 1 - ƯU ĐÃI KHỦNG',
                'description' => '
                    <h3>Mua nhiều - Xem nhiều - Tiết kiệm nhiều</h3>
                    <p>Mua 5 vé cùng lúc được tặng 1 vé miễn phí.</p>
                    <ul>
                       <li>Áp dụng cho cùng một suất chiếu.</li>
                       <li>Chỉ áp dụng <strong>một lần duy nhất</strong> cho mỗi khách hàng.</li>
                       <li>Không áp dụng kèm các chương trình khuyến mãi khác.</li>
                    </ul>
                    <p><em>Đi đông không lo tốn kém!</em></p>
                ',
                'start_date' => now()->addDays(7),
                'end_date' => now()->addDays(43),
                'discount_type' => 'fixed_amount',
                'discount_value' => 100000,
                'usage_limit' => 2000,
                'min_purchase' => 500000,
                'status' => 'inactive',
            ],
            [
                'title' => 'GIẢM 10% - GHẾ ĐÔI LÃNG MẠN',
                'description' => '
                    <h3>Ngồi gần nhau - Giá giảm sâu</h3>
                    <p>Giảm 10% giá vé ghế đôi cho mọi suất chiếu.</p>
                    <ul>
                       <li>Áp dụng tất cả các ngày.</li>
                       <li>Chỉ áp dụng cho ghế đôi.</li>
                    </ul>
                    <p><em>Tình cảm tăng, chi phí giảm!</em></p>
                ',
                'start_date' => now()->addDays(3),
                'end_date' => now()->addDays(38),
                'discount_type' => 'percentage',
                'discount_value' => 10,
                'usage_limit' => null,
                'min_purchase' => 60000,
                'status' => 'active',
            ],
            [
                'title' => 'GIẢM 26K - CODE BÍ MẬT',
                'description' => '
                    <h3>Tìm ra code - Vé rẻ bất ngờ</h3>
                    <p>Một mã giảm giá bí mật được ẩn đâu đó trên website. Nếu bạn tìm thấy và nhập đúng mã, bạn sẽ nhận ngay giảm 26.000đ cho 1 vé bất kỳ.</p>
                    <ul>
                       <li>Chỉ áp dụng cho 1 lần sử dụng duy nhất.</li>
                       <li>Áp dụng mọi loại phim, mọi suất chiếu.</li>
                       <li>Mã chỉ hiển thị cho những ai thật sự tinh mắt!</li>
                    </ul>
                    <p><em>Bạn tìm ra code này rồi, chúc mừng nhé!</em></p>
                ',
                'start_date' => ($startTimeForCodeSecret = fake()->dateTimeBetween('now', '+7 days', 'Asia/Ho_Chi_Minh')),
                'end_date' => Carbon::parse($startTimeForCodeSecret)->addDay(),
                'discount_type' => 'fixed_amount',
                'discount_value' => 26,
                'usage_limit' => 3,
                'min_purchase' => 0,
                'status' => 'active',
            ]
        ];

        if(date('n') === 8){
            array_push($data, [
                'title' => 'GIẢM 50% - MỪNG CÁCH MẠNG THÁNG 8',
                'description' => '
                    <h3>Kỷ niệm ngày Cách Mạng Tháng 8 Thành Công</h3>
                    <p>Nhân dịp kỷ niệm sự kiện lịch sử trọng đại của dân tộc, chúng tôi dành tặng ưu đãi đặc biệt cho mọi khách hàng.</p>
                    <ul>
                       <li>Giảm ngay 50% tổng giá trị đơn hàng trong <strong>1 giao dịch duy nhất</strong>.</li>
                       <li>Áp dụng mọi loại vé, mọi suất chiếu và mọi ghế ngồi.</li>
                       <li>Không áp dụng cộng dồn với các khuyến mãi khác.</li>
                       <li>Thời gian áp dụng: từ ngày 17/08 đến hết ngày 25/08.</li>
                    </ul>
                    <p><em>Hòa mình vào không khí hào hùng của lịch sử - Xem phim với giá không thể tin!</em></p>
                ',
                'start_date' => '2025-08-17',
                'end_date' => '2025-08-25',
                'discount_type' => 'percentage',
                'discount_value' => 50,
                'usage_limit' => null,
                'min_purchase' => 0,
                'status' => Carbon::parse('2025-08-25')->isPast() ? 'expired' : 'active',
            ], [
                'title' => 'GIẢM 35% - NGÀY QT NẠN NHÂN MẤT TÍCH CƯỠNG BỨC',
                'description' => '
                    <h3>Chia sẻ và đồng hành cùng nạn nhân mất tích cưỡng bức</h3>
                    <p>Nhân ngày 30/08, chúng tôi dành tặng ưu đãi đặc biệt để cùng lan tỏa sự quan tâm và sẻ chia.</p>
                    <ul>
                       <li>Giảm 40% tổng giá trị đơn hàng trong <strong>1 giao dịch duy nhất</strong> từ 28/08 đến hết 31/08.</li>
                       <li>Áp dụng mọi loại vé: 2D, 3D, IMAX, VIP.</li>
                       <li>Không áp dụng cộng dồn với các mã giảm giá khác.</li>
                    </ul>
                    <p><em>Xem phim – thêm yêu thương – lan tỏa nhân ái.</em></p>
                ',
                'start_date' => '2025-08-28',
                'end_date' => '2025-08-31',
                'discount_type' => 'percentage',
                'discount_value' => 35,
                'usage_limit' => 2000,
                'min_purchase' => 0,
                'status' => Carbon::parse('2025-08-31')->isPast() ? 'expired' : 'active',
            ], [
                'title' => 'GIẢM 40% - NGÀY VÌ NẠN NHÂN CHẤT ĐỘC MÀU DA CAM',
                'description' => '
                    <h3>Chung tay vì nạn nhân chất độc màu da cam</h3>
                    <p>Ngày 10/08 là dịp để tưởng nhớ và sẻ chia với những nạn nhân chất độc màu da cam Việt Nam.</p>
                    <ul>
                       <li>Giảm 45% tổng giá trị đơn hàng trong <strong>1 giao dịch duy nhất</strong> từ 07/08 đến hết 10/08.</li>
                       <li>Áp dụng cho mọi loại phim và ghế ngồi.</li>
                       <li>Không áp dụng cộng dồn với các mã giảm giá khác.</li>
                    </ul>
                    <p><em>Hành động nhỏ – Ý nghĩa lớn.</em></p>
                ',
                'start_date' => '2025-08-07',
                'end_date' => '2025-08-10',
                'discount_type' => 'percentage',
                'discount_value' => 40,
                'usage_limit' => null,
                'min_purchase' => 0,
                'status' => Carbon::parse('2025-08-10')->isPast() ? 'expired' : 'active',
            ]);
        }else{
            array_push($data, [
                'title' => 'GIẢM 50% - MỪNG QUỐC KHÁNH VIỆT NAM',
                'description' => '
                    <h3>Chào mừng Quốc khánh 2/9</h3>
                    <p>Nhân dịp Quốc khánh nước Cộng hòa Xã hội Chủ nghĩa Việt Nam, chúng tôi gửi tặng ưu đãi đặc biệt đến mọi khách hàng.</p>
                    <ul>
                       <li>Giảm 50% tổng giá trị đơn hàng trong <strong>1 giao dịch duy nhất</strong> từ 01/09 đến hết 07/09.</li>
                       <li>Áp dụng mọi loại vé: 2D, 3D, IMAX, ghế VIP.</li>
                       <li>Không áp dụng cộng dồn với các mã giảm giá khác.</li>
                    </ul>
                    <p><em>Một ngày lễ lớn – Một niềm vui trọn vẹn!</em></p>
                ',
                'start_date' => '2025-09-01',
                'end_date' => '2025-09-07',
                'discount_type' => 'percentage',
                'discount_value' => 50,
                'usage_limit' => null,
                'min_purchase' => 0,
                'status' => Carbon::parse('2025-09-07')->isPast() ? 'expired' : 'active',
            ], [
                'title' => 'GIẢM 35% - NGÀY QUỐC TẾ HÒA BÌNH',
                'description' => '
                    <h3>Cùng lan tỏa thông điệp hòa bình</h3>
                    <p>Nhân Ngày Quốc tế Hòa bình 21/09, chúng tôi gửi tới khách hàng món quà ý nghĩa.</p>
                    <ul>
                       <li>Giảm 35% tổng giá trị đơn hàng trong <strong>1 giao dịch duy nhất</strong> từ 19/09 đến hết 25/09.</li>
                       <li>Áp dụng cho mọi định dạng phim và ghế ngồi.</li>
                       <li>Không áp dụng cộng dồn với các khuyến mãi khác.</li>
                    </ul>
                    <p><em>Phim hay – Thông điệp đẹp – Trái tim hòa bình.</em></p>
                ',
                'start_date' => '2025-09-19',
                'end_date' => '2025-09-25',
                'discount_type' => 'percentage',
                'discount_value' => 35,
                'usage_limit' => 2000,
                'min_purchase' => 0,
                'status' => Carbon::parse('2025-09-25')->isPast() ? 'expired' : 'active',
            ], [
                'title' => 'GIẢM 45% - TẾT TRUNG THU',
                'description' => '
                    <h3>Vui Tết Trung Thu – Xem phim thả ga</h3>
                    <p>Nhân dịp Tết Trung thu 17/09, rạp chiếu phim dành tặng ưu đãi siêu hấp dẫn cho mọi khách hàng.</p>
                    <ul>
                       <li>Giảm 45% tổng giá trị đơn hàng trong <strong>1 giao dịch duy nhất</strong> từ 14/09 đến hết 20/09.</li>
                       <li>Áp dụng cho mọi loại vé và mọi suất chiếu.</li>
                       <li>Không áp dụng cộng dồn với các chương trình khuyến mãi khác.</li>
                    </ul>
                    <p><em>Trung thu thêm rực rỡ cùng những thước phim tuyệt vời.</em></p>
                ',
                'start_date' => '2025-09-14',
                'end_date' => '2025-09-20',
                'discount_type' => 'percentage',
                'discount_value' => 45,
                'usage_limit' => null,
                'min_purchase' => 0,
                'status' => Carbon::parse('2025-09-20')->isPast() ? 'expired' : 'active',
            ]);
        }

        Promotion::insert(array_map(fn($promotion) => array_merge($promotion, ['description' => str_replace("    ", "", trim($promotion['description'])), 'code' => Str::random(8), 'created_at' => now()]), $data));
    }
}
