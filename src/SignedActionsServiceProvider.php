<?php

declare(strict_types=1);

namespace FreshleafMedia\SignedActions;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\URL;

class SignedActionsServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot(): void
    {
        URL::macro('signedAction', function ($name, $parameters = [], $expiration = null, $absolute = true) {
            /** @var UrlGenerator $this */

            $this->ensureSignedRouteParametersAreNotReserved(
                $parameters = Arr::wrap($parameters)
            );

            if ($expiration) {
                $parameters = $parameters + ['expires' => $this->availableAt($expiration)];
            }

            ksort($parameters);

            $key = call_user_func($this->keyResolver);

            return $this->action($name, $parameters + [
                    'signature' => hash_hmac('sha256', $this->action($name, $parameters, $absolute), $key),
                ], $absolute);
        });

        URL::macro('temporarySignedAction', function ($name, $expiration, $parameters = [], $absolute = true) {
            /** @var UrlGenerator $this */

            return $this->signedAction($name, $parameters, $expiration, $absolute);
        });
    }
}
