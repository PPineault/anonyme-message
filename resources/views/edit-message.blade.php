<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <title>Modifier un message</title>
</head>

<body>
    <h1>Modifier un message</h1>

    <form action="{{ route('messages.update', $message->id) }}" method="post">
        @csrf
        @method('PUT')
        <div>
            <textarea id="content" name="content" rows="4" cols="50">{{ $message->content }}</textarea>
            @error('content')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit">Enregistrer</button>
    </form>
</body>

</html>
