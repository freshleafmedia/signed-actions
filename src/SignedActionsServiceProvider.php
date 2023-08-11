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
        URL::macro('signedAction', function (string $name, array $parameters = [], \DateInterval|\DateTimeInterface|int $expiration = null, bool $absolute = true): string {
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

        URL::macro('temporarySignedAction', function (string $name, \DateInterval|\DateTimeInterface|int $expiration, array $parameters = [], bool $absolute = true): string {
            /** @var UrlGenerator $this */

            return $this->signedAction($name, $parameters, $expiration, $absolute);
        });
    }
}
