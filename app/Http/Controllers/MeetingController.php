<?php

namespace App\Http\Controllers;

use App\Models\ZoomMeeting;
use App\Traits\ZoomMeetingTrait;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use JsonException;

/**
 * Class MeetingController
 *
 * @package namespace App\Http\Controllers
 */
class MeetingController extends Controller
{
    use ZoomMeetingTrait;

    /**
     *
     */
    const MEETING_TYPE_INSTANT = 1;
    /**
     *
     */
    const MEETING_TYPE_SCHEDULE = 2;
    /**
     *
     */
    const MEETING_TYPE_RECURRING = 3;
    /**
     *
     */
    const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;

    /**
     * @param $id
     *
     * @return Application|Factory|View
     * @throws GuzzleException
     * @throws JsonException
     */
    public function show($id): View|Factory|Application
    {
        $meeting = $this->get($id);

        return view('meetings.index', compact('meeting'));
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     * @throws GuzzleException
     * @throws JsonException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->create($request->all());

        return redirect()->route('meetings.index');
    }

    /**
     * @param         $meeting
     * @param Request $request
     *
     * @return RedirectResponse
     * @throws GuzzleException
     * @throws JsonException
     */
    public function update($meeting, Request $request): RedirectResponse
    {
        $this->update($meeting->zoom_meeting_id, $request);

        return redirect()->route('meetings.index');
    }

    /**
     * @param ZoomMeeting $meeting
     *
     * @return mixed
     * @throws GuzzleException
     */
    public function destroy(ZoomMeeting $meeting): mixed
    {
        $this->delete($meeting->id);

        return $this->sendSuccess('Meeting deleted successfully.');
    }
}
