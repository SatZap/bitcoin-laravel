<?php

namespace Tests\Feature;

use App\Events\PostCreated;
use App\Listeners\CreditNewPostSats;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public $verifiedUser;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([
            'email_verified_at' => Carbon::now(),
        ]);

        $this->verifiedUser = $user;
    }

    public function test_user_can_submit_a_post(): void
    {
        $this->post('/login', [
            'email' => $this->verifiedUser->email,
            'password' => 'password',
        ]);

        $this
            ->actingAs($this->verifiedUser)
            ->post(route('post.store'), [
                'title' => 'This is the best title ever made by mankind.',
                'body' => "There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.",
           ])
            ->assertSessionDoesntHaveErrors()
            ->assertViewIs('post.show');

        $this->assertDatabaseHas('posts', [
            'title' => 'This is the best title ever made by mankind.',
        ]);
    }

    public function test_user_receives_bitcoin_when_they_submit_post(): void
    {
        Event::fake();

        $this->post('/login', [
            'email' => $this->verifiedUser->email,
            'password' => 'password',
        ]);

        $this
            ->actingAs($this->verifiedUser)
            ->post(route('post.store'), [
                'title' => 'This is the best title ever made by mankind.',
                'body' => "There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.",
            ]);

        Event::assertDispatched(PostCreated::class);

        $post = Post::first();
        $event = new PostCreated($post);
        $listener = new CreditNewPostSats();
        $listener->handle($event);

        $this->assertDatabaseHas('transactions', [
            'amount' => env('CREDIT_NEW_POST'),
        ]);
    }

    public function test_post_must_pass_validation(): void
    {
        $this->post('/login', [
            'email' => $this->verifiedUser->email,
            'password' => 'password',
        ]);

        $this
            ->actingAs($this->verifiedUser)
            ->post(route('post.store'), [
                'title' => 'asdfasdf',
                'body' => "asdfasdf",
            ])
            ->assertSessionHasErrors(['title', 'body']);
    }

    public function test_user_can_render_post_submission_route(): void
    {
        $this->post('/login', [
            'email' => $this->verifiedUser->email,
            'password' => 'password',
        ]);

        $this
            ->actingAs($this->verifiedUser)
            ->get(route('post.create'))
            ->assertOk();
    }
}
