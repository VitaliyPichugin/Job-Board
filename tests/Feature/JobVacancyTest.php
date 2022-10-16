<?php declare(strict_types=1);

namespace Tests\Feature;

use App\Models\JobVacancy;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Tests\TestCase;

class JobVacancyTest extends TestCase
{
    const URI = 'api/job';

    /**
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $email = config('api.apiEmail');
        $password = config('api.apiPassword');
        $this->json('POST', 'api/auth/login', [
            'email' => $email,
            'password' => $password,
        ]);
    }

    public function test_index()
    {
        $this->json('GET', self::URI . '/all')->assertStatus(200);
    }

    public function test_show()
    {
        $user = $this->createUser();
        $job = $this->createJobVacancy($user);

        $this->json('GET', self::URI . "/detail/$job->id")->assertStatus(200);
    }

    public function test_list_job_vacancies()
    {
        $tags = Tag::query()->take(15)->get()->random(7);
        $user = $this->createUser();
        $job = $this->createJobVacancy($user);
        $tag_ids = $tags->pluck('id');
        $job->tags()->attach($tag_ids);

        $request = [
            'sort_date' => 'asc',
            'sort_response' => 'desc',
            'tags' => $tag_ids ?? null,
            'date' => $job->created_at,
            'week' => Carbon::now()->weekOfYear,
            'day' => Carbon::now()->dayOfYear,
            'month' => Carbon::now()->month,
            'year' => Carbon::now()->year,
        ];

        $this->json('GET', self::URI . "/list-of-job-vacancies", $request)
            ->assertStatus(200)
            ->assertJsonCount(1)
        ;
    }

    public function test_delete_job_vacancy()
    {
        $user = $this->createUser();
        $job = $this->createJobVacancy($user);

        $this->json('DELETE', self::URI . "/$job->id")->assertSuccessful();

        $this->assertDatabaseHas('job_vacancies', [
            'id' => $job->id,
        ]);
    }

    public function test_delete_job_vacancy_response()
    {
        $user = $this->createUser();
        $job = $this->createJobVacancy($user);

        $user = User::find(auth('api')->id());
        $responses = $user->responses();
        $responses->create([
                               'job_id' => $job->id,
                               'user_id' => $user->id,
                           ]);
        $id = $responses->get()->first()['id'];
        $this->json('DELETE', self::URI . "/response/$id")
            ->assertSuccessful()
        ;

        $this->assertDatabaseHas('job_vacancy_responses', [
            'id' => $id,
        ]);
    }

    public function test_send_response()
    {
        $response = $this->createUser();

        $job = new JobVacancy();
        $job->title = "Test title";
        $job->description = 'test description';
        $job->user_id = $response['user_id'];
        $job->save();

        $this->createUser();//other user for send response

        $request = [
            'job_id' => $job->id,
            'author_id' => $response['user_id'],
        ];

        $this->json('POST', self::URI . "/send-response", $request)
            ->assertSuccessful()
        ;
    }

    public function test_create_job_vacancy()
    {
        $tags = Tag::query()->take(15)->get();

        $request = [
            'title' => 'test title',
            'description' => 'test description',
            'tags' => $tags->random(3)->pluck('id') ?? null,
        ];
        $this->json('POST', self::URI . "/create", $request)
            ->assertSuccessful()
        ;
    }

    public function test_update_job_vacancy()
    {
        $tags = Tag::query()->take(10)->get();
        $response = $this->createUser();

        $job = new JobVacancy();
        $job->title = "Test title";
        $job->description = 'test description';
        $job->user_id = $response['user_id'];
        $job->save();

        $request = [
            'title' => 'update test title',
            'description' => 'update test description',
            'tags' => $tags->random(3)->pluck('id') ?? null,
        ];
        $this->json('POST', self::URI . "/update/$job->id", $request)
            ->assertSuccessful()
        ;
    }

    public function test_get_liked()
    {
        $this->json('GET', self::URI . '/liked')->assertStatus(200);
    }

    private function createUser()
    {
        $user['name'] = 'Test User';
        $user['email'] = Str::random(5) . '@mail.com';
        $user['password'] = 'password';
        $user['password_confirmation'] = 'password';

        $response = $this->json('POST', 'api/auth/register', $user);

        $this->assertDatabaseHas('users', [
            'id' => $response['user_id'],
            'name' => $response['name'],
            'email' => $response['email'],
        ]);

        return $response;
    }

    private function createJobVacancy($response)
    {
        $job = new JobVacancy();
        $job->title = "Test title";
        $job->description = 'test description';
        $job->user_id = $response['user_id'];
        $job->save();

        return $job;
    }
}
