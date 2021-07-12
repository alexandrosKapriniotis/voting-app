<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GravatarTest extends TestCase
{
    use RefreshDatabase;

    /**
     *
     * @test
     */
    public function user_can_generate_gravatar_default_image_when_no_email_found()
    {
        $user = User::factory()->create([
            'name'  => 'Andre',
            'email' =>  'afakeemail.com@fakeemail.com'
        ]);

        $gravatarUrl = $user->getAvatar();

        $this->assertEquals('https://www.gravatar.com/avatar/'.md5($user->email).'?s=200'.'&d=s3.amazonaws.com/laracasts/images/forum/avatars/default-avatar-1.png',$gravatarUrl);

        $response = Http::get($user->getAvatar());

        $this->assertTrue($response->successful());
    }
}
