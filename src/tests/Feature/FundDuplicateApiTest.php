<?php

use App\Models\FundDuplicatesCandidate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FundDuplicateApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test listing duplicates.
     *
     * @return void
     */
    public function test_list_funds_duplicate()
    {
        $perPage = 5;
        $page = 1;

        FundDuplicatesCandidate::factory()->times(10)->create();

        //Check first page
        $response = $this->get("/api/duplicates?pagination[perPage]=$perPage&pagination[page]=$page");
        $response->assertStatus(200)
            ->assertJson([
                'page' => $page,
                'perPage' => $perPage,
                'hasNext' => true
            ]);

        //Check second page and pagination
        $page = 2;
        $response = $this->get("/api/duplicates?pagination[perPage]=$perPage&pagination[page]=$page");
        $response->assertStatus(200)
            ->assertJson([
                'page' => $page,
                'perPage' => $perPage,
                'hasNext' => false
            ]);
    }

    /**
     * Test getting a specific fund duplicate.
     *
     * @return void
     */
    public function test_get_fund()
    {
        /** @var FundDuplicatesCandidate $fd */
        $fd = FundDuplicatesCandidate::factory()->create(['resolved'=> true]);
        $response = $this->get("/api/duplicates/{$fd->id}");
        $response->assertStatus(200)
            ->assertJson([
                'resolved' => $fd->resolved,
                'parent'     => [
                    'name' => $fd->parent->name
                ],
                'duplicate'  => [
                    'name' => $fd->duplicate->name
                ]
            ]);
    }

    /**
     * Test updating a specific fund duplicate.
     *
     * @return void
     */
    public function test_update_fund()
    {
        /** @var FundDuplicatesCandidate $fundCandidate */
        $fundCandidate = FundDuplicatesCandidate::factory()->create();

        $response = $this->put("/api/duplicates/{$fundCandidate->id}");
        $response->assertStatus(404);

    }

    /**
     * Test deleting a specific fund duplicate.
     *
     * @return void
     */
    public function test_delete_fund()
    {
        /** @var FundDuplicatesCandidate $fd */
        $fd = FundDuplicatesCandidate::factory()->create();
        $response = $this->delete("/api/duplicates/{$fd->id}");
        $response->assertStatus(404);
    }

    /**
     * Test creating a new fund duplicate.
     *
     * @return void
     */
    public function test_create_fund()
    {
        $response = $this->post('/api/duplicates/');
        $response->assertStatus(404);
    }
}

