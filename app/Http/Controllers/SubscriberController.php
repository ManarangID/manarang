<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Helper\Helpers;
use App\Mail\Websitemail;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Spatie\FlareClient\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SubscriberController extends Controller
{

    public function index()
    {
        $subscribers = Subscriber::where('status','Active')->get();
        return view('components.subscriber.subscriber', compact('subscribers'));
    }

    public function store(Request $request)
    {
            $validated = $request->validate([
                'email' => 'required|email|unique:subscribers|max:30']);

        // else
        // {
            $token = hash('sha256', time());
            $subscriber = new Subscriber();
            $subscriber->email = $request->email;
            $subscriber->token = $token;
            $subscriber->status = 'Pending';
            // $subscriber->save();

            $subscriber->save() ? 'You have successfully subscribed.' : 'Something went wrong, please try again.';

            // Send email
            $subject = 'Please Comfirm Subscription';
            $verification_link = url('subscriber/verify/'.$token.'/'.$request->email);
            $message = 'Please click on the following link in order to verify as subscriber:<br><br>';
            
            // $message .= $verification_link;

            $message .= '<a href="'.$verification_link.'">';
            $message .= $verification_link;
            $message .= '</a><br><br>';
            $message .= 'If you received this email by mistake, simply delete it. You will not be subscribed if you do not  click the confirmation link above.';

            \Mail::to($request->email)->send(new Websitemail($subject,$message));

            return redirect()->back()->with('success', 'Thanks, please check your inbox to confirm subscription')->withFragment('#subscriber');
        // }
    }

    public function verify($token,$email)
    {

        // Helpers::read_json();
        
        $subscriber_data = Subscriber::where('token',$token)->where('email',$email)->first();
        if($subscriber_data) 

        
        {
            $subscriber_data->token = '';
            $subscriber_data->status = 'Active';
            $subscriber_data->update();

            return redirect()->back()->with('success', 'You are successfully verified as a subscribe to this system');
        } 
        else 
        {
            return redirect()->back()->with('error', 'Error!');
        }
    }

    public function compose()
    {
        return view('components.subscriber.compose');
    }

    public function send_email_subscriber(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'message' => 'required'
        ]);

        $subject = $request->subject;
        $message = $request->message;

        $subscribers = Subscriber::where('status','Active')->get();
        foreach($subscribers as $row) {
            \Mail::to($row->email)->send(new Websitemail($subject,$message));
        }

        return redirect()->back()->with('success', 'Email is Sent Successfully.');
    }

    public function send_new_post(string $id): RedirectResponse
    {
        $posts = Post::findOrFail($id);
        $subject = 'Halo kami ada berita terbaru nih';
        $message = config('app.url').'/posts/'.$posts->seotitle;

        $subscribers = Subscriber::where('status','Active')->get();
        foreach($subscribers as $row) {
            \Mail::to($row->email)->send(new Websitemail($subject,$message));
        }

        return redirect()->back()->with('success', 'Email is Sent Successfully.');
    }
}