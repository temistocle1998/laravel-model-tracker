<?php
namespace Tracker\Tests;
use PHPUnit\Framework\TestCase;
use Tracker\Models\ModelChange;
use Illuminate\Database\Capsule\Manager as DB;

class TrackerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Boot the database connection using Eloquent (without Laravel)
        $this->setUpDatabase();
    }

    protected function setUpDatabase()
    {
        $db = new DB;

        // Configure the SQLite in-memory database
        $db->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);

        // Set the global Eloquent database connection
        $db->bootEloquent();
        $db->setAsGlobal();

        // Run migrations
        DB::schema()->create('model_changes', function ($table) {
            $table->id();
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->unsignedBigInteger('user_id');
            $table->json('changes');
            $table->timestamps();
        });
    }

    /** @test */
    public function it_tracks_changes_on_model_update()
    {
        // Simulate an update on a model and track the change
        $modelChange = ModelChange::create([
            'model_type' => 'App\\Product',
            'model_id' => 1,
            'user_id' => 1,
            'changes' => json_encode([
                'name' => [
                    'old_value' => 'Old Name',
                    'new_value' => 'New Name',
                ],
            ]),
        ]);

        // Assert the record was saved correctly
        $this->assertDatabaseHas('model_changes', [
            'model_type' => 'App\\Product',
            'model_id' => 1,
            'user_id' => 1,
        ]);
    }

    protected function assertDatabaseHas($table, array $data)
    {
        $exists = DB::table($table)->where($data)->exists();
        $this->assertTrue($exists, "Failed asserting that a row in the table [$table] matches the given data.");
    }
}
