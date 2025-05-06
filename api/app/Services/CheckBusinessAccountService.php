<?php

namespace App\Services;

class CheckBusinessAccountService
{
    private array $linkedAccounts;

    public function __construct($linkedAccounts)
    {
        $this->linkedAccounts = $linkedAccounts;
    }

    public function ensureIsBusinessAccount() {
        foreach ($this->linkedAccounts as $acc) {
            if (isset($acc['instagram_business_account']['id'])) {
                return true;
            }
        }

        return false;
    }
}
