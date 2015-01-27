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
		'coordinates',
		'exif',
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

	/**
	 * Get the base directory for storing photos on disk.
	 * @return string
	 */
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

	/**
	 * Grab useful exif data from the given $file and set it on this Photo.
	 * @param  Symfony\Component\HttpFoundation\File\UploadedFile $file
	 * @return void
	 */
	public function gleanExifData(Symfony\Component\HttpFoundation\File\UploadedFile $file)
	{
		$image = Image::make($file);

		$this->setCoordinatesFromExif($image);
		$this->setDateTakenFromExif($image);
	}

	/**
	 * Set the date_taken attribute on this Photo based on exif data on the given $image.
	 * @param Intervention\Image\Image $image
	 * @return  void
	 */
	private function setDateTakenFromExif(Intervention\Image\Image $image)
	{
		if (!$this->imageHasRequiredExifData($image, ['DateTimeOriginal'])) return;

		$dateTimeString = $this->exifDateToDateTimeString($image->exif('DateTimeOriginal'));

		if (empty($dateTimeString)) return;

		$this->date_taken = $dateTimeString;
	}

	/**
	 * Set the "coordinates" array on this Photo based on the GPS exif data on the given $image.
	 * @param Intervention\Image\Image $image
	 * @return  void
	 */
	private function setCoordinatesFromExif(Intervention\Image\Image $image)
	{
		$gpsAttributes = [
			'GPSLongitude',
			'GPSLongitudeRef',
			'GPSLatitude',
			'GPSLatitudeRef'
		];

		if (!$this->imageHasRequiredExifData($image, $gpsAttributes)) return;

		$this->coordinates = [
			$this->toDecimalDegrees($image->exif('GPSLongitude'), $image->exif('GPSLongitudeRef')),
			$this->toDecimalDegrees($image->exif('GPSLatitude'), $image->exif('GPSLatitudeRef'))
		];
	}

	/**
	 * Determine whether the given $image has all exif data in $requiredAttrs.
	 * @param  Intervention\Image\Image  $image
	 * @param  array $requiredAttrs
	 * @return boolean
	 */
	private function imageHasRequiredExifData(Intervention\Image\Image $image, array $requiredAttrs)
	{
		foreach ($requiredAttrs as $attr)
		{
			if (empty($image->exif($attr))) return false;
		}

		return true;
	}

	/**
	 * Convert a given $dateString in the format 2015:01:14 22:00:38 to a standard datetime string.
	 * @param  string $dateString
	 * @return string|null
	 */
	public function exifDateToDateTimeString($dateString)
	{
		// Should be something like this: 2015:01:14 22:00:38
		try
		{
			$date = new Carbon\Carbon($dateString);
			return $date->toDateTimeString();
		}

		catch (InvalidArgumentException $e)
		{
			return null;
		}
	}

	/**
	 * Convert coordinates as degrees, minutes, and seconds into decimal degrees.
	 * http://stackoverflow.com/questions/2526304/php-extract-gps-exif-data
	 * @param  array $coordinate
	 * @param  string $hemisphere
	 * @return float
	 */
	public function toDecimalDegrees(array $coordinate, $hemisphere)
	{
		for ($i = 0; $i < 3; $i++)
		{
			$part = explode('/', $coordinate[$i]);

			if (count($part) == 1)
			{
				$coordinate[$i] = $part[0];
			} 

			else if (count($part) == 2)
			{	
				$coordinate[$i] = floatval($part[0]) / floatval($part[1]);
			}
			else
			{
				$coordinate[$i] = 0;
			}
		}

		list($degrees, $minutes, $seconds) = $coordinate;
		$sign = ($hemisphere == 'W' || $hemisphere == 'S') ? -1 : 1;

		return $sign * ($degrees + $minutes / 60 + $seconds / 3600);
	}

	/**
	 * Get a URL to view this photo.
	 * @return string
	 */
	public function getUrlAttribute()
	{
		return URL::action('FilesController@show', $this->_id);
	}
}
