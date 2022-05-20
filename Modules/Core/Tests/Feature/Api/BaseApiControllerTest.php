<?php

namespace Modules\Core\Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BaseApiControllerTest extends TestCase
{
    /**
     * Check version api test - [GET]
     *
     * @return void
     */
    public function testCheckVersionGetApi()
    {
        $this->getJson(route('core.base-api.check-version.get.api'))
            ->assertOk()
            ->assertExactJson(['code'=>200,'message' => "success"]);
    }
}
