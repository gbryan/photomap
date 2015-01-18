<?php

use PhotoMap\Pagination\Paginator;
use Illuminate\Http\Request as LaravelInput;

class MarkersController extends \ApiController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// TO-DO: validation
		// requires operator, shape, coordinates

		$input = new LaravelInput(Input::all());

		$validation = Validator::make($input->all(), [
			'operator'		=> 'required|in:within',
			'shape'			=> 'required|in:box,sphere',
			'coordinates'	=> 'required|jsonArray'
		]);

		if ($validation->fails())
		{
			return $this->errorResponse($validation->errors()->toArray());
		}

		switch ($input->get('operator'))
		{
			case 'within':
				return $this->queryWithin($input);
		}

	}

	private function queryWithin(LaravelInput $input)
	{
		switch ($input->get('shape'))
		{
			case 'box':
				return $this->withinBox($input);
			case 'sphere':
				return $this->withinSphere($input);
			default:
				return 'invalid shape';
		}

		return 'invalid shape';
	}

	private function withinBox(LaravelInput $input)
	{
		// requires $coordinates, which is an array of two arrays

		$coordinates = json_decode($input->get('coordinates'));

		$results = Marker::withinBox($coordinates)->paginate($input->get('limit', Paginator::MAX_PER_PAGE));

		return $this->successResponse($results->apiFields(), 'Markers within box ' . json_encode($coordinates));
	}

	private function withinSphere(LaravelInput $input)
	{
		// $geoWithin: { $centerSphere: [ [ <x>, <y> ], <radius> ] }
		// requires radius
		// requires coordinates, which is an array of 2 elements.
		return 'not yet implemented';
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		// 
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$marker = new Marker(Input::all());

		if (!$marker->save())
		{
			return $this->errorResponse($marker->errors->toArray());
		}

		return $this->successResponse($marker->toArray(), 'Marker created successfully!');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$marker = Marker::findOrFail($id);

		$markerArray = $marker->toArray();

		if (Input::get('include_photos', false))
		{
			$markerArray = array_merge($markerArray, ['photos' => $marker->photos()->lists('_id')]);
		}

		return $this->successResponse($markerArray);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
