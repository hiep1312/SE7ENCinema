<?php

namespace App\Livewire\Admin\Movies;

use App\Models\Genre;
use App\Models\Movie;
use App\Models\Room;
use App\Models\Showtime;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use SE7ENCinema\scAlert;

class MovieEdit extends Component
{
    use WithFileUploads, scAlert;

    public $movieItem;
    public $title = '';
    public $description = null;
    public $duration = null;
    public $release_date = null;
    public $end_date = null;
    public $director = null;
    public $actors = null;
    public $age_restriction = 'P';
    public $poster = null;
    public $trailer_url = null;
    public $format = '2D';
    public $price = null;
    public $formattedPrice = null;
    public $status = "showing";

    /* Genre */
    public $searchGenre = '';
    public $genresSelected = [];

    /* Tab */
    public $tabCurrent = 'showtimes';

    /* Showtime */
    public $baseShowtimeStart = null;
    public $baseShowtimeEnd = null;
    public $baseRoom = null;
    public $showtimes = [];

    /* Modal type */
    public $modalType = null;
    public $searchModal = '';
    public $modalSelected = null;

    protected function rules(){
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'release_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:release_date',
            'director' => 'nullable|string|max:255',
            'actors' => 'nullable|string',
            'age_restriction' => 'required|in:P,K,T13,T16,T18,C',
            'poster' => 'nullable|image|max:20480',
            'trailer_url' => 'nullable|url',
            'format' => 'required|in:2D,3D,4DX,IMAX',
            'price' => 'required|integer|min:0',
            'status' => 'required|in:coming_soon,showing,ended',

            'genresSelected.*' => 'integer|exists:genres,id',

            'showtimes.*.room_id' => 'required|integer|exists:rooms,id',
            'showtimes.*.start_time' => 'required|date_format:Y-m-d\TH:i|after_or_equal:now|after_or_equal:release_date',
            'showtimes.*.status' => 'required|in:active,canceled',
        ];

        if($this->end_date !== $this->movieItem->end_date?->format('Y-m-d\TH:i')) $rules['end_date'] .= '|after_or_equal:now';
        if($this->duration && $this->end_date) $rules['showtimes.*.start_time'] .= "|before:" . Carbon::parse($this->end_date)->subMinutes(+$this->duration);

