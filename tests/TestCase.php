<?php

use App\Entities\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\Facades\DB;

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    use DatabaseTransactions, \SoftDeleteTestTrait;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * The api version prefix.
     *
     * @var string $apiVersionPrefix
     */
    protected $apiVersionPrefix = 'api/';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        if (DB::connection() instanceof SQLiteConnection) {
            DB::statement(DB::raw('PRAGMA foreign_keys = ON'));
        }

        return $app;
    }

    /**
     * Adds the proper prefix to the URI for calling API routes/URIs.
     *
     * @param $method
     * @param $uri
     * @param array $parameters
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @param null $content
     * @return \Illuminate\Http\Response
     */
    protected function apiCall($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        $server = array_merge($server, ['HTTP_X-Requested-With' => 'XMLHttpRequest', 'HTTP_Accept' => 'application/json']);
        return parent::call($method, $this->apiVersionPrefix.$uri, $parameters, $cookies, $files, $server, $content);
    }

    /**
     * Makes an Admin in the DB and logs them in.
     *
     * @param array $attributes
     * @return User
     */
    protected function beLoggedInAsAdmin(array $attributes = [])
    {
        $user = factory(User::class, 'admin')->create($attributes);
        $this->be($user);
        return $user;
    }

    protected function assertJsonUnprocessableEntity($assertErrors = true)
    {
        $this->assertResponseStatus(422); // Asserts Status code is 422.
        $this->assertJsonResponseHasMessage('Unprocessable Entity');
        $this->assertJsonResponseHasStatusCode(422);
        if ($assertErrors) {
            $this->assertJsonResponseHasErrors();
        }
    }

    protected function assertJsonResponseOkAndFormattedProperly($message = 'Success')
    {
        $this->assertResponseOk(); // Asserts Status code is 200.
        $data = $this->response->getData(true);
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayNotHasKey('errors', $data);
        $this->assertJsonResponseHasStatusCode(200);
        $this->assertJsonResponseHasMessage($message);
    }

    protected function assertJsonResponseNotFound($errorMessage = 'Not found')
    {
        $this->assertResponseStatus(404);
        $this->assertJsonResponseHasStatusCode(404);
        $this->assertJsonResponseHasMessage($errorMessage);
    }

    protected function assertJsonResponseError($errorMessage = 'Internal error')
    {
        $this->assertResponseStatus(500);
        $this->assertJsonResponseHasStatusCode(500);
        $this->assertJsonResponseHasMessage($errorMessage);
    }

    protected function assertJsonResponseNotAuthorized($errorMessage = 'Not authorized')
    {
        $this->assertResponseStatus(401);
        $this->assertJsonResponseHasStatusCode(401);
        $this->assertJsonResponseHasMessage($errorMessage);
    }

    protected function assertJsonResponse($code, $errorMessage)
    {
        $this->assertResponseStatus($code);
        $this->assertJsonResponseHasStatusCode($code);
        $this->assertJsonResponseHasMessage($errorMessage);
    }

    protected function assertJsonResponseHasStatusCode($code)
    {
        $data = $this->response->getData(true);
        $this->assertArrayHasKey('status_code', $data);
        $this->assertEquals($code, $data['status_code']);
    }

    protected function assertJsonResponseHasMessage($message)
    {
        $data = $this->response->getData(true);
        $this->assertArrayHasKey('message', $data);
        $this->assertEquals($message, $data['message']);
    }

    protected function assertJsonResponseHasDataKey($key)
    {
        $data = $this->response->getData(true);
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey($key, $data['data']);
    }

    protected function assertJsonResponseHasErrors()
    {
        $data = $this->response->getData(true);
        $this->assertArrayHasKey('errors', $data);
        $this->assertInternalType('array', $data['errors']);
    }

    protected function assertJsonResponseHasError($key, $error)
    {
        $data = $this->response->getData(true);
        $this->assertEquals(array_get($data, 'errors.'. $key), $error);
    }
}
