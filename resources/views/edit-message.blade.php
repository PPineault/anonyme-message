<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <title>Modifier un message</title>
    <style>
        body {
            background: blue;
            color: black
        }

        h1,
        h2 {
            color: white
        }
    </style>
</head>

<body class="container">
    <h1 class="text-center my-4">Modifier un message</h1>

    <form action="{{ route('messages.update', $message->id) }}" method="post" class="mb-4">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <textarea id="content" name="content" rows="4" class="form-control">{{ $message->content }}</textarea>
            @error('content')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>

</body>

</html>
