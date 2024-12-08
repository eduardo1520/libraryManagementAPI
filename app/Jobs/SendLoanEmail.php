<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
class SendLoanEmail implements ShouldQueue
{

    use InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $loanDetails;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $loanDetails)
    {
        $this->user = $user;
        $this->loanDetails = $loanDetails;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Iniciando envio de e-mail.', [
            'user' => $this->user,
            'loanDetails' => $this->loanDetails,
        ]);

        Mail::send('emails.loan', ['user' => $this->user, 'loanDetails' => $this->loanDetails], function ($message) {
            $message->to($this->user['email'], $this->user['name'])
                    ->subject('Confirmação de Empréstimo');
        });
    }
}
