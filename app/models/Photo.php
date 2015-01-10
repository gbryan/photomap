<?php

class Photo extends BaseModel {

	public $singularName = 'photo';

	public $pluralName = 'photos';

	protected $appends = [
		'url'
	];

	protected $fillable = [
		'title',
		'description',
		'date_taken',
		'marker_id'
	];

	protected $apiFields = [
		'_id',
		'title',
		'description',
		'date_taken',
		'url',
		'marker_id',
		'created_at',
		'updated_at'
	];

	public $validationRulesCreating = [
		'photo'		=> 'required|image|max:5000'
	];

	public $validationRulesUpdating = [
		'photo'		=> 'image|max:5000'
	];

	public function marker()
	{
		return $this->belongsTo('Marker');
	}

	public static function baseDirectory()
	{
		return storage_path() . '/photos';
	}

	/**
	 * Save an uploaded file to the photo storage directory.
	 * @param  Symfony\Component\HttpFoundation\File\UploadedFile $file
	 * @param  String $filenameWithoutExtension
	 * @return \Intervention\Image\Image|false
	 */
	public function storeImage(Symfony\Component\HttpFoundation\File\UploadedFile $file, $filenameWithoutExtension = null)
	{
		$filenameWithoutExtension = (!empty($filenameWithoutExtension)) ? $filenameWithoutExtension : $file->getClientOriginalName();
		$basePath = Photo::baseDirectory();
		$filenameWithPath = $basePath . '/' . $filenameWithoutExtension . '.' . $file->getClientOriginalExtension();

		if (!File::exists($basePath))
		{
			File::makeDirectory($basePath, 0755, true);
		}

		$imageOnDisk = Image::make($file->getRealPath());

		return $imageOnDisk->save($filenameWithPath);
	}

	public function getUrlAttribute()
	{
		return URL::action('FilesController@show', $this->_id);
	}
}
