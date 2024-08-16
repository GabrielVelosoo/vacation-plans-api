<?php

namespace Tests\Unit;

use App\Models\HolidayPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;

class HolidayPlanTest extends TestCase
{

    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {

        parent::setUp();

        //Creates a user and generates a token for authentication
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);

    }

    //Can I create a vacation plan?(test)
    #[Test]
    public function can_i_create_a_vacation_plan()
    {

        $data = [
            'title' => 'Vacation in Thailand',
            'description' => 'Trip to Bangkok and the islands',
            'date' => '2024-11-15',
            'location' => 'Bangkok, Thailand',
            'participants' => 'Alice and Bob',
        ];

        $response = $this->postJson('/api/vacation-plans', $data);

        $response->assertStatus(201)
                 ->assertJson([
                    'title' => 'Vacation in Thailand',
                    'description' => 'Trip to Bangkok and the islands',
                    'date' => '2024-11-15',
                    'location' => 'Bangkok, Thailand',
                 ]);

        $this->assertDatabaseHas('holiday_plans', [
            'title' => 'Vacation in Thailand',
        ]);

    }

    //Can I get all the vacation plans?(test)
    #[Test]
    public function can_i_get_all_vacation_plan()
    {
        
        $holidayPlan = HolidayPlan::factory()->create([
            'title' => 'Vacation in Thailand',
            'description' => 'Trip to Bangkok and the islands',
            'date' => '2024-11-15',
            'location' => 'Bangkok, Thailand',
            'participants' => 'Alice and Bob',
        ]);

        $response = $this->getJson('/api/vacation-plans');

        $response->assertStatus(200);

    }

    //Can I get a specific vacation plan?(test)
    #[Test]
    public function can_i_get_single_vacation_plan()
    {

        $holidayPlan = HolidayPlan::factory()->create([
            'title' => 'Vacation in Thailand',
            'description' => 'Trip to Bangkok and the islands',
            'date' => '2024-11-15',
            'location' => 'Bangkok, Thailand',
            'participants' => 'Alice and Bob',
        ]);

        $response = $this->getJson('/api/vacation-plans/' . $holidayPlan->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'title' => $holidayPlan->title,
                 ]);

    }

    //Can I update a vacation plan?(test)
    #[Test]
    public function can_i_update_vacation_plan()
    {

        $holidayPlan = HolidayPlan::factory()->create([
            'title' => 'Vacation in Thailand',
            'description' => 'Trip to Bangkok and the islands',
            'date' => '2024-11-15',
            'location' => 'Bangkok, Thailand',
            'participants' => 'Alice and Bob',
        ]);

        $data = [
            'title' => 'Updated Vacation',
            'description' => 'Updated Vacation',
            'date' => '2025-04-10',
            'location' => 'Chicago, USA',
            'participants' => 'Joel and Alice',
        ];

        $response = $this->putJson('/api/vacation-plans/' . $holidayPlan->id, $data);

        $response->assertStatus(200)
            ->assertJson([
                'title' => 'Updated Vacation',
                'description' => 'Updated Vacation',
                'date' => '2025-04-10',
                'location' => 'Chicago, USA',
                'participants' => 'Joel and Alice',
            ]);

        $this->assertDatabaseHas('holiday_plans', [
            'title' => 'Updated Vacation',
            'description' => 'Updated Vacation',
            'date' => '2025-04-10',
            'location' => 'Chicago, USA',
            'participants' => 'Joel and Alice',
        ]);

    }

    //Can I partially update a vacation plan?(test)
    #[Test]
    public function can_i_partially_update_vacation_plan()
    {

        $holidayPlan = HolidayPlan::factory()->create([
            'title' => 'Vacation in Thailand',
            'description' => 'Trip to Bangkok and the islands',
            'date' => '2024-11-15',
            'location' => 'Bangkok, Thailand',
            'participants' => 'Alice and Bob',
        ]);

        $data = [
            'title' => 'Updated Vacation Patch',
        ];

        $response = $this->patchJson('/api/vacation-plans/' . $holidayPlan->id, $data);

        $response->assertStatus(200)
            ->assertJson([
                'title' => 'Updated Vacation Patch',
                'description' => 'Trip to Bangkok and the islands',
                'date' => '2024-11-15',
                'location' => 'Bangkok, Thailand',
                'participants' => 'Alice and Bob',
            ]);

        $this->assertDatabaseHas('holiday_plans', [
            'id' => $holidayPlan->id,
            'title' => 'Updated Vacation Patch',
            'description' => 'Trip to Bangkok and the islands',
            'date' => '2024-11-15',
            'location' => 'Bangkok, Thailand',
            'participants' => 'Alice and Bob',
        ]);

    }


    //Can i delete a vacation plan?(test)
    #[Test]
    public function can_i_delete_vacation_plan()
    {

        $holidayPlan = HolidayPlan::factory()->create([
            'title' => 'Vacation in Thailand',
            'description' => 'Trip to Bangkok and the islands',
            'date' => '2024-11-15',
            'location' => 'Bangkok, Thailand',
            'participants' => 'Alice and Bob',
        ]);

        $response = $this->deleteJson('/api/vacation-plans/' . $holidayPlan->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('holiday_plans', [
            'id' => $holidayPlan->id,
        ]);

    }

    //Can i generate a pdf for a vacation plan?(test)
    #[Test]
    public function can_i_generate_pdf_for_vacation_plan()
    {

        $holidayPlan = HolidayPlan::factory()->create([
            'title' => 'Vacation in Thailand',
            'description' => 'Trip to Bangkok and the islands',
            'date' => '2024-11-15',
            'location' => 'Bangkok, Thailand',
            'participants' => 'Alice and Bob',
        ]);

        $response = $this->getJson('/api/vacation-plans/' . $holidayPlan->id . '/pdf');

        $response->assertStatus(200);

        $this->assertTrue(
            $response->headers->get('content-type') === 'application/pdf'
        );

    }

}
