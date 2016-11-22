Ashleydw\Mail
=========


This package overrides the default Laravel Mail to return the status of the mail action - Laravel 4.2 was changed to return void on all Mail::send calls in v4.2, see: https://github.com/laravel/framework/commit/894752ffb9aab1c65af92acc1ae7004062a2556d and discussed in https://github.com/laravel/framework/pull/4405#issuecomment-51154875

If you're using Mandrill, or another API that requires a response this is incorrect.

This package overcomes this by extending the default Mail package files and returns the correct responses. It's purposefully built for Mandrill but more Transports can be easily added.

# Mandrill usage:

Same as before, but now you can see the options inside the services file. For example, to set the `async` attribute to false (which I would recommend), your services file will be:

```
<?php

return [
	'mandrill' => [
		'secret'   => 'your-secret',
		'options' => [
			'async' => false,
		]
	],
];
```

You can set any options from the docs (the options are simply added with array_merge): https://mandrillapp.com/api/docs/messages.JSON.html#method-send

Then, to get the response:

```
/** @var \GuzzleHttp\Message\Response $response */
$response = Mail::send(
	$emailTemplate,
	[
		....
	],
	function ($message) use ($user, $action) {
		....
	}
);

/** @var \GuzzleHttp\Stream\Stream $body */
$response = json_decode($response->getBody(), true);
```

Note: You should probably check `$response->getStatusCode()`  somewhere.