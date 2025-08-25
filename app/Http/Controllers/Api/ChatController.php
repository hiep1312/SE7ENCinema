<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Showtime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    public function __construct()
    {
        request()->headers->set('Accept', 'application/json');
    }

    public function sendAIMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:1000',
            'session_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Dữ liệu không hợp lệ',
                'details' => $validator->errors()
            ], 400);
        }

        try {
            // Lưu lịch sử chat
            $this->saveChatMessage($request->session_id, 'user', $request->message);

            // Gọi AI API sẽ được xử lý ở frontend
            return response()->json([
                'success' => true,
                'message' => 'Message received',
                'session_id' => $request->session_id
            ]);
        } catch (\Exception $e) {
            Log::error('Chat AI Error', [
                'error' => $e->getMessage(),
                'session_id' => $request->session_id
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Lỗi hệ thống, vui lòng thử lại sau'
            ], 500);
        }
    }

    public function requestStaff(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_id' => 'required|string',
            'customer_info' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Dữ liệu không hợp lệ'
            ], 400);
        }

        try {
            // Thêm session vào hàng đợi hỗ trợ
            $sessionData = [
                'session_id' => $request->session_id,
                'customer_info' => $request->customer_info,
                'requested_at' => now(),
                'status' => 'pending',
                'priority' => 'normal'
            ];

            // Lưu vào cache với TTL 1 giờ
            Cache::put("support_request_{$request->session_id}", $sessionData, 3600);

            // Thêm vào danh sách session đang chờ
            $pendingSessions = Cache::get('pending_customer_sessions', []);
            $pendingSessions[$request->session_id] = $sessionData;
            Cache::put('pending_customer_sessions', $pendingSessions, 3600);

            return response()->json([
                'success' => true,
                'message' => 'Đã gửi yêu cầu hỗ trợ',
                'session_id' => $request->session_id,
                'queue_position' => count($pendingSessions)
            ]);
        } catch (\Exception $e) {
            Log::error('Staff Request Error', [
                'error' => $e->getMessage(),
                'session_id' => $request->session_id
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Không thể kết nối với hệ thống hỗ trợ'
            ], 500);
        }
    }

    public function getMovies(Request $request)
    {
        try {
            // Đảm bảo response là JSON
            $request->headers->set('Accept', 'application/json');

            $movies = Movie::where('status', 'showing')
                ->with(['genres', 'showtimes' => function ($query) {
                    // Sử dụng start_time thay vì show_date
                    $query->where('start_time', '>=', now())
                        ->where('status', '!=', 'completed')
                        ->orderBy('start_time', 'asc');
                }])
                ->orderBy('release_date', 'desc')
                ->get();

            $movieData = $movies->map(function ($movie) {
                return [
                    'title' => $movie->title,
                    'description' => $movie->description,
                    'duration' => $movie->duration,
                    'release_date' => $movie->release_date,
                    'end_date' => $movie->end_date,
                    'director' => $movie->director,
                    'actors' => $movie->actors,
                    'age_restriction' => $movie->age_restriction,
                    'poster_url' => $movie->poster_url,
                    'trailer_url' => $movie->trailer_url,
                    'format' => $movie->format,
                    'price' => $movie->price,
                    'has_showtimes' => $movie->showtimes->count() > 0,
                    'next_showtime' => $movie->showtimes->first()?->start_time?->format('Y-m-d')
                ];
            });

            // Xóa dd() - đây là nguyên nhân gây lỗi 500!
            // dd($movieData);

            return response()->json([
                'success' => true,
                'data' => $movieData->toArray(),
                'total' => $movieData->count()
            ], 200, [
                'Content-Type' => 'application/json; charset=utf-8'
            ]);
        } catch (\Exception $e) {
            Log::error('Get Movies Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Không thể tải danh sách phim',
                'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500, [
                'Content-Type' => 'application/json; charset=utf-8'
            ]);
        }
    }

    public function getShowtimes(Request $request, $movieId)
    {
        try {
            $movie = Movie::findOrFail($movieId);

            // Sử dụng schema thực tế từ model Showtime
            $showtimes = Showtime::where('movie_id', $movieId)
                ->where('start_time', '>=', now())
                ->where('status', '!=', 'completed')
                ->with(['room']) // Sử dụng 'room' thay vì 'cinema_room'
                ->orderBy('start_time', 'asc')
                ->get();

            // Group by date từ start_time
            $showtimeData = $showtimes->groupBy(function ($showtime) {
                return $showtime->start_time->format('Y-m-d');
            })->map(function ($dayShowtimes, $date) {
                return [
                    'date' => $date,
                    'date_formatted' => \Carbon\Carbon::parse($date)->format('d/m/Y'),
                    'day_name' => \Carbon\Carbon::parse($date)->locale('vi')->dayName,
                    'showtimes' => $dayShowtimes->map(function ($showtime) {
                        return [
                            'id' => $showtime->id,
                            'start_time' => $showtime->start_time->format('H:i'),
                            'end_time' => $showtime->end_time->format('H:i'),
                            'room_name' => $showtime->room->name ?? 'N/A',
                            'room_capacity' => $showtime->room->capacity ?? 0,
                            // Tính toán available seats từ bookings
                            'available_seats' => $this->calculateAvailableSeats($showtime),
                            'status' => $showtime->status
                        ];
                    })->toArray()
                ];
            })->values();

            return response()->json([
                'success' => true,
                'data' => [
                    'movie' => [
                        'id' => $movie->id,
                        'title' => $movie->title,
                        'duration' => $movie->duration,
                        'rating' => $movie->rating ?? null
                    ],
                    'showtimes' => $showtimeData
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Get Showtimes Error', [
                'error' => $e->getMessage(),
                'movie_id' => $movieId
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Không thể tải lịch chiếu'
            ], 500);
        }
    }

    /**
     * Tính toán số ghế còn trống cho một showtime
     */
    private function calculateAvailableSeats(Showtime $showtime)
    {
        if (!$showtime->room) {
            return 0;
        }

        $totalSeats = $showtime->room->capacity ?? 0;
        $bookedSeats = $showtime->booking()->count();
        $heldSeats = $showtime->getActiveHolds()->count();

        return max(0, $totalSeats - $bookedSeats - $heldSeats);
    }

    protected function saveChatMessage($sessionId, $type, $message, $metadata = [])
    {
        $chatHistory = Cache::get("chat_history_{$sessionId}", []);

        $chatHistory[] = [
            'id' => uniqid(),
            'type' => $type,
            'message' => $message,
            'metadata' => $metadata,
            'timestamp' => now()->toISOString()
        ];

        // Giới hạn lịch sử chat (chỉ giữ 100 tin nhắn gần nhất)
        if (count($chatHistory) > 100) {
            $chatHistory = array_slice($chatHistory, -100);
        }

        Cache::put("chat_history_{$sessionId}", $chatHistory, 3600); // 1 hour
    }
}
