<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\UpdateRecord;
use Illuminate\Http\Request;
use App\Repositories\ProgressRepository;
use App\Repositories\UserRepository;
use App\Repositories\MedicalRecordRepository;
use Illuminate\Support\Facades\Notification;

class ProgressController extends Controller
{
    protected $progressRepository;
    protected $userRepository;
    protected $medicalRecordRepository;

    public function __construct(
        ProgressRepository $progressRepository,
        UserRepository $userRepository,
        MedicalRecordRepository $medicalRecordRepository
    ) {
        $this->progressRepository = $progressRepository;
        $this->userRepository = $userRepository;
        $this->medicalRecordRepository = $medicalRecordRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id = $request['id'] ?? '';
        $recordId = $request['record_id'] ?? '';

        $query = $this->progressRepository->getCollection($request);

        if ($id) {
            $query->where('id', $id);
        }

        if ($recordId) {
            $query->where('record_id', $recordId);
        }

        $items = $query->orderByDesc('created_at')->paginate(3);

        return $this->sendSuccess($items);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $progress = $this->progressRepository->create($request->all());
            $id = $request['record_id'];
            $record = $this->medicalRecordRepository->detail($id);
            $user = User::find($record->user_id);
            $doctor = User::find($record->doctor_id);
            $record->updated_at = $progress->created_at;
            $record->save();
            Notification::send($user, new UpdateRecord($doctor, $progress));

        } catch (\Exception $e) {
            return $this->sendError('Vui lòng thử lại', $e->getMessage(), 'Có lỗi xảy ra');
        }

        return $this->sendSuccess($progress);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('common');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('common');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $this->progressRepository->update($request->all(), $id);
        } catch (\Exception $e) {
            return $this->sendError('Vui lòng nhập đủ các trường', 'Kiểm tra lại');
        }

        return $this->sendSuccess('');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->progressRepository->delete($id);
        } catch (\Exception $e) {
            return $this->sendError('Vui lòng thử lại', $e->getMessage(), 'Có lỗi xảy ra');
        }

        return $this->sendSuccess('');
    }
}
