<?php

test('A user can view available trips', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
