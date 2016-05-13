<?php

trait ApiAccessTrait
{
    protected function apiResetPasswordCall($params, $username, $key)
    {
        $this->makeApiCall('password/reset', $params, $username, $key);
    }

    protected function apiLoginCall($params, $username, $key)
    {
        $this->makeApiCall('login', $params, $username, $key);
    }

    protected function makeApiCall($path, $params, $username, $key)
    {
        $this->apiCall(
            'POST',
            $path,
            $params,
            [],
            [],
            ['PHP_AUTH_USER' => $username, 'PHP_AUTH_PW' => $key]
        );
    }
}
