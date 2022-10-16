<?php declare(strict_types=1);

namespace App\Jobs;

use App\Mail\SendJobResponse;
use App\Models\JobVacancyResponse;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendJobVacancyResponse implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $response;

    protected $data_email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(JobVacancyResponse $response, $data_email)
    {
        $this->response = $response;
        $this->data_email = $data_email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() : void
    {
        try {
            $data_email = $this->data_email;
            $this->response->notified = 1;
            if ($this->response->save()) {
                Mail::to($data_email['email'])->send(new SendJobResponse($data_email));
            }
        } catch (\Exception $exception) {
            $this->failed($exception);
        }
    }

    public function failed($exception)
    {
        Mail::raw($exception, function ($mail) {
            $mail->to(env('MAIL_ADMIN_ADDRESS'))
                ->subject('Error!')
            ;
        });
    }
}
