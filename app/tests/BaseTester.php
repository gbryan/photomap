<?php

class BaseTester extends TestCase {
	
	protected $usesDb = false;

	protected $enableEvents = true;

	protected $loginFirst = false;

	public function setUp()
	{
		parent::setUp();

		if ($this->usesDb)
		{
			$this->dropTestDatabase();
			$this->setupTestData();
		}

		if ($this->enableEvents)
		{
			$this->registerModelEvents();
		}

		if ($this->loginFirst)
		{
			$this->login();
		}
	}

	public function tearDown()
	{
		parent::tearDown();

		$this->deleteTestStorage();
	}

	protected function login()
	{
		$this->be(User::whereEmail('bogus@bogus.com')->first());
	}

	/**
	 * Clear existing event listeners and reregister them.
	 * @return void
	 */
    private function registerModelEvents() {

    	$models = [
    		'Marker',
    		'Photo'
    	];

        // Reset each model event listeners.
        foreach ($models as $model) {

            // Flush existing listeners.
            call_user_func(array($model, 'flushEventListeners'));

            // Reregister them.
            call_user_func(array($model, 'boot'));
        }
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
