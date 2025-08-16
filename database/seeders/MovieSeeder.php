<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'title' => 'Bến Phà Xác Sống',
                'description' => 'Nội dung “Bến Phà Xác Sống” xoay quanh cuộc chạy trốn của nhóm người của Công (Huỳnh Đông) khỏi sự bùng phát của dịch bệnh và cố gắng chạy đến chuyến phà cuối cùng ở vùng hạ lưu sông Mekong. Thế nhưng, trong quá trình chạy trốn, họ một lần thất lạc khi xuất hiện sự biến chất và phá bĩnh của Diễm (Ốc Thanh Vân) khiến nhóm người buộc phải tách ra. Và cuộc tấn công của Zombie đổ bộ cù lao, đối mặt giữa sống và chết, các nhân vật nhận ra không phải dịch bệnh, chính sự tồn tại của tính ích kỷ và oán hận mới là thứ đẩy họ vào những thử thách sống còn.',
                'duration' => '83',
                'release_date' => now()->addDays(17),
                'end_date' => null,
                'director' => 'Nguyễn Thành Nam',
                'actors' => 'Huỳnh Đông, Ốc Thanh Vân, Trần Phong, La Thành, Xuân Nghị, Lê Lộc',
                'age_restriction' => 'T16',
                'poster' => 'https://cinema.momocdn.net/img/18551296692651622-poster.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=1Jz3e_lq-wg',
                'format' => '2D',
                'genres' => ['Thảm hoạ', 'Hành động']
            ],
            [
                'title' => 'Mưa Đỏ',
                'description' => '“Mưa Đỏ” - Phim truyện điện ảnh về đề tài chiến tranh cách mạng, kịch bản của nhà văn Chu Lai, lấy cảm hứng và hư cấu từ sự kiện 81 ngày đêm chiến đấu anh dũng, kiên cường của nhân dân và cán bộ, chiến sĩ bảo vệ Thành Cổ Quảng Trị năm 1972. Tiểu đội 1 gồm toàn những thanh niên trẻ tuổi và đầy nhiệt huyết là một trong những đơn vị chiến đấu, bám trụ tại trận địa khốc liệt này. Bộ phim là khúc tráng ca bằng hình ảnh, là nén tâm nhang tri ân và tưởng nhớ những người con đã dâng hiến tuổi thanh xuân cho đất nước, mang âm hưởng của tình yêu, tình đồng đội thiêng liêng, là khát vọng hòa bình, hoà hợp dân tộc của nhân dân Việt Nam.',
                'duration' => '124',
                'release_date' => now()->addDays(17),
                'end_date' => null,
                'director' => 'Đặng Thái Huyền',
                'actors' => 'Đỗ Nhật Hoàng, Hứa Vĩ Văn, Lam Thanh Nha, Đình Khang, Phương Nam, Hoàng Long, Nguyễn Hùng',
                'age_restriction' => 'T13',
                'poster' => 'https://iguov8nhvyobj.vcdn.cloud/media/catalog/product/cache/1/image/c5f0a1eff4c394a251036189ccddaacd/3/5/350x495-muado_1.jpg',
                'trailer_url' => 'https://youtu.be/-Ir9rPvqwuw',
                'format' => '2D',
                'genres' => ['Chiến tranh', 'Lịch sử']
            ],
            [
                'title' => 'TRẬN CHIẾN SAU TRẬN CHIẾN',
                'description' => 'Mười sáu năm sau khi kẻ thù tàn ác biến mất, nhóm cựu chiến sĩ cách mạng buộc phải tái hợp để cứu con gái của một người anh em trong nhóm.',
                'duration' => '90',
                'release_date' => now()->addDays(13),
                'end_date' => null,
                'director' => 'Paul Thomas Anderson',
                'actors' => 'Leonardo DiCaprio, Sean Penn, Benicio Del Toro, Regina Hall, Teyana Taylor, Chase Infiniti',
                'age_restriction' => 'P',
                'poster' => 'https://iguov8nhvyobj.vcdn.cloud/media/catalog/product/cache/1/thumbnail/190x260/2e2b8cd282892c71872b9e67d2cb5039/v/n/vn_obaa_vert_tsr_leo_2764x4096_intl.jpg',
                'trailer_url' => 'https://youtu.be/XzLiVpfqXOg',
                'format' => '2D',
                'genres' => ['Hành Động', 'Hồi hộp', 'Tâm Lý']
            ],
            [
                'title' => 'NĂM CỦA ANH, NGÀY CỦA EM',
                'description' => 'Khi thế giới bị chia cắt thành 2 chiều không gian song song, tình yêu nảy nở giữa Hứa Quang Hán và Viên Lễ Lâm bị cuốn trôi theo hai nhịp sống khác biệt. Họ vẫn níu giữ sợi dây mong manh của định mệnh, cố tìm đến điểm giao nhau giữa hai thế giới để viết tiếp chuyện tình còn dang dở. Một bản tình ca lặng lẽ và day dứt, nơi Hứa Quang Hán nhẹ nhàng mang đến những nốt lặng bồi hồi, liệu tình yêu có đủ để vượt qua giới hạn của không gian và thời gian?',
                'duration' => '120',
                'release_date' => now()->addDays(13),
                'end_date' => null,
                'director' => 'Kung Siu Ping',
                'actors' => 'Hsu Kuang Han, Angela Yuen',
                'age_restriction' => 'P',
                'poster' => 'https://iguov8nhvyobj.vcdn.cloud/media/catalog/product/cache/1/thumbnail/190x260/2e2b8cd282892c71872b9e67d2cb5039/t/e/teaser_poster_b_-_measure_in_love.jpg',
                'trailer_url' => null,
                'format' => '2D',
                'genres' => ['Thần thoại', 'Tình cảm']
            ],
            [
                'title' => 'KẺ VÔ DANH 2',
                'description' => 'Bob Odenkirk trở lại với vai Hutch Mansell - người chồng, người cha sống ở vùng ngoại ô và cũng là sát thủ nghiện công việc trong chương mới của Nobody, bộ phim hành động - giật gân đầy ly kỳ từng có doanh thu mở màn đứng đầu phòng vé Hoa Kỳ vào năm 2021. Bốn năm sau khi vô tình đụng độ mafia Nga, Hutch vẫn còn nợ 30 triệu đô và đang trả dần bằng chuỗi nhiệm vụ ám sát không hồi kết nhắm vào tội phạm quốc tế. Dù phần nào yêu thích công việc bạo lực này, Hutch và vợ Becca bắt đầu kiệt sức và xa cách, nên quyết định tổ chức chuyến du lịch đến công viên nước để hâm nóng tình cảm gia đình.',
                'duration' => '90',
                'release_date' => now()->addDays(9),
                'end_date' => null,
                'director' => 'Timo Tjahjanto',
                'actors' => 'Bob Odenkirk, Connie Nielsen, John Ortiz, RZA, Colin Hanks, with Christopher Lloyd, Sharon Stone',
                'age_restriction' => 'T18',
                'poster' => 'https://iguov8nhvyobj.vcdn.cloud/media/catalog/product/cache/1/thumbnail/190x260/2e2b8cd282892c71872b9e67d2cb5039/n/b/nb2_poolposter_470x700.jpg',
                'trailer_url' => 'https://youtu.be/rTpeRfGM23M',
                'format' => '2D',
                'genres' => ['Hành Động', 'Hồi hộp']
            ],
            [
                'title' => 'TRẠI TU NUÔI QUỶ',
                'description' => 'Trong một lần trốn tránh cảnh sát, Issa và anh trai Tomas tìm nơi trú ẩn trong một trại trẻ mồ côi hẻo lánh giữa rừng. Nhưng nơi trú ẩn an toàn của hai anh em sớm lộ ra là một địa ngục trần gian do một giáo phái tế thần cai quản, phục tùng một vị thần đáng sợ chuyên hành động về đêm.',
                'duration' => '105',
                'release_date' => now()->addDays(9),
                'end_date' => null,
                'director' => 'Mikhail Red',
                'actors' => 'Heaven Peralejo, Skywalker David, Eula Valdez',
                'age_restriction' => 'T18',
                'poster' => 'https://iguov8nhvyobj.vcdn.cloud/media/catalog/product/cache/1/image/c5f0a1eff4c394a251036189ccddaacd/4/7/470wx700h-devilnun.jpg',
                'trailer_url' => 'https://youtu.be/9bfQHIOWomc',
                'format' => '2D',
                'genres' => ['Kinh Dị']
            ],
            [
                'title' => 'Phi Vụ Động Trời 2',
                'description' => 'Thám tử Judy Hopps và Nick Wilde dấn thân vào cuộc truy tìm bí ẩn về một loài bò sát bí ẩn đến Zootopia và khiến thành phố của các loài động vật có vú đảo lộn.',
                'duration' => '123',
                'release_date' => now()->addDays(3),
                'end_date' => null,
                'director' => 'Byron Howard',
                'actors' => 'Ginnifer Goodwin, Jason Bateman, Idris Elba, Jenny Slate, Maurice LaMarche, Quan Kế Huy, Quinta Brunson',
                'age_restriction' => 'P',
                'poster' => 'https://cinema.momocdn.net/img/77130590823187687-zootopia.png',
                'trailer_url' => 'https://youtu.be/BqP9oYa9MdI',
                'format' => '2D',
                'genres' => ['Gia đình', 'Phiêu lưu', 'Hài', 'Hoạt hình']
            ],
            [
                'title' => 'Những Kẻ Xấu Xa 2: Băng Đảng Quái Kiệt',
                'description' => 'Biệt đội Bad Guys đang cố gắng lấy lại sự tin tưởng của mọi người sau khi hoàn lương, nhưng mọi nỗ lực sụp đổ khi họ bị kéo vào "phi vụ cuối cùng" do nhóm Bad Girls cầm đầu. Liệu họ sẽ giữ vững lý tưởng chính nghĩa, hay lại bị cuốn vào con đường tội phạm đã từng cố gắng rời bỏ?',
                'duration' => '104',
                'release_date' => now()->addDays(3),
                'end_date' => null,
                'director' => 'Pierre Perifel',
                'actors' => 'Sam Rockwell, Marc Maron, Craig Robinson, Anthony Ramos, Richard Ayoade',
                'age_restriction' => 'P',
                'poster' => 'https://image.tmdb.org/t/p/w500/u4U5nYgxSFkefOQme6UBwy7NzNn.jpg',
                'trailer_url' => 'https://youtu.be/ql1mh0P6shE',
                'format' => '2D',
                'genres' => ['Gia đình', 'Phiêu lưu', 'Hài', 'Hoạt hình']
            ],
            [
                'title' => 'SHIN CẬU BÉ BÚT CHÌ: NHỮNG VŨ CÔNG SIÊU CAY KASUKABE',
                'description' => 'Lễ hội Giải trí Thiếu nhi Kasukabe chính thức được tổ chức. Và bất ngờ chưa, ban tổ chức thông báo rằng đội chiến thắng trong cuộc thi nhảy của lễ hội sẽ được mời sang Ấn Độ biểu diễn ngay trên sân khấu bản địa! Nghe vậy, Shin và Đội đặc nhiệm Kasukabe lập tức lên kế hoạch chinh phục giải thưởng và khởi hành sang Ấn Độ để “quẩy banh nóc”! Chuyến du lịch tưởng chừng chỉ có vui chơi ca hát lại rẽ hướng 180 độ khi Shin và Bo tình cờ lạc vào một tiệm tạp hóa bí ẩn giữa lòng Ấn Độ. Tại đây, cả hai bắt gặp một chiếc balo có hình dáng giống... cái mũi và cả hai quyết định mua về. Nhưng không ngờ, chiếc balo lại ẩn chứa một bí mật kỳ lạ. Trong lúc tò mò nghịch ngợm, Bo lỡ tay nhét một mảnh giấy kỳ lạ từ balo lên... mũi mình. Và thế là thảm họa bắt đầu!',
                'duration' => '105',
                'release_date' => now()->addDays(3),
                'end_date' => null,
                'director' => 'Masakazu Hashimoto',
                'actors' => 'Yumiko Kobayashi, Miki Narahashi, Toshiyuki Morikawa, Satomi Kōrogi',
                'age_restriction' => 'P',
                'poster' => 'https://iguov8nhvyobj.vcdn.cloud/media/catalog/product/cache/1/image/c5f0a1eff4c394a251036189ccddaacd/1/_/1.20wx1.80h.jpg',
                'trailer_url' => 'https://youtu.be/fh-35EBXCwo',
                'format' => '2D',
                'genres' => ['Gia đình', 'Hài', 'Hoạt hình', 'Phiêu Lưu']
            ],
            [
                'title' => 'TÔI CÓ BỆNH MỚI THÍCH CẬU',
                'description' => 'Giả ung thư để khỏi bị đuổi học, chàng nam sinh cấp 3 Tử Kiệt đâu ngờ rằng mình lại mắc bệnh tương tư Tử Khiết, cô nàng lớp trưởng thông minh, xinh đẹp nhưng lạnh lùng và nghi ngờ cậu đang “diễn sâu.”',
                'duration' => '110',
                'release_date' => now(),
                'end_date' => null,
                'director' => 'Hsu Fu Hsiang',
                'actors' => 'Zhan Huai Yun, Chiang Chi, Liu Hsiu Fu, Huang Guan Zhi',
                'age_restriction' => 'T13',
                'poster' => 'https://iguov8nhvyobj.vcdn.cloud/media/catalog/product/cache/1/image/c5f0a1eff4c394a251036189ccddaacd/3/5/350x495-lovesick.jpg',
                'trailer_url' => 'https://youtu.be/6llD9SRnuAQ',
                'format' => '2D',
                'genres' => ['Hài', 'Tình cảm']
            ],
            [
                'title' => 'Chốt Đơn',
                'description' => 'Chốt Đơn! xoay quanh cuộc sống tất bật của Hoàng Linh - nữ “chiến thần” chốt đơn trẻ đang thành công và chú An (Quyền Linh) một nhân viên văn phòng kiêm tài xế xe ôm công nghệ. Giữa những mơ hồ, thay đổi, áp lực của xã hội lên hai thế hệ, Hoàng Linh và chú An vốn đối lập từ tuổi tác, hoàn cảnh đến tính cách, lại tìm thấy một tình bạn thật trong sáng, khởi tạo nên những nhiệt huyết mới để mang đến những phiên “Chốt đơn!” thắng đậm.',
                'duration' => '112',
                'release_date' => now(),
                'end_date' => null,
                'director' => 'Bảo Nhân',
                'actors' => 'Quyền Linh, Hồng Đào, Hồng Vân, Khương Lê',
                'age_restriction' => 'T16',
                'poster' => 'https://cinema.momocdn.net/img/104992032433728327-chotdonfinal.png',
                'trailer_url' => 'https://youtu.be/KHfhsOeFR4w',
                'format' => '2D',
                'genres' => ['Gia đình', 'Tâm lý']
            ],
            [
                'title' => 'Tình Yêu và Cám Dỗ',
                'description' => 'Ahmed, 18 tuổi, là người Pháp gốc Algeria. Anh lớn lên ở vùng ngoại ô Paris. Trên giảng đường đại học, anh gặp Farah – một cô gái trẻ người Tunisia tràn đầy năng lượng, vừa mới chuyển đến từ Tunis. Khi cùng nhau khám phá kho tàng văn học Ả Rập đầy chất nhục cảm và gợi tình mà trước đây anh chưa từng biết tới, Ahmed say đắm yêu cô gái ấy.',
                'duration' => '102',
                'release_date' => now(),
                'end_date' => null,
                'director' => 'Leyla Bouzid',
                'actors' => 'Sami Outalbali, Zbeida Belhajamor, Mahia Zrouki, Mathilde La Musse, Samir Elhakim',
                'age_restriction' => 'T18',
                'poster' => 'https://image.tmdb.org/t/p/w500/x3nOXecxmDbvA7E4loCk5A8X23p.jpg',
                'trailer_url' => 'https://youtu.be/ShBbalC64qo',
                'format' => '2D',
                'genres' => ['Tình cảm', 'Lãng mạn']
            ],
            [
                'title' => 'Mang Mẹ Đi Bỏ',
                'description' => 'Mang Mẹ Đi Bỏ kể về số phận của Hoan (Tuấn Trần) - một chàng trai trẻ ngày ngày hóa thân thành “thằng hề đường phố” với ngón nghề cắt tóc vỉa hè để kiếm tiền lo cho mẹ. Mẹ Hoan là bà Lê Thị Hạnh (Hồng Đào), mắc bệnh Alzheimer và cư xử như một đứa trẻ con. Căn bệnh của mẹ không chỉ là gánh nặng mưu sinh, mà còn lấy đi của Hoan một cuộc đời tự do với những ước mơ chưa thể thực hiện.',
                'duration' => '113',
                'release_date' => now(),
                'end_date' => null,
                'director' => 'Mo Hong-jin',
                'actors' => 'Hồng Đào, Tuấn Trần, Jung Il-woo, Bảo Ngọc, Quốc Khánh, Hải Triều, Lâm Vỹ Dạ, Vinh Râu',
                'age_restriction' => 'K',
                'poster' => 'https://cinema.momocdn.net/img/89435418967485480-mangmedibo.png',
                'trailer_url' => 'https://youtu.be/yF2pXRJictA',
                'format' => '2D',
                'genres' => ['Gia đình', 'Tình cảm']
            ],
            [
                'title' => 'THANH GƯƠM DIỆT QUỶ: VÔ HẠN THÀNH',
                'description' => null,
                'duration' => '155',
                'release_date' => now(),
                'end_date' => null,
                'director' => 'Haruo Sotozaki',
                'actors' => 'Natsuki Hanae, Saori Hayami, Yoshitsugu Matsuoka',
                'age_restriction' => 'T16',
                'poster' => 'https://iguov8nhvyobj.vcdn.cloud/media/catalog/product/cache/1/image/c5f0a1eff4c394a251036189ccddaacd/p/o/poster_dm.jpg',
                'trailer_url' => 'https://youtu.be/BSkUboiyeCo',
                'format' => '2D',
                'genres' => ['Hành Động', 'Hoạt Hình']
            ],
            [
                'title' => 'Tội Đồ',
                'description' => 'Michael B. Jordan sẽ cùng khán giả chứng kiến những nghi lễ đầy ám ảnh, thách thức đức tin của con người. Trong Tội Đồ, Michael B. Jordan sẽ thủ vai kép khi cùng lúc thể hiện nhân vật hai anh em sinh đôi. Mắc kẹt trong cuộc sống thực tại rối ren, cả hai quay trở lại quê nhà để có một khởi đầu mới, để rồi phát hiện một thế lực ác quỷ đang nhăm nhe chờ đợi họ.',
                'duration' => '147',
                'release_date' => now(),
                'end_date' => null,
                'director' => 'Ryan Coogler',
                'actors' => 'Michael Jordan, Hailee Steinfeld, Wunmi Mosaku, Jayme Lawson, Delroy Lindo, Lola Kirke',
                'age_restriction' => 'T18',
                'poster' => 'https://cinema.momocdn.net/img/80094979744858665-sin.png',
                'trailer_url' => 'https://youtu.be/KQwChFH4YQk',
                'format' => '2D',
                'genres' => ['Hồi hộp', 'Kinh Dị', 'Tâm Lý']
            ],
            [
                'title' => 'Ma Không Đầu',
                'description' => 'Bộ đôi Tiến Luật và Ngô Kiến Huy, với nghề nghiệp "độc lạ" hốt xác và lái xe cứu thương, hứa hẹn mang đến những tràng cười không ngớt cho khán giả qua hành trình tìm xác có một không hai trên màn ảnh Việt. Nhờ sự trợ giúp của thế lực tâm linh, họ không chỉ đối mặt với những tình huống "dở khóc dở cười" mà còn khám phá ra những bí mật rợn người ẩn sau những thi thể.',
                'duration' => '119',
                'release_date' => now(),
                'end_date' => null,
                'director' => 'Bùi Văn Hải',
                'actors' => 'Tiến Luật, Ngô Kiến Huy, Hồng Vân, Hữu Châu, Đại Nghĩa, Thanh Hương',
                'age_restriction' => 'T18',
                'poster' => 'https://cinema.momocdn.net/img/45523730113337120-makhongdau.png',
                'trailer_url' => 'https://youtu.be/cz6OS1yQIwo',
                'format' => '2D',
                'genres' => ['Hài', 'Kinh Dị']
            ],
            [
                'title' => 'BÍ KÍP LUYỆN RỒNG',
                'description' => 'Câu chuyện về một chàng trai trẻ với ước mơ trở thành thợ săn rồng, nhưng định mệnh lại đưa đẩy anh đến tình bạn bất ngờ với một chú rồng.',
                'duration' => '126',
                'release_date' => now()->subDays(2),
                'end_date' => null,
                'director' => 'Dean DeBlois',
                'actors' => 'Mason Thames, Nico Parker, Gerard Butler',
                'age_restriction' => 'K',
                'poster' => 'https://iguov8nhvyobj.vcdn.cloud/media/catalog/product/cache/1/image/c5f0a1eff4c394a251036189ccddaacd/h/d/hdg_payoff_470x700.jpg',
                'trailer_url' => 'https://youtu.be/rvOaNwwDVZk',
                'format' => '2D',
                'genres' => ['Hài', 'Hành Động', 'Phiêu Lưu', 'Thần thoại']
            ],
            [
                'title' => 'XÌ TRUM',
                'description' => 'Câu chuyện trở lại với ngôi làng Xì Trum, nơi mà mỗi ngày đều là lễ hội. Bỗng một ngày, sự yên bình của ngôi làng bị phá vỡ khi Tí Vua bị bắt cóc một cách bí ẩn bởi hai phù thủy độc ác Gà Mên và Cà Mên. Từ đây, Tí Cô Nương phải dẫn dắt các Tí đi vào thế giới thực để giải cứu ông. Với sự giúp đỡ của những người bạn mới, các Tí sẽ bước vào cuộc phiêu lưu khám phá định mệnh của mình để cứu lấy vũ trụ.',
                'duration' => '92',
                'release_date' => now()->subDays(2),
                'end_date' => null,
                'director' => 'Chris Miller',
                'actors' => 'Rihanna, James Corden, Nick Offerman, Natasha Lyonne, Amy Sedaris, Nick Kroll, Daniel Levy, Octavia Spencer',
                'age_restriction' => 'P',
                'poster' => 'https://iguov8nhvyobj.vcdn.cloud/media/catalog/product/cache/1/image/c5f0a1eff4c394a251036189ccddaacd/7/0/700x1000-smurfs.jpg',
                'trailer_url' => 'https://youtu.be/wn-rx2oumsw',
                'format' => '2D',
                'genres' => ['Gia đình', 'Phiêu Lưu', 'Hài', 'Hoạt Hình']
            ],
            [
                'title' => 'PHÍA SAU VẾT MÁU',
                'description' => '5 vụ án mạng rúng động, một "trò chơi" sinh tử đầy uẩn khúc - và Cổ Thiên Lạc, trong vai thám tử tư, vô tình bị cuốn vào vòng xoáy kinh hoàng đó. Bí ẩn nối tiếp bí ẩn, ai sẽ là con mồi, ai mới là kẻ săn? Câu trả lời đang chờ được hé lộ…',
                'duration' => '103',
                'release_date' => now()->subDays(2),
                'end_date' => null,
                'director' => 'Lý Tử Tuấn',
                'actors' => 'Cổ Thiên Lạc, Trương Thiệu Huy, Châu Tú Na, Huỳnh Hạo',
                'age_restriction' => 'T18',
                'poster' => 'https://iguov8nhvyobj.vcdn.cloud/media/catalog/product/cache/1/image/c5f0a1eff4c394a251036189ccddaacd/4/7/470wx700h-bts.jpg',
                'trailer_url' => 'https://youtu.be/GORtGKJpLPM',
                'format' => '2D',
                'genres' => ['Kinh Dị', 'Tội phạm']
            ],
            [
                'title' => 'BỘ TỨ SIÊU ĐẲNG: BƯỚC ĐI ĐẦU TIÊN',
                'description' => 'Sau một chuyến bay thám hiểm vũ trụ, bốn phi hành gia bất ngờ sở hữu năng lực siêu nhiên và trở thành gia đình siêu anh hùng đầu tiên của Marvel. The Fantastic Four: First Steps là bộ phim mở đầu Kỷ nguyên anh hùng thứ sáu (Phase Six), đặt nền móng cho siêu bom tấn Avengers: Doomsday trong năm sau.',
                'duration' => '115',
                'release_date' => now()->subDays(5),
                'end_date' => null,
                'director' => 'Matt Shakman',
                'actors' => 'Pedro Pascal, Vanessa Kirby, Joseph Quinn, Ebon Moss-Bachrach, Ralph Ineson, Julia Garner, Paul Walter Hauser, John Malkovich',
                'age_restriction' => 'T13',
                'poster' => 'https://iguov8nhvyobj.vcdn.cloud/media/catalog/product/cache/1/image/c5f0a1eff4c394a251036189ccddaacd/c/g/cgv_350x495.jpg',
                'trailer_url' => 'https://youtu.be/h80i_zRnwog',
                'format' => '2D',
                'genres' => ['Hành Động', 'Khoa Học Viễn Tưởng', 'Phiêu Lưu']
            ],
            [
                'title' => 'THÁM TỬ LỪNG DANH CONAN: DƯ ẢNH CỦA ĐỘC NHÃN',
                'description' => 'Trên những ngọn núi tuyết của Nagano, một vụ án bí ẩn đã đưa Conan và các thám tử quay trở lại quá khứ. Thanh tra Yamato Kansuke - người đã bị thương nặng trong một trận tuyết lở nhiều năm trước - bất ngờ phải đối mặt với những ký ức đau thương của mình trong khi điều tra một vụ tấn công tại Đài quan sát Nobeyama.',
                'duration' => '109',
                'release_date' => now()->subDays(5),
                'end_date' => null,
                'director' => 'Katsuya Shigehara',
                'actors' => 'Minami Takayama, Wakana Yamazaki, Rikiya Koyama, Megumi Hayashibara',
                'age_restriction' => 'K',
                'poster' => 'https://iguov8nhvyobj.vcdn.cloud/media/catalog/product/cache/1/image/c5f0a1eff4c394a251036189ccddaacd/2/6/260625_poster_conan_6.jpg',
                'trailer_url' => 'https://youtu.be/dz5mN-iIC4g',
                'format' => '2D',
                'genres' => ['Hành Động', 'Hoạt Hình']
            ],
            [
                'title' => 'TIẾNG ỒN QUỶ DỊ',
                'description' => 'Sau khi dọn vào căn hộ mới, hai chị em Joo-Young (Lee Sun-Bin) và Joo-Hee (Han Su-A) liên tục bị quấy nhiễu bởi những tiếng động kỳ quái phát ra từ giữa các tầng – thứ âm thanh âm ỉ, vặn vẹo như thể có ai đó… hoặc thứ gì đó đang sống giữa các bức tường. Rồi một ngày, Joo-Hee biến mất không dấu vết. Joo-Young cùng bạn trai của em gái lao vào cuộc truy tìm trong vô vọng – khi càng đào sâu, họ càng tiến gần đến một bí mật đen tối bị chôn vùi sau những bức tường cách âm.',
                'duration' => '94',
                'release_date' => now()->subDays(7),
                'end_date' => null,
                'director' => 'Kim Soo-Jin',
                'actors' => 'Lee Sun-bin, Han Soo-a, Kim Min-Seok',
                'age_restriction' => 'T18',
                'poster' => 'https://iguov8nhvyobj.vcdn.cloud/media/catalog/product/cache/1/image/c5f0a1eff4c394a251036189ccddaacd/4/7/470wx700h-noise.jpg',
                'trailer_url' => 'https://youtu.be/oPg5Nhdy4H4',
                'format' => '2D',
                'genres' => ['Hồi hộp', 'Kinh Dị']
            ],
            [
                'title' => 'Điều Ước Cuối Cùng',
                'description' => 'Biết mình không còn sống được bao lâu vì căn bệnh ALS, Hoàng tâm sự với hai người bạn thân – Thy và Long – về tâm nguyện cuối cùng: được “mất zin” trước khi chết. Hành trình giúp Hoàng thực hiện điều ước ấy đưa họ qua những tình huống dở khóc dở cười, đồng thời thử thách tình bạn, tình thân và ý nghĩa của tình yêu thương vô điều kiện.',
                'duration' => '114',
                'release_date' => now()->subDays(7),
                'end_date' => null,
                'director' => 'Đoàn Sĩ Nguyên',
                'actors' => 'Tiến Luật, Lý Hạo Mạnh Quỳnh, Hoàng Hà, Đinh Y Nhung, Lương Anh Vũ',
                'age_restriction' => 'T16',
                'poster' => 'https://cinema.momocdn.net/img/132123530917994983-mattrinh.png',
                'trailer_url' => 'https://youtu.be/VhY3F96vv3M',
                'format' => '2D',
                'genres' => ['Hài', 'Lãng mạn']
            ],
        ];

        foreach ($data as $movie) {
            $dataGenres = $movie['genres'];
            $dataGenres = array_map(fn($genre) => Genre::firstOrCreate(['name' => mb_ucfirst($genre, 'UTF-8')])->id, $dataGenres);
            unset($movie['genres']);
            $dataPoster = file_get_contents($movie['poster'], false, stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'ignore_errors' => true
                ],
            ]));

            $poster = "movies/" . uniqid('poster_') . basename($movie['poster']);
            Storage::drive('public')->put($poster, $dataPoster);

            $movie = Movie::create(array_merge($movie, ['title' => mb_convert_case($movie['title'], MB_CASE_TITLE_SIMPLE, 'UTF-8'), 'poster' => $poster, 'price' => fake()->numberBetween(10000, 80000), 'status' => $movie['release_date']->isFuture() ? 'coming_soon' : 'showing']));

            $movie->genres()->attach($dataGenres);
        }
    }
}