        return $rules;
    }

    protected $messages = [
        'title.required' => 'Vui lòng nhập tiêu đề phim.',
        'title.max' => 'Tiêu đề phim không được vượt quá 255 ký tự.',
        'duration.required' => 'Vui lòng nhập thời lượng phim.',
        'duration.integer' => 'Thời lượng phim phải là một số nguyên.',
        'duration.min' => 'Thời lượng phim phải lớn hơn 0 phút.',
        'release_date.required' => 'Vui lòng chọn ngày khởi chiếu.',
        'release_date.date' => 'Ngày khởi chiếu không hợp lệ.',
        'end_date.date' => 'Ngày kết thúc không hợp lệ.',
        'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày khởi chiếu, và ngày hiện tại.',
        'director.max' => 'Tên đạo diễn không được vượt quá 255 ký tự.',
        'age_restriction.required' => 'Vui lòng chọn độ tuổi giới hạn.',
        'age_restriction.in' => 'Giá trị độ tuổi không hợp lệ.',
        'poster.image' => 'Ảnh poster phải là một hình ảnh hợp lệ (jpg, png, v.v).',
        'poster.max' => 'Dung lượng ảnh không được vượt quá 20MB.',
        'trailer_url.url' => 'Đường dẫn trailer không hợp lệ.',
        'format.required' => 'Vui lòng chọn định dạng phim.',
        'format.in' => 'Định dạng phim không hợp lệ.',
        'price.required' => 'Vui lòng nhập giá vé.',
        'price.integer' => 'Giá vé phải là một số.',
        'price.min' => 'Giá vé không được âm.',
        'status.required' => 'Vui lòng chọn trạng thái phim.',
        'status.in' => 'Trạng thái phim không hợp lệ.',

        'genresSelected.*.integer' => 'ID thể loại không hợp lệ.',
        'genresSelected.*.exists' => 'Một hoặc nhiều thể loại đã chọn không tồn tại.',

        'showtimes.*.room_id.required' => 'Vui lòng chọn phòng chiếu.',
        'showtimes.*.room_id.integer' => 'ID phòng chiếu không hợp lệ.',
        'showtimes.*.room_id.exists' => 'Phòng chiếu được chọn không tồn tại.',
        'showtimes.*.start_time.required' => 'Vui lòng chọn khung giờ chiếu.',
        'showtimes.*.start_time.date_format' => 'Khung giờ chiếu không hợp lệ.',
        'showtimes.*.start_time.after_or_equal' => 'Khung giờ chiếu phải lớn hơn hoặc bằng thời điểm hiện tại, và ngày khởi chiếu phim.',
        'showtimes.*.start_time.before' => 'Khung giờ chiếu phải trước ngày kết thúc phim.',
        'showtimes.*.status.required' => 'Vui lòng chọn trạng thái cho suất chiếu.',
        'showtimes.*.status.in' => 'Trạng thái suất chiếu không hợp lệ. Chỉ chấp nhận: đang chiếu hoặc đã huỷ.',
    ];

    public function mount(Movie $movie){
        $this->movieItem = $movie;
        $this->fill($movie->only('title', 'description', 'duration', 'director', 'actors', 'age_restriction', 'trailer_url', 'format', 'price', 'status') + ['release_date' => $movie->release_date->format('Y-m-d'), 'end_date' => $movie->end_date?->format('Y-m-d'), 'formattedPrice' => number_format($movie->price, 0, '.', '.')]);

        $this->genresSelected = $movie->genres()->pluck('genres.id')->toArray();

        $this->showtimes = $movie->showtimes()->where([
            ['movie_id', $movie->id],
            ['start_time', '>', now()],
        ])->get()->map(fn($showtime) => [
            'id' => $showtime->id,
            'room_id' => $showtime->room_id,
            'start_time' => $showtime->start_time->format('Y-m-d\TH:i'),
            'status' => $showtime->status,
        ])->toArray();
    }

    public function updatedFormattedPrice(){
        $this->price = str_replace([',', '.'], '', $this->formattedPrice);
    }

    public function toggleShowtime(?int $index = null)
    {
        if(isset($index)) $this->showtimes = array_values(array_filter($this->showtimes, fn($i) => $i !== $index, ARRAY_FILTER_USE_KEY));
        else $this->showtimes[] = [
            'room_id' => null,
            'start_time' => '',
            'status' => 'active',
        ];
    }

    public function generateShowtimes(){
        $this->validate([
            'baseShowtimeStart' => 'required|date_format:Y-m-d\TH:i|after_or_equal:now',
            'baseShowtimeEnd' => 'nullable|date_format:Y-m-d\TH:i|after:baseShowtimeStart',
            'baseRoom' => 'nullable|integer|exists:rooms,id',
        ], [
            'baseShowtimeStart.required' => 'Vui lòng nhập thời gian bắt đầu chiếu.',
            'baseShowtimeStart.date_format' => 'Thời gian bắt đầu chiếu phải có định dạng đúng: YYYY-MM-DDTHH:MM.',
            'baseShowtimeStart.after_or_equal' => 'Thời gian bắt đầu chiếu phải lớn hơn hoặc bằng thời điểm hiện tại.',
            'baseShowtimeEnd.date_format' => 'Thời gian kết thúc chiếu phải có định dạng đúng: YYYY-MM-DDTHH:MM.',
            'baseShowtimeEnd.after' => 'Thời gian kết thúc chiếu phải sau thời gian bắt đầu chiếu.',
            'baseRoom.integer' => 'ID phòng chiếu không hợp lệ.',
            'baseRoom.exists' => 'Phòng chiếu được chọn không tồn tại.',
        ]); $this->validateOnly('duration');

        $startTime = Carbon::parse($this->baseShowtimeStart);
        $endTime = $this->baseShowtimeEnd ? Carbon::parse($this->baseShowtimeEnd) : $startTime->copy()->endOfDay();
        $movieDuration = +$this->duration;
        $roomId = $this->baseRoom;
        $currentShowtimes = collect(array_map(function($showtime) use ($movieDuration){
            return array_merge($showtime, ['start_time' => ($startTimeTimestamp = strtotime($showtime['start_time'])), 'end_time' => strtotime("+{$movieDuration} minutes", $startTimeTimestamp)]);
        }, $this->showtimes));

        $existingShowtimes = !is_null($roomId) ? Showtime::select('room_id', 'start_time', 'end_time')->where('room_id', $roomId)
            ->where('status', 'active')
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->get()->map(function($showtime) {
                return [
                    'room_id' => $showtime->room_id,
                    'start_time' => $showtime->start_time->timestamp,
                    'end_time' => $showtime->end_time->timestamp,
                ];
            })->keyBy('start_time') : [];

        $generatedShowtimes = [];

        while ($startTime->lessThan($endTime) && count($generatedShowtimes) < 50) {
            $showtimeEndTime = $startTime->copy()->addMinutes($movieDuration);
            if ($showtimeEndTime->greaterThan($endTime)) break;

            $query = is_null($roomId) ? $currentShowtimes->whereNull('room_id') : $currentShowtimes->where('room_id', $roomId)->merge($existingShowtimes);
            $duplicateShowtime = $query->where(fn($showtime) => $showtime['start_time'] < $showtimeEndTime->timestamp && $showtime['end_time'] > $startTime->timestamp)->first();
            if(isset($duplicateShowtime)){
                $startTime = Carbon::createFromTimestamp($duplicateShowtime['start_time'], 7)->addMinutes((($duplicateShowtime['end_time'] - $duplicateShowtime['start_time']) / 60) + 10);
                continue;
            }

            $generatedShowtimes[] = [
                'room_id' => $roomId,
                'start_time' => $startTime->format('Y-m-d\TH:i'),
                'status' => 'active',
            ];

            $startTime = $showtimeEndTime->addMinutes(10);
        }

        if (empty($generatedShowtimes)) {
            session()->flash('errorGeneratedShowtimes', 'Không thể tạo suất chiếu với khung thời gian đã chọn!');
            return;
        }

        $this->showtimes = array_merge($this->showtimes, $generatedShowtimes);
        usort($this->showtimes, fn($valueA, $valueB) => strtotime($valueA['start_time']) - strtotime($valueB['start_time']));

        session()->flash('successGeneratedShowtimes', "Đã tạo thành công " . count($generatedShowtimes) . " suất chiếu!");
    }

    public function addGenre(){
        $this->validate([
            'searchGenre' => 'required|string|max:255',
        ], [
            'searchGenre.required' => 'Tên thể loại là bắt buộc.',
            'searchGenre.string' => 'Tên thể loại phải là một chuỗi ký tự.',
            'searchGenre.max' => 'Tên thể loại không được vượt quá 255 ký tự.',
        ]);

        $genreAdded = Genre::create(['name' => $this->searchGenre]);
        $this->genresSelected[] = $genreAdded->id;
    }

    public function setData(){
        $modalText = $this->modalType==="director" ? 'đạo diễn' : 'diễn viên';
        if(empty($this->modalSelected)) $this->scAlert("Chưa chọn {$modalText}", "Bạn chưa chọn {$modalText} nào. Hệ thống sẽ tiếp tục mà không cập nhật {$modalText}.", 'warning', '');
        else $this->{$this->modalType} = $this->modalType==="director" ? $this->modalSelected : implode(', ', $this->actors ? collect(array_unique(array_merge($this->modalSelected, array_map(fn($actor) => trim($actor), explode(',', $this->actors ?? '')))))->sort()->toArray() : $this->modalSelected);
    }

    public function resetModal(){
        $this->reset(['modalType', 'searchModal', 'modalSelected']);
    }

    public function updateMovie()
    {
        $this->validate();

        $imagePath = $this->movieItem->poster;
        if($this->poster && $this->poster instanceof UploadedFile):
            !Storage::disk('public')->exists($imagePath) ?: Storage::disk('public')->delete($imagePath);
            $imagePath = $this->poster->store('movies', 'public');
        endif;

        $this->movieItem->update([
            'title' => $this->title,
            'description' => $this->description,
            'duration' => $this->duration,
            'release_date' => $this->release_date,
            'end_date' => $this->end_date,
            'director' => $this->director,
            'actors' => $this->actors,
            'age_restriction' => $this->age_restriction,
            'poster' => $imagePath,
            'trailer_url' => $this->trailer_url,
            'format' => $this->format,
            'price' => $this->price,
            'status' => $this->status,
        ]);

        $this->movieItem->genres()->sync($this->genresSelected);

        $currentShowtimes = $this->movieItem->showtimes->pluck('id')->toArray();

        foreach ($this->showtimes as $showtime) {
            $dataShowtime = [
                'movie_id' => $this->movieItem->id,
                'room_id' => $showtime['room_id'],
                'start_time' => $showtime['start_time'],
                'end_time' => Carbon::parse($showtime['start_time'])->addMinutes(+$this->duration),
                'status' => $showtime['status'],
            ];

            if(isset($showtime['id']) && in_array($showtime['id'], $currentShowtimes)) {
                $showtimeEdit = $this->movieItem->showtimes()->find($showtime['id']);

                $showtimeEdit->update($dataShowtime);
            }else{
                Showtime::create(array_merge($dataShowtime, ['status' => 'active']));
            }
        }

        return redirect()->route('admin.movies.index')->with('success', 'Cập nhật phim thành công!');
    }

    #[Title('Chỉnh sửa phim - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $genres = Genre::select('id', 'name')->when($this->searchGenre, fn ($query) => $query->where('name', 'like', '%' . $this->searchGenre . '%'))->get();
        $totalGenres = Genre::count();
        $rooms = Room::select('id', 'name')->where('status', 'active')->get();
        if(!empty($this->showtimes)){
            $shotimesReversed = array_reverse($this->showtimes, true);
            $existingShowtimeIds = array_map(fn($showtime) => $showtime['id'], array_filter($this->showtimes, fn($showtime) => isset($showtime['id'])));
            $this->showtimes = array_reverse(array_map(function($showtime, $index) use ($rooms, $existingShowtimeIds){
                $availableRooms = $rooms;
                $roomConflict = $showtime['room_id'];

                if($showtime['start_time']){
                    $startTimeCarbon = Carbon::parse($showtime['start_time']);
                    $endTimeCarbon = $startTimeCarbon->clone()->addMinutes(+$this->duration);

                    $roomsFiltered = $rooms->toQuery()->select('id', 'name')->where('status', 'active')->whereDoesntHave('showtimes', function($query) use ($startTimeCarbon, $endTimeCarbon, $existingShowtimeIds){
                        $query->where('start_time', '<', $endTimeCarbon)
                            ->where('end_time', '>', $startTimeCarbon)
                            ->whereNotIn('id', $existingShowtimeIds)
                            ->where('status', 'active');
                    })->get();

                    (!empty($showtime['room_id']) && !$roomsFiltered->contains('id', $showtime['room_id'])) && (($roomConflict = $this->showtimes[$index]['room_id'] = null) || $this->scToast('Phòng chiếu bị trùng thời gian, lựa chọn của bạn đã bị ghi đè', 'warning', 5000, true, ''));
                    $availableRooms = $roomsFiltered->reject(function($room) use ($startTimeCarbon, $endTimeCarbon, $showtime, &$roomConflict, $index) {
                        return collect(array_filter($this->showtimes, fn($showtimeOther) => $showtimeOther !== $showtime && (int)$showtimeOther['room_id'] === $room->id  && !empty($showtimeOther['start_time'])))->contains(function($showtimeTemp) use ($startTimeCarbon, $endTimeCarbon, $showtime, $room, &$roomConflict, $index){
                            $tempStartCarbon = Carbon::parse($showtimeTemp['start_time']);
                            $tempEndCarbon = $tempStartCarbon->clone()->addMinutes(+$this->duration);
                            $hasTimeConflict = $tempStartCarbon < $endTimeCarbon && $tempEndCarbon > $startTimeCarbon;
                            $hasTimeConflict && ((int)$showtime['room_id'] === $room->id) && (($roomConflict = $this->showtimes[$index]['room_id'] = null) || $this->scToast('Phòng chiếu bị trùng thời gian, lựa chọn của bạn đã bị ghi đè', 'warning', 5000, true, ''));
                            return $hasTimeConflict;
                        });
                    });
                }

                return array_merge($showtime, ['rooms' => $availableRooms, 'room_id' => $roomConflict]);
            }, $shotimesReversed, array_keys($shotimesReversed)));
        }

        $modalData = [];
        if($this->modalType){
            $query = Movie::select("{$this->modalType} as name")
                ->when($this->searchModal, fn ($query) => $query->whereLike($this->modalType, '%' . trim($this->searchModal) . '%'));
            if($this->modalType === 'director'){
                $modalData = $query->distinct()->orderBy('name')->get()->pluck('name');
                (is_null($this->modalSelected)) && ($this->modalSelected = $modalData->contains($this->director) ? $this->director : '');
            }else{
                $modalData = $query->get()->pluck('name')->flatMap(fn($actors) => array_map(fn($actor) => trim($actor), explode(',', $actors)))->unique()->sort();
                (is_null($this->modalSelected)) && ($this->modalSelected = $this->actors ? array_filter(array_map(fn($actor) => $modalData->contains(trim($actor)) ? trim($actor) : null, explode(',', $this->actors))) : []);
            }
        }
        return view('livewire.admin.movies.movie-edit', compact('genres', 'rooms', 'totalGenres', 'modalData'));
    }
}
