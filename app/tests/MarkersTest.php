<?php

use PhotoMap\Helpers;

class MarkersTest extends BaseTester {

	use TestMarkersTrait;

	protected $usesDb = true;

	protected $loginFirst = true;

	public function test_create_a_marker_and_ensure_output_matches_input()
	{
		$marker = Marker::create($this->testMarker);

		$fields = array_keys($this->testMarker);
		$this->assertEquals($this->testMarker, $marker->onlyFields($fields));
	}

	public function test_creating_marker_fails_when_geometry_is_not_provided()
	{
		$data = $this->testMarker;
		unset($data['geometry']);

		$marker = new Marker($data);

		$this->assertFalse($marker->save());
	}

	public function test_no_markers_are_found_when_current_project_is_set_to_a_project_with_no_associated_markers()
	{
		$lonelyProject = new Project;
		$lonelyProject->name = 'My lonely project';
		$lonelyProject->description = 'No markers will be associated with this project.';
		$lonelyProject->save();

		$project = new Project;
		$project->name = 'My favorite project';
		$project->description = 'All markers will be associated with this project.';
		$project->save();

		$marker = Marker::create(array_merge($this->testMarker, ['project_id' => $project->_id]));
		$marker2 = Marker::create(array_merge($this->testMarker, ['project_id' => $project->_id]));

		Helpers::setCurrentScope('project', $lonelyProject);

		$matches = Marker::all();
		$this->assertEquals(0, $matches->count());
	}

	public function test_marker_is_saved_with_project_id_of_current_project()
	{
		$project = new Project;
		$project->name = 'My favorite project';
		$project->description = 'All markers will be associated with this project.';
		$project->save();

		Helpers::setCurrentScope('project', $project);

		// Since we've set a project, $marker should be associated with it.
		$marker = Marker::create($this->testMarker);

		$matches = Marker::all()->toArray();
		$this->assertEquals(1, count($matches));
		$this->assertEquals($this->testMarker['name'], $matches[0]['name']);
	}

	public function test_marker_is_saved_with_null_project_id_if_no_current_project_is_set()
	{
		Helpers::setCurrentScope('project', null);

		$marker = Marker::create($this->testMarker);

		$this->assertEquals(null, $marker->project_id);
	}

	public function test_all_markers_are_returned_if_no_current_project_is_set()
	{
		Helpers::setCurrentScope('project', null);

		$marker1 = Marker::create(array_merge($this->testMarker, ['project_id' => 'a']));
		$marker2 = Marker::create(array_merge($this->testMarker, ['project_id' => 'b']));
		$marker3 = Marker::create(array_merge($this->testMarker, ['project_id' => 'c']));

		$matches = Marker::all();

		$this->assertEquals(3, $matches->count());
	}

}
