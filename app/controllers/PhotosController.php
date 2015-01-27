<?php

use PhotoMap\Pagination\Paginator;

class PhotosController extends \ApiController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
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
		$photo = new Photo(Input::all());

		if (!$photo->validate(Input::all(), $photo->validationRulesCreating))
		{
			return $this->errorResponse($photo->errors->toArray());
		}

		$filenameWithoutExtension = new MongoId;

		$imageOnDisk = $photo->storeImage(Input::file('photo'), $filenameWithoutExtension);
		$photo->gleanExifData(Input::file('photo'));
		$photo->filename = $imageOnDisk->dirname . '/' . $imageOnDisk->filename . '.' . $imageOnDisk->extension;
		$photo->_id = $filenameWithoutExtension;

		if (Input::get('create_marker', false) && empty($photo->coordinates))
		{
			return $this->errorResponse([$photo->_id => 'Marker could not be created because the provided photo has no GPS data.'],
				'Marker could not be created because the provided photo has no GPS data.');
		}

		if (Input::get('create_marker', false) && !empty($photo->coordinates))
		{
			$marker = Marker::create([
				'type'			=> 'Feature',
				'name'			=> $photo->_id,
				'description'	=> $photo->_id,
				'geometry'	=> [
					'type'			=> 'Point',
					'coordinates'	=> $photo->coordinates
				]
			]);
			$photo->marker_id = $marker->_id;
		}
		
		if (!$photo->save())
		{
			return $this->errorResponse($photo->errors->toArray());
		}

		return $this->successResponse($photo, 'Photo created successfully!');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$photo = Photo::findOrFail($id);

		return $this->successResponse($photo->apiFields());
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
	 * Given a partial set of attributes, update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function partialUpdate($id)
	{
		$photo = Photo::findOrFail($id);

		$photo->fill(Input::all());
		
		if (!$photo->save())
		{
			return $this->errorResponse($photo->errors->toArray());
		}

		return $this->successResponse($photo, 'Photo updated successfully!');
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

	/**
	 * Find markers that are not associated with a marker.
	 * @return Response
	 */
	public function noMarker()
	{
		return $this->successResponse(
			Photo::whereNull('marker_id')->paginate()->apiFields(), 
			'Photos without associated markers');
	}

}
