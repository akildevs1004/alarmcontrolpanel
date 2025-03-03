<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Mail;
use App\Mail\ErrorOccurred;
use Exception;
use PhpOffice\PhpSpreadsheet\Calculation\ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    // public function register()
    // {
    //     $this->reportable(function (Throwable $e) {
    //         $this->sendErrorEmail($e);
    //     });
    // }
    public function report(Exception $exception)
    {

        // parent::report($exception);

        $this->sendErrorEmail($exception);
    }


    protected function sendErrorEmail(\Exception $exception)
    {
        // Prepare the error details
        $errorDetails = [
            'exception_message' => $exception->getMessage(),
            'exception_file' => $exception->getFile(),
            'exception_line' => $exception->getLine(),
            'stack_trace' => $exception->getTraceAsString(),
        ];

        Mail::to('xtremegurad@gmail.com')->send(new ErrorOccurred($errorDetails));
        Mail::to('venuakil2@gmail.com')->send(new ErrorOccurred($errorDetails));
    }
}
