# Signed Actions For Laravel

```php
$signedUrl = URL::signedAction(Controller::class, ['param' => 'example']);
$temporaryUrl = URL::temporarySignedAction(Controller::class, ['param' => 'example'], CarbonImmutable::tomorrow());
```
