<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;
use App\Models\EventParticipation;
use App\Mail\EventApprovedEmail;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
return view('home');
    }
    public function users()
    {
           if(isset($_GET['del'])){
            $user = User::find($_GET['del']);
             
                $user->delete();
            
            return redirect()->back()->with('success', 'Event deleted successfully.');
        }
                $users = User::all();

        return view('admin.users', compact('users'));
    }
    public function events()
    {
        if(isset($_GET['cng_fetured'])){
            $event = Event::find($_GET['cng_fetured']);
             
                $event->update(['is_fetured' => $event->is_fetured == 1 ? 0 : 1]);
           
            $event->save();
            return redirect()->back()->with('success', 'Event featured status updated successfully.');
        }
            if(isset($_GET['del'])){
            $event = Event::find($_GET['del']);
             
                $event->delete();
            
            return redirect()->back()->with('success', 'Event deleted successfully.');
        }
        
        $events = Event::all();
        return view('admin.events',compact('events'));
    }
    public function updateStatus(Request $request, Event $event)
    {
        $event->status = $request->input('status');
        $event->save();
        if($event->status == 'approved') {
            $user = User::find($event->user_id);
            // LOCAL DEVELOPMENT: Skip email sending to avoid spam rejection
            if (env('APP_ENV') !== 'local') {
                Mail::to($user->email)->send(new EventApprovedEmail($user, $event));
            }
            // Send email to the user
        } 
        return redirect()->back()->with('success', 'Event status updated successfully.');
    }
    public function events_participation()
    {
        $events_participation = EventParticipation::all();
        return view('admin.events_participation',compact('events_participation'));
    }
    public function events_participation_update(Request $request, $id)
    {
        $eventParticipation = EventParticipation::findOrFail($id);
        $eventParticipation->status = $request->input('status');
        $eventParticipation->save();

        return redirect()->back()->with('success', 'Event participation status updated successfully.');
    }
}
