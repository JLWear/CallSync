<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meeting;
use App\Models\Group;
use Aws\Chime\ChimeClient;
use Aws\Exception\AwsException;

class MeetingController extends Controller
{
    protected $chime;

    public function __construct()
    {
        $this->chime = new ChimeClient([
            'region' => 'us-east-1',
            'version' => '2018-05-01',
        ]);
    }

    public function create($groupId)
    {
        $group = Group::findOrFail($groupId);

        try {
            $response = $this->chime->createMeeting([
                'ClientRequestId' => uniqid(),
                'MediaRegion' => 'us-east-1',
            ]);

            $meeting = Meeting::create([
                'group_id' => $group->id,
                'chime_meeting_id' => $response['Meeting']['MeetingId'],
                'started_at' => now(),
                'ended_at' => null,
            ]);

            return response()->json([
                'message' => 'Réunion créée avec succès.',
                'meeting' => $meeting,
                'chime_meeting_id' => $response['Meeting']['MeetingId'],
            ], 201);

        } catch (AwsException $e) {
            return response()->json([
                'error' => 'Erreur lors de la création de la réunion Chime.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function endMeeting($meetingId)
    {
        $meeting = Meeting::findOrFail($meetingId);

        try {
            $this->chime->deleteMeeting([
                'MeetingId' => $meeting->chime_meeting_id,
            ]);

            $meeting->update([
                'ended_at' => now(),
            ]);

            return response()->json([
                'message' => 'Réunion terminée avec succès.',
                'meeting' => $meeting,
            ], 200);

        } catch (AwsException $e) {
            return response()->json([
                'error' => 'Erreur lors de la fin de la réunion Chime.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getMeetingDetails($meetingId)
    {
        $meeting = Meeting::findOrFail($meetingId);

        return response()->json([
            'meeting' => $meeting,
        ]);
    }
}
