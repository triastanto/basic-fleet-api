<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

class TrustHosts extends Middleware
{
    /**
     * Get the host patterns that should be trusted.
     *
     * @return array<int, string|null>
     * @codeCoverageIgnore
     */
    public function hosts(): array
    {
        // TODO: Write test here
        return [
            $this->allSubdomainsOfApplicationUrl(),
        ];
    }
}
