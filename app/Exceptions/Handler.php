<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        return parent::render($request, $exception);
    }
	
	protected function handleValidationException(ValidationException $exception)
	{
		$errors = $this->formatErrorBlock($exception->validator);

		$first = '';
		if (!empty($errors) && !empty($errors[0]))
			$first = $errors[0]['message'];

		$json = new \stdClass;
		$json->success = false;
		$json->success_code = 400;
		$json->message = $first;
		$json->errors = $errors;

		return response()->json($json, 400);
	}
	
	public function formatErrorBlock($validator)
	{
		$errors = $validator->errors()->toArray();
		$return = array();

		foreach ($errors as $field => $message) {
			$r = ['field' => $field];

			foreach ($message as $key => $msg) {
				if ($key) {
					$r['message'.$key] = $msg;
				} else {
					$r['message'] = $msg;
				}
			}

			$return[] = $r;
		}

		return $return;
	}
}
