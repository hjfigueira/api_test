<?php

namespace Tests\Feature;

use App\Models\Fund;
use App\Models\FundManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FundApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test listing funds.
     *
     * @return void
     */
    public function test_list_funds()
    {
        $perPage = 5;
        $page = 1;

        Fund::factory()->times(10)->create();

        //Check first page
        $response = $this->get("/api/fund?pagination[perPage]=$perPage&pagination[page]=$page");
        $response->assertStatus(200)
            ->assertJson([
                'page' => $page,
                'perPage' => $perPage,
                'hasNext' => true
            ]);

        //Check second page and pagination
        $page = 2;
        $response = $this->get("/api/fund?pagination[perPage]=$perPage&pagination[page]=$page");
        $response->assertStatus(200)
            ->assertJson([
                'page' => $page,
                'perPage' => $perPage,
                'hasNext' => false
            ]);

        //Check for filters
        /** @var Fund $fund */
        $fund = Fund::factory()->create();
        $response = $this->get("/api/fund?filter[name][equal]={$fund->name}");
        $response->assertStatus(200)
            ->assertJson([
                'hasNext' => false,
                'data' => [
                    [
                        'name' => $fund->name
                    ]
                ]
            ]);

    }

    /**
     * Test getting a specific fund.
     *
     * @return void
     */
    public function test_get_fund()
    {
        /** @var Fund $fund */
        $fund = Fund::factory()->create();

        $response = $this->get("/api/fund/{$fund->id}");

        $response->assertStatus(200)
            ->assertJson([
                'name' => $fund->name,
                'start_year' => $fund->start_year,
                'fund_manager' => [
                    'id' => $fund->manager->id,
                    'name' => $fund->manager->name
                ]
            ]);
    }

    /**
     * Test updating a specific fund.
     *
     * @return void
     */
    public function test_update_fund()
    {
        /** @var Fund $fund */
        $fund = Fund::factory()->create();
        $newName = 'Another new name';

        $response = $this->put("/api/fund/{$fund->id}", [ 'name' => $newName ]);
        $response->assertStatus(200)
            ->assertJson([
                'name' => $newName
            ]);

        /** @var Fund $updatedFund */
        $updatedFund = Fund::query()->find($fund->id);
        $this->assertSame($updatedFund->name, $newName);

    }

    /**
     * Test deleting a specific fund.
     *
     * @return void
     */
    public function test_delete_fund()
    {
        /** @var Fund $fund */
        $fund = Fund::factory()->create();
        $response = $this->delete("/api/fund/{$fund->id}");
        $response->assertStatus(200);

        /** @var Fund $deleted */
        $deleted = Fund::query()->find($fund->id);
        $this->assertNull($deleted);
    }

    /**
     * Test creating a new fund.
     *
     * @return void
     */
    public function test_create_fund()
    {
        /** @var FundManager $fundManager */
        $fundManager = FundManager::factory()->create();

        $response = $this->post('/api/fund/', [
            'name' => 'Fund Name',
            'start_year' => 2022,
            'fund_manager_id' => $fundManager->id,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'name' => 'Fund Name',
                'start_year' => 2022,
                'fund_manager' => [
                    'id' => $fundManager->id,
                    'name' => $fundManager->name,
                ]
            ]);

        $createdData = $response->json();
        $createdId = $createdData['id'] ?? null;
        $this->assertNotNull($createdId);

        /** @var Fund $createdModel */
        $createdModel = Fund::query()->find($createdId);
        $this->assertNotNull($createdModel);

        $this->assertSame($createdModel->name, 'Fund Name');
        $this->assertSame($createdModel->start_year, 2022);
    }
}

