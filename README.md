# Signed Actions For Laravel

```php
$signedUrl = URL::signedAction(Controller::class, ['param' => 'example']);
$temporaryUrl = URL::temporarySignedAction(Controller::class, CarbonImmutable::tomorrow(), ['param' => 'example']);
```

# Install

```
composer require freshleafmedia/signed-actions
```