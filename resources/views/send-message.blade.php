<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <title>Envoyer un message</title>
</head>

<body>
    <h1>Envoyer un message</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('messages.store') }}" method="post">
        @csrf
        <div>

            <textarea id="content" name="content" rows="4" cols="50"></textarea>
            @error('content')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit">Envoyer</button>
    </form>

    @if (isset($messages) && $messages->count() > 0)
        <div class="message-container">
            <h2>Vos messages :</h2>

            @foreach ($messages as $msg)
                <div>
                    <p>{{ $msg->content }}</p>
                    <a href="{{ route('messages.edit', $msg->id) }}" class="btn btn-primary btn-sm">Modifier</a>
                    <form action="{{ route('messages.destroy', $msg->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                    </form>
                </div>
            @endforeach

        </div>
    @endif
</body>

</html>
