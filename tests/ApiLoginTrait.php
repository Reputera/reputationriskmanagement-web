<?php

trait ApiLoginTrait
{
    protected function apiLoginCall($params, $username, $key)
    {
        $this->apiCall(
            'POST',
            'login',
            $params,
            [],
            [],
            ['PHP_AUTH_USER' => $username, 'PHP_AUTH_PW' => $key]
        );
    }
}
