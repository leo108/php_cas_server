Click here to reset your password: <a href="{{ $link = route('password.reset.get', ['token' => $token]).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a>
