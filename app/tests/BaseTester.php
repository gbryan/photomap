<?php

class BaseTester extends TestCase {
	
	protected $usesDb = false;

	protected $testMarker = [
		'name'	=> 'City Hall',
		'loc'	=> [
			'type'			=> 'Point',
			'coordinates'	=> [-122.273257, 37.869230]
		],
		'description'	=> 'My fancy description',
		'tags'	=> ['building', 'government']
	];

	public function setUp()
	{
		parent::setUp();

		if ($this->usesDb)
		{
			$this->dropTestDatabase();
			$this->setupTestData();
		}
	}

	public function tearDown()
	{
		parent::tearDown();

		$this->deleteTestStorage();
	}

	private function deleteTestStorage()
	{
		File::deleteDirectory(storage_path(), true);
	}

	private function dropTestDatabase()
	{
		$mongo = new MongoClient;
		$db = $mongo->photomap_test;
		$db->command(['dropDatabase' => 1]);
	}

	protected function setupTestData()
	{
		$this->createUsers();
	}

	protected function createUsers()
	{
		$users = [
			[
				'username'		=> 'admin',
				'password'		=> Hash::make('password'),
				'first_name'	=> 'Administrator',
				'email'			=> 'bogus@bogus.com'
			]
		];

		DB::table('users')->insert($users);
	}
}
