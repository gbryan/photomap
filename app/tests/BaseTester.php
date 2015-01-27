<?php

class BaseTester extends TestCase {
	
	protected $usesDb = false;

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
				'email'			=> 'bogus@bogus.com',
				'password'		=> Hash::make('password'),
				'first_name'	=> 'Administrator'
			]
		];

		DB::table('users')->insert($users);
	}
}
