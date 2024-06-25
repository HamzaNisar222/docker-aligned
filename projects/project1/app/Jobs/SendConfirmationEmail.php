<?php

namespace App\Jobs;

use App\Models\Client;
use Illuminate\Bus\Queueable;
use App\Mail\ConfirmationEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


class SendConfirmationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $confirmationUrl;

    public function __construct(Client $user, $confirmationUrl)
    {
        $this->user = $user;
        $this->confirmationUrl = $confirmationUrl;
    }

    public function handle()
    {
        Mail::to($this->user->email)
            ->send(new ConfirmationEmail($this->confirmationUrl));
    }
}
