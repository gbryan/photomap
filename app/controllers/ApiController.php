<?php

class ApiController extends BaseController {
	
	public function successResponse($data = array(), $message = '', $statusMessage = 'success', $httpStatus = 200, $headers = array())
	{
		return Response::json([
				'status'	=> $statusMessage,
				'message'	=> $message,
				'data'		=> $data
			],
			$httpStatus,
			$headers
		);
	}

	public function errorResponse($errors = array(), $message = '', $statusMessage = 'error', $httpStatus = 400, $headers = array())
	{
		return Response::json([
				'status'	=> $statusMessage,
				'message'	=> $message,
				'errors'	=> $errors,
			],
			$httpStatus,
			$headers
		);
	}

}
