<?php

namespace Tests\Feature;

use App\Events\NewMessage;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Notification;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SendMessageToChannelTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanSendMessagesToTheirCurrent()
    {
        Broadcast::shouldReceive('event')->once()->andReturnSelf();

        $user = $this->createUser();

        $response = $this->actingAs($user, 'api')
            ->postJson("/api/channels/{$user->current_channel_id}/messages", [
                'content' => 'Hello there',
                'uuid' => Uuid::uuid4()->toString(),
            ]);

        $response->assertStatus(201);
    }

    public function testUsersFromOtherCompaniesCannotAccessTheChannel()
    {
        $user = $this->createUser();
        $otherUser = $this->createUser();

        $response = $this->actingAs($otherUser, 'api')
            ->postJson("/api/channels/{$user->current_channel_id}/messages", [
                'content' => 'Hello there',
                'uuid' => Uuid::uuid4()->toString(),
            ]);

        $response->assertStatus(403);
    }

    public function testUsersCanOnlySendNotificationsToChannelsTheyHaveJoined()
    {
        $user = $this->createUser();
        $otherChannel = $user->currentCompany->channels()->create(['name' => 'backend']);

        $response = $this->actingAs($user, 'api')
            ->postJson("/api/channels/{$otherChannel->id}/messages", [
                'content' => 'Hello there',
                'uuid' => Uuid::uuid4()->toString(),
            ]);

        $response->assertStatus(403);
    }
}
