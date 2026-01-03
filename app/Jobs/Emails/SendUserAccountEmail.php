<?php

namespace App\Jobs\Emails;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Mail;
use App\Mail\SendUserAccountEmail as SendUserAccountMail;
use Log;

class SendUserAccountEmail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */

    protected $user;

    protected $password;

    public function __construct($user, $password)
    {
        
        $this->user = $user;

        $this->password = $password;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try{
            
            $email = new SendUserAccountMail($this->user, $this->password);

            Mail::to($this->user->email)->send($email);
          
        }
        catch(\Exception $exception){

            Log::error('Error Sending Email : '.$exception->getMessage());
        }
    }
}
