<?php

namespace App\Services\Transaction\Authorization;

interface AuthorizationServiceInterface
{

    public function authorized(): bool;
}
