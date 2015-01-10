<?php

class FilesController extends \ApiController {

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$photo = Photo::where('_id', '=', $id)->firstOrFail();

		return Image::make($photo->filename)->response();
	}

}
