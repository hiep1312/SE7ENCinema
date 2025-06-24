<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chi Tiết Phim</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <!-- <style>
        body {
            background: #0d0d0d;
            color: #eaeaea;
            font-family: 'Roboto', sans-serif;
        }
        .header-section {
            background: #1a1a1a;
            padding: 40px 0;
            text-align: center;
            border-bottom: 3px solid #e50914;
        }
        .header-section h1 {
            color: #fff;
            font-size: 2.8rem;
            font-weight: bold;
            text-transform: uppercase;
        }
        .nav-pills .nav-link {
            background: #2a2a2a;
            color: #eaeaea;
            margin-right: 10px;
            border-radius: 30px;
            font-weight: 600;
        }
        .nav-pills .nav-link.active {
            background: #e50914;
            color: #fff;
        }
        .filter-group {
            background: #1e1e1e;
            padding: 10px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
        }
        .filter-group label {
            margin-right: 15px;
            color: #fff;
        }
        .filter-group select {
            background: #333;
            color: #fff;
            border: none;
            padding: 6px 15px;
            border-radius: 8px;
        }
        .card {
            background: #1c1c1c;
            border: 1px solid #444;
            border-radius: 10px;
            transition: 0.3s;
        }
        .card:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 20px rgba(0,0,0,0.7);
        }
        .card-img-top {
            height: 320px;
            object-fit: cover;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .card-body {
            padding: 15px;
        }
        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #fff;
        }
        .movie-info p {
            font-size: 0.9rem;
            margin-bottom: 5px;
            color: #ccc;
        }
        .movie-info p strong {
            color: #fff;
        }
        .badge {
            font-size: 0.75rem;
            padding: 6px 12px;
            font-weight: 600;
            border-radius: 20px;
        }
        .footer {
            padding: 30px 0;
            text-align: center;
            color: #777;
            border-top: 1px solid #444;
            margin-top: 40px;
        }
        @media (max-width: 768px) {
            .card-img-top {
                height: 200px;
            }
        }
    </style> -->
    <style>
    body {
        background: #0a0a0a;
        color: #f0f0f0;
        font-family: 'Roboto', sans-serif;
    }

    .header-section {
        background: linear-gradient(to right, #1a1a1a, #2c2c2c);
        padding: 50px 0 30px;
        text-align: center;
        border-bottom: 4px solid #e50914;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.8);
    }

    .header-section h1 {
        color: #fff;
        font-size: 3rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        text-shadow: 2px 2px 8px rgba(229, 9, 20, 0.6);
    }

    .nav-pills .nav-link {
        background-color: #2c2c2c;
        color: #ccc;
        font-weight: 600;
        border-radius: 25px;
        padding: 10px 25px;
        margin-right: 10px;
        transition: 0.3s;
        border: 1px solid #444;
    }

    .nav-pills .nav-link.active {
        background: linear-gradient(90deg, #e50914, #ff1e56);
        color: #fff;
        box-shadow: 0 5px 15px rgba(229, 9, 20, 0.5);
        border: none;
    }

    .filter-group {
        background: #1f1f1f;
        padding: 12px 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        box-shadow: inset 0 0 5px rgba(255, 255, 255, 0.05);
    }

    .filter-group label {
        margin-right: 15px;
        font-weight: 500;
        color: #f0f0f0;
    }

    .filter-group select {
        background: #2e2e2e;
        color: #fff;
        border: 1px solid #555;
        padding: 6px 15px;
        border-radius: 8px;
        font-weight: 500;
        transition: 0.2s;
    }

    .card {
        background: #191919;
        border: 1px solid #333;
        border-radius: 12px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-6px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.6);
    }

    .card-img-top {
        height: 320px;
        object-fit: cover;
        transition: 0.3s;
    }

    .card:hover .card-img-top {
        filter: brightness(85%);
    }

    .card-body {
        padding: 18px;
    }

    .card-title {
        font-size: 1.3rem;
        font-weight: bold;
        color: #fff;
        margin-bottom: 10px;
    }

    .movie-info p {
        font-size: 0.95rem;
        color: #ccc;
        margin-bottom: 6px;
    }

    .movie-info p strong {
        color: #fff;
    }

    .badge {
        font-size: 0.75rem;
        padding: 5px 12px;
        font-weight: 600;
        border-radius: 20px;
    }

    .badge.bg-warning {
        background: #ffc107;
        color: #000;
    }

    .badge.bg-success {
        background: #28a745;
    }

    .footer {
        text-align: center;
        padding: 30px 0;
        color: #888;
        border-top: 1px solid #333;
        margin-top: 50px;
        font-size: 0.95rem;
        background: #111;
    }

    @media (max-width: 768px) {
        .card-img-top {
            height: 200px;
        }

        .header-section h1 {
            font-size: 2rem;
        }

        .filter-group {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .nav-pills {
            flex-wrap: wrap;
        }
    }
</style>

</head>
<body>
    <div class="container py-4">
        <div class="header-section">
            <h1>Chi Tiết Phim</h1>
        </div>

        <div class="filter-group mt-4">
            <label for="status">Lọc theo trạng thái:</label>
            <select id="status" class="form-select w-auto">
                <option value="all">Tất cả</option>
                <option value="coming_soon">Phim sắp chiếu</option>
                <option value="showing">Phim đang chiếu</option>
                <option value="ended">Ngừng chiếu</option>
            </select>
        </div>

        <ul class="nav nav-pills mb-3" id="movieTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="coming_soon-tab" data-bs-toggle="pill" data-bs-target="#coming_soon" type="button">Phim sắp chiếu</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="showing-tab" data-bs-toggle="pill" data-bs-target="#showing" type="button">Phim đang chiếu</button>
            </li>
        </ul>

        <div class="tab-content" id="movieTabContent">
            <div class="tab-pane fade show active" id="coming_soon">
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    @foreach ($movies->where('status', 'coming_soon') as $movie)
                        <div class="col" data-status="coming_soon">
                            <div class="card">
                                <img src="{{ asset('storage/' . ($movie->poster ?? '404.webp')) }}" class="card-img-top" alt="{{ $movie->title }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $movie->title }}</h5>
                                    <div class="movie-info">
                                        <p><strong>Ngày chiếu:</strong> {{ $movie->release_date }}</p>
                                        <p><strong>Thời lượng:</strong> {{ $movie->duration }} phút</p>
                                        <p><strong>Đạo diễn:</strong> {{ $movie->director }}</p>
                                        <p><strong>Diễn viên:</strong> {{ $movie->actors }}</p>
                                        <p><strong>Định dạng:</strong> {{ $movie->format }}</p>
                                        <p><strong>Giá vé:</strong> {{ number_format($movie->price) }} VNĐ</p>
                                        <p><strong>Giới hạn tuổi:</strong> {{ $movie->age_restriction }}</p>
                                    </div>
                                    <span class="badge bg-warning text-dark">coming_soon</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="tab-pane fade" id="showing">
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    @foreach ($movies->where('status', 'showing') as $movie)
                        <div class="col" data-status="showing">
                            <div class="card">
                                <img src="{{ asset('storage/' . ($movie->poster ?? '404.webp')) }}" class="card-img-top" alt="{{ $movie->title }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $movie->title }}</h5>
                                    <div class="movie-info">
                                        <p><strong>Ngày chiếu:</strong> {{ $movie->release_date }}</p>
                                        <p><strong>Thời lượng:</strong> {{ $movie->duration }} phút</p>
                                        <p><strong>Đạo diễn:</strong> {{ $movie->director }}</p>
                                        <p><strong>Diễn viên:</strong> {{ $movie->actors }}</p>
                                        <p><strong>Định dạng:</strong> {{ $movie->format }}</p>
                                        <p><strong>Giá vé:</strong> {{ number_format($movie->price) }} VNĐ</p>
                                        <p><strong>Giới hạn tuổi:</strong> {{ $movie->age_restriction }}</p>
                                    </div>
                                    <span class="badge bg-success">Đang chiếu</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="footer">
            <p>Cập nhật lúc: {{ date('H:i d-m-Y') }} (Giờ Việt Nam)</p>
            <!-- Current time: 07:34 PM +07, Tuesday, June 24, 2025 -->
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('status').addEventListener('change', function() {
            const status = this.value;
            const tabs = document.querySelectorAll('.tab-pane');
            tabs.forEach(tab => {
                const movies = tab.querySelectorAll('.col');
                movies.forEach(movie => {
                    const movieStatus = movie.getAttribute('data-status');
                    if (status === 'all' || status === movieStatus) {
                        movie.style.display = 'block';
                    } else {
                        movie.style.display = 'none';
                    }
                });
                // Đảm bảo tab phù hợp với trạng thái được chọn
                if (status === 'all' || status === 'coming_soon') {
                    document.querySelector('#coming_soon-tab').classList.add('active');
                    document.querySelector('#coming_soon').classList.add('show', 'active');
                    document.querySelector('#showing-tab').classList.remove('active');
                    document.querySelector('#showing').classList.remove('show', 'active');
                } else if (status === 'showing') {
                    document.querySelector('#showing-tab').classList.add('active');
                    document.querySelector('#showing').classList.add('show', 'active');
                    document.querySelector('#coming_soon-tab').classList.remove('active');
                    document.querySelector('#coming_soon').classList.remove('show', 'active');
                } else if (status === 'ended') {
                    // Ẩn cả hai tab nếu chọn "Ngừng chiếu" (giả định không có phim trong trạng thái này)
                    tabs.forEach(t => t.classList.remove('show', 'active'));
                }
            });
        });
    </script>
</body>
</html>