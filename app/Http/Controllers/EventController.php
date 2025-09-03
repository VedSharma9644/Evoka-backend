<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Models\EventComment;
use App\Models\EventParticipation;
use App\Models\EventChat;
use App\Models\User;
use App\Mail\ThanksParticipationEmail;
use App\Mail\ParticipationAlertEmail;

use Illuminate\Support\Facades\Mail;
class EventController extends Controller
{
    function getPaypalAccessToken()
{
    $response = Http::asForm()->withBasicAuth(
        env('PAYPAL_CLIENT_ID'),
        env('PAYPAL_SECRET')
    )->post(env('PAYPAL_BASE_URL') . '/v1/oauth2/token', [
        'grant_type' => 'client_credentials',
    ]);

    return $response->json()['access_token'];
}

    public function index(Request $request)
    {
        if($request->expired == 'true'){
            $events = Event::where('status','completed')->get();
         
        }elseif($request->fetured == 'true'){
            $events = Event::whereIn('status',['approved','completed'])->where('is_fetured',1)->get();
         
        }else{
        $events = Event::whereIn('status',['approved','completed'])->get();
        }
        return response()->json([
            'message' => 'Events retrieved successfully',
            'events' => $events,
        ], 200);
    }
    public function singleEvent(Request $request, $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
        $rating = $event->getAverageRatingAttribute();
        $event->rating = $rating; 
        $event->user_name = $event->user->username; 
        $event->participation_total = $event->participants->count();
        $EventComments = EventComment::where('event_id', $id)->get()->map(function ($comment) {
            return [
                'user_id' => $comment->user_id,
                'text' => $comment->comment,
                'created_at' => $comment->created_at,
                'user_name' => $comment->user->name
            ];
        });
        $EventParticipation = EventParticipation::where('event_id', $id)->get()->map(function ($d) use ($request) {
            return [
                'user_id' => $d->user_id,
                'name' => $d->user->username,
                 'status' => $d->status,
                'time' => $d->created_at,
            ];
        });
        return response()->json([
            'message' => 'Event retrieved successfully',
            'event' => $event,
            'comments' => $EventComments,
            'participation' => $EventParticipation,
        ], 200);
    }
    public function my_participation(Request $request){
$EventParticipation = EventParticipation::where('user_id',$request->user()->id)->get()->map(function ($d){
    return [
        'id'=>$d->event_id,
        'eventName'=>$d->event->title,
        'dateTime'=>$d->event->start_date.' '.$d->event->start_time,
        'status'=>$d->status,
        'participatedAt'=>$d->created_at,
        'participation_id'=>$d->id
    ];
});
return response()->json([
    'data'=>$EventParticipation
]);
    }
    public function participate(Request $request, $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        // Check if the user is already participating
        $existingParticipation = $event->participants()->where('user_id', $request->user()->id)->first();
        if ($existingParticipation) {
            return response()->json(['message' => 'You are already participating in this event'], 400);
        }

        if($event->participants()->count() >= $event->max_participants) {
            return response()->json(['message' => 'Event is full'], 400);
        }
           
        
        if($event->is_free == false and $event->price > 0) {
            // LOCAL DEVELOPMENT: Bypass PayPal for testing
            if (env('APP_ENV') === 'local') {
                // Create participation directly for local testing
                $participationData = [
                    'user_id' => $request->user()->id,
                    'status' => 'approved', // Auto-approve for local testing
                ];
                
                // Handle multi-participant booking data
                if ($request->has('participants') && is_array($request->participants)) {
                    $participantNames = [];
                    $participantEmails = [];
                    
                    foreach ($request->participants as $participant) {
                        if (!empty($participant['name'])) {
                            $participantNames[] = $participant['name'];
                        }
                        if (!empty($participant['email'])) {
                            $participantEmails[] = $participant['email'];
                        }
                    }
                    
                    $participationData['participant_names'] = $participantNames;
                    $participationData['participant_emails'] = $participantEmails;
                    $participationData['number_of_participants'] = count($participantNames);
                } else {
                    // Single participant (default)
                    $participationData['number_of_participants'] = 1;
                }
                
                $event->participants()->create($participationData);
                
                // LOCAL DEVELOPMENT: Skip email sending to avoid spam rejection
                if (env('APP_ENV') !== 'local') {
                    Mail::to($request->user()->email)->send(new ThanksParticipationEmail($request->user(), $event));
                    Mail::to($event->notification_email)->send(new ParticipationAlertEmail($request->user(), $event));
                }
                
                return response()->json([
                    'message' => 'Participation successful (Local Development Mode)',
                    'success' => true
                ], 200);
            }
            
            // PRODUCTION: Use PayPal
            $accessToken = self::getPaypalAccessToken();

            $response = Http::withToken($accessToken)
                ->post(env('PAYPAL_BASE_URL') . '/v2/checkout/orders', [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [[
                        'amount' => [
                            'currency_code' => 'EUR',
                            'value' => $event->price,
                        ],
                    ]],
                    'application_context' => [
                        'return_url' => url('paypal/success/' . $event->id.'/'. $request->user()->id),
                        'cancel_url' => url('paypal/cancel'),
                    ]
                ]);
             $redirect =  (collect($response->json()['links'])->firstWhere('rel', 'approve')['href']);
                return response()->json(['redirect' => $redirect ], 200);
        }
//  return response()->json(['message' => $event->is_free], 400);

        // Create a new participation
        $participationData = [
            'user_id' => $request->user()->id,
            'status' => 'pending',
        ];
        
        // Handle multi-participant booking data
        if ($request->has('participants') && is_array($request->participants)) {
            $participantNames = [];
            $participantEmails = [];
            
            foreach ($request->participants as $participant) {
                if (!empty($participant['name'])) {
                    $participantNames[] = $participant['name'];
                }
                if (!empty($participant['email'])) {
                    $participantEmails[] = $participant['email'];
                }
            }
            
            $participationData['participant_names'] = $participantNames;
            $participationData['participant_emails'] = $participantEmails;
            $participationData['number_of_participants'] = count($participantNames);
        } else {
            // Single participant (default)
            $participationData['number_of_participants'] = 1;
        }
        
        $event->participants()->create($participationData);
        
        // LOCAL DEVELOPMENT: Skip email sending to avoid spam rejection
        if (env('APP_ENV') !== 'local') {
            Mail::to($request->user()->email)->send(new ThanksParticipationEmail($request->user(), $event));
            Mail::to($event->notification_email)->send(new ParticipationAlertEmail($request->user(), $event));
        }

        return response()->json(['message' => 'Participation request sent successfully'], 200);
    }
    public function paypal_eventpart_success(Request $request, $event_id, $uid)
    {
         $orderId = $request->query('token');
        $event = Event::find($event_id);

    
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
$accessToken = self::getPaypalAccessToken();

$response = Http::withToken($accessToken)
    ->withBody('', 'application/json') // <-- explicitly empty body
    ->post(env('PAYPAL_BASE_URL') . "/v2/checkout/orders/{$orderId}/capture");

// return $response->json();
        // Check if the user is already participating
        $existingParticipation = $event->participants()->where('user_id', $uid)->first();
        if ($existingParticipation) {
            return response()->json(['message' => 'You are already participating in this event'], 400);
        }

        // Create a new participation
        $event->participants()->create([
            'user_id' => $uid,
            'status' => 'approved',
        ]);
            Mail::to(User::find($uid)->email)->send(new ThanksParticipationEmail(User::find($uid), $event));
            Mail::to($event->notification_email)->send(new ParticipationAlertEmail(User::find($uid), $event));

   return redirect(env('FRONTEND_URL').'payment/success');

        // return response()->json(['message' => 'Participation request sent successfully'], 200);
    }
    public function rate(Request $request, $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        // Validate rating
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|between:1,5',
           
        ]);

        $EventRating = EventRating::where('event_id', $id)->where('user_id', $request->user()->id)->first();
        if ($EventRating) {
            // return response()->json(['message' => 'You have already rated this event'], 400);
            $EventRating->update([
                'rating' => $request->rating,
            ]);
            return response()->json(['message' => 'Rating updated successfully'], 200);
        }
        EventRating::create([
            'user_id' => $request->user()->id,
            'event_id' => $id,
            'rating' => $request->rating,
        ]);
        // Store rating logic here

        return response()->json(['message' => 'Rating submitted successfully'], 200);
    }
    public function my_ratings(Request $request, $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

       

        $EventRating = EventRating::where('event_id', $id)->where('user_id', $request->user()->id)->first();
        if ($EventRating) {
            return response()->json(['rating' => $EventRating->rating], 200);
        }
            return response()->json(['rating' => 0], 200);


     }
     public function add_comments(Request $request, $id){
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        // Validate comment
        $validator = Validator::make($request->all(), [
            'comment' => 'required|string|max:255',
           
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }
        EventComment::create([
            'user_id' => $request->user()->id,
            'event_id' => $id,
            'comment' => $request->comment,
        ]);
        // Store rating logic here

        return response()->json(['message' => 'Comment submitted successfully'], 200);
     }
      public function send_chat(Request $request, $id){
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        // Validate comment
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:255',
           
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }
        EventChat::create([
            'user_id' => $request->user()->id,
            'event_id' => $id,
            'message' => $request->message,
        ]);
        // Store rating logic here

        return response()->json(['message' => 'Message submitted successfully'], 200);
     }
     public function chat(Request $request, $id){
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

       
        $EventChat = EventChat::where('event_id', $id)->get()->map(function ($chat) use ($request) {
            return [
                'user' => $chat->user_id == $request->user()->id?'You': $chat->user->username,
                'text' => $chat->message,
                'timestamp' => $chat->created_at,
                
            ];
        });
        return response()->json([
            'message' => 'Event retrieved successfully',
            'data' => $EventChat,
        ], 200);
     }
    public function store(Request $request)
    {
        // Map camelCase to snake_case
        $input = $request->all();
        $mappedInput = [
            'title' => $input['title'] ?? null,
            'category' => $input['category'] ?? null,
            'description' => $input['description'] ?? null,
            'start_date' => $input['startDate'] ?? null,
            'start_time' => $input['startTime'] ?? null,
            'end_date' => $input['endDate'] ?? null,
            'end_time' => $input['endTime'] ?? null,
            'is_public' => filter_var($input['isPublic'] ?? true, FILTER_VALIDATE_BOOLEAN),
            'notification_email' => $input['notificationEmail'] ?? null,
            'address' => $input['address'] ?? null,
            'is_free' => filter_var($input['isFree'] ?? true, FILTER_VALIDATE_BOOLEAN),
            'price' => $input['price'] ?? null,
            'max_participants' => $input['maxParticipants'] ?? null,
            'latitude' => $input['latitude'] ?? null,
            'longitude' => $input['longitude'] ?? null,
        ];

        // Validation rules
        $validator = Validator::make($mappedInput, [
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_date' => 'required|date|after_or_equal:start_date',
            'end_time' => 'required|date_format:H:i',
            'is_public' => 'required|boolean',
            'notification_email' => 'required|email|max:255',
            'address' => 'required|string',
            'is_free' => 'required|boolean',
            'price' => 'required_if:is_free,false|numeric|min:0|nullable',
            'max_participants' => 'required|integer|min:1',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            $firstError = $validator->errors()->first(); // Get the first error message
        
            return response()->json([
                'message' => $firstError,
            ], 422);
        }
        

        // Handle file uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('events/images', 'public');
                $imagePaths[] = $path;
            }
        }

        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('events/documents', 'public');
        }

        // Create event
        $event = Event::create([
            'user_id' => $request->user()->id,
            'title' => $mappedInput['title'],
            'category' => $mappedInput['category'],
            'description' => $mappedInput['description'],
            'start_date' => $mappedInput['start_date'],
            'start_time' => $mappedInput['start_time'],
            'end_date' => $mappedInput['end_date'],
            'end_time' => $mappedInput['end_time'],
            'is_public' => $mappedInput['is_public'],
            'notification_email' => $mappedInput['notification_email'],
            'address' => $mappedInput['address'],
            'is_free' => $mappedInput['is_free'],
            'price' => $mappedInput['is_free'] ? null : $mappedInput['price'],
            'max_participants' => $mappedInput['max_participants'],
            'latitude' => $mappedInput['latitude'],
            'longitude' => $mappedInput['longitude'],
            'images' => $imagePaths,
            'document' => $documentPath,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Event created successfully',
            'event' => $event,
        ], 201);
    }

    public function myEvents(Request $request)
    {
        $events = Event::where('user_id', $request->user()->id)->get()->map(function($d){
            return array_merge(
            $d->toArray(),
            ['participants' => $d->participants->map(
                function($p){
                    return [
                        'user_id'=>$p->user_id,
                        'name'=>$p->user->name,
                        'email'=>$p->user->email,
                        'telephone'=>$p->user->telephone,
                        'status'=>$p->status,
                        'status_reason'=>$p->status_reason,
                    ];
                }
            )]
        );
        });

        return response()->json([
            'message' => 'Events retrieved successfully',
            'events' => $events,
        ], 200);
    }
    public function chnage_status(Request $request, $id){
  $event = Event::where('id', $id)->where('user_id', $request->user()->id)->first();

        if (!$event) {
            return response()->json(['message' => 'Event not found or unauthorized'], 404);
        }
        
        $event->participants->where('user_id',$request->user_id)->first()->update(['status'=>$request->status]);
          return response()->json([
            'message' => 'Status updated successfully!',
            
        ], 200);
    }
    public function update(Request $request, $id)
    {
        $event = Event::where('id', $id)->where('user_id', $request->user()->id)->first();

        if (!$event) {
            return response()->json(['message' => 'Event not found or unauthorized'], 404);
        }

        // Map camelCase to snake_case
        $input = $request->all();
        $mappedInput = [
            'title' => $input['title'] ?? null,
            'category' => $input['category'] ?? null,
            'description' => $input['description'] ?? null,
            'start_date' => $input['startDate'] ?? null,
            'start_time' => $input['startTime'] ?? null,
            'end_date' => $input['endDate'] ?? null,
            'end_time' => $input['endTime'] ?? null,
            'is_public' => filter_var($input['isPublic'] ?? true, FILTER_VALIDATE_BOOLEAN),
            'notification_email' => $input['notificationEmail'] ?? null,
            'address' => $input['address'] ?? null,
            'is_free' => filter_var($input['isFree'] ?? true, FILTER_VALIDATE_BOOLEAN),
            'price' => $input['price'] ?? null,
            'max_participants' => $input['maxParticipants'] ?? null,
            'latitude' => $input['latitude'] ?? null,
            'longitude' => $input['longitude'] ?? null,
            'existing_images' => $input['existing_images'] ?? json_encode([]),
        ];
// dd($mappedInput);
// return response()->json([
//             'message' => 'Event retrieved successfully',
//             'event' => $input,
//         ], 200);
        // Validation rules
        $validator = Validator::make($mappedInput, [
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'start_time' => 'required',
            'end_date' => 'required|date|after_or_equal:start_date',
            'end_time' => 'required',
            'is_public' => 'required|boolean',
            'notification_email' => 'required|email|max:255',
            'address' => 'required|string',
            'is_free' => 'required|boolean',
            'price' => 'required_if:is_free,false|numeric|min:0|nullable',
            'max_participants' => 'required|integer|min:1',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'existing_images' => 'json',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        // Handle file uploads
        $imagePaths = json_decode($mappedInput['existing_images'], true);
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('events/images', 'public');
                $imagePaths[] = $path;
            }
        }

        $documentPath = $event->document;
        if ($request->hasFile('document')) {
            if ($documentPath) {
                Storage::disk('public')->delete($documentPath);
            }
            $documentPath = $request->file('document')->store('events/documents', 'public');
        }

        // Update event
        $event->update([
            'title' => $mappedInput['title'],
            'category' => $mappedInput['category'],
            'description' => $mappedInput['description'],
            'start_date' => $mappedInput['start_date'],
            'start_time' => $mappedInput['start_time'],
            'end_date' => $mappedInput['end_date'],
            'end_time' => $mappedInput['end_time'],
            'is_public' => $mappedInput['is_public'],
            'notification_email' => $mappedInput['notification_email'],
            'address' => $mappedInput['address'],
            'is_free' => $mappedInput['is_free'],
            'price' => $mappedInput['is_free'] ? null : $mappedInput['price'],
            'max_participants' => $mappedInput['max_participants'],
            'latitude' => $mappedInput['latitude'],
            'longitude' => $mappedInput['longitude'],
            'images' => $imagePaths,
            'document' => $documentPath,
            'status' => 'pending', // Reset to pending on update
        ]);

        return response()->json([
            'message' => 'Event updated successfully',
            'event' => $event,
        ], 200);
    }
}