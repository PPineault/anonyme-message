<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    <title>message anonyme</title>
</head>

<body class="container">
    <h1 class="text-center my-4">Envoyer un message anonyme </h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form id="send-message-form" action="{{ route('messages.store') }}" method="post" class="mb-4">
        @csrf
        <div class="mb-3">
            <textarea id="content" name="content" rows="4" class="form-control"></textarea>
            @error('content')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>

    @if (isset($messages) && $messages->count() > 0)
        <div class="message-container">
            <h2 class="text-center my-4">Vos messages :</h2>

            @foreach ($messages as $msg)
                <div class="card mb-3">
                    <div class="card-body">
                        <p>{{ $msg->content }}</p>
                        <a href="{{ route('messages.edit', $msg->id) }}" class="btn btn-primary btn-sm">Modifier</a>
                        <form action="{{ route('messages.destroy', $msg->id) }}" method="POST"
                            style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </div>
                </div>
            @endforeach

        </div>

    @endif


    <script>
        $(document).ready(function() {
            let lastMessageId = 0;
            let messages = [];
            let isTyping = false;
            let refreshInterval;

            function reloadPage() {
                location.reload();
            }

            function checkForNewMessages() {
                $.ajax({
                    url: "{{ route('messages.getNew') }}",
                    data: {
                        last_message_id: lastMessageId,
                    },
                    success: function(data) {
                        if (data.length > 0) {
                            data.forEach(function(message) {
                                // Vérifier si le message n'a pas déjà été ajouté
                                if (!messages.some((m) => m.id === message.id)) {
                                    addMessageToPage(message);
                                    messages.push(message);
                                    lastMessageId = message.id;
                                }
                            });
                        }
                    },
                    error: function() {
                        console.log("Erreur lors de la récupération des messages");
                    },
                });
            }

            function addMessageToPage(message) {
                let messageHtml = `
    <div id="message-${message.id}" class="message-item">
        <p>${message.content}</p>
        <a href="{{ route('messages.edit', ':id') }}" class="btn btn-primary btn-sm edit-message" data-message-id="${message.id}">Modifier</a>
        <form action="{{ route('messages.destroy', ':id') }}" method="POST" style="display: inline;" class="delete-message-form" data-message-id="${message.id}">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
        </form>
    </div>
    `.replace(/':id'/g, message.id);
                $(".message-container").append(messageHtml);
            }

            function updateMessageOnPage(messageId, newContent) {
                $(`#message-${messageId} p`).text(newContent);
            }

            function removeMessageFromPage(messageId) {
                $(`#message-${messageId}`).remove();
                messages = messages.filter((m) => m.id !== messageId);
            }

            function startRefreshInterval() {
                refreshInterval = setInterval(function() {
                    if (!isTyping) {
                        checkForNewMessages();
                        reloadPage();
                    }
                }, 5000);
            }

            function stopRefreshInterval() {
                clearInterval(refreshInterval);
            }

            $("#send-message-form").on("submit", function(e) {
                e.preventDefault();

                var content = $("#content").val();

                $.ajax({
                    url: "{{ route('messages.store') }}",
                    type: "POST",
                    data: {
                        content: content,
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        $("#content").val("");
                        checkForNewMessages(); // Recharger les messages après l'envoi
                    },
                    error: function() {
                        console.log("Erreur lors de l'envoi du message");
                    },
                });
            });

            // Gestion de la modification des messages
            $(document).on("click", ".edit-message", function() {
                var messageId = $(this).data("message-id");
                window.location.href =
                    "{{ route('messages.edit', ':id') }}".replace(":id", messageId);
            });

            // Gestion de la mise à jour des messages
            $(document).on("submit", "#edit-message-form", function(e) {
                e.preventDefault();

                var messageId = $(this).data("message-id");
                var newContent = $("#content").val();

                $.ajax({
                    url: "{{ route('messages.update', ':id') }}".replace(
                        ":id",
                        messageId
                    ),
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: "PUT",
                        content: newContent,
                    },
                    success: function() {
                        updateMessageOnPage(messageId, newContent);
                    },
                    error: function() {
                        console.log("Erreur lors de la mise à jour du message");
                    },
                });
            });

            // Gestion de la suppression des messages
            $(document).on("submit", ".delete-message-form", function(e) {
                e.preventDefault();

                var messageId = $(this).data("message-id");

                $.ajax({
                    url: "{{ route('messages.destroy', ':id') }}".replace(
                        ":id",
                        messageId
                    ),
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: "DELETE",
                    },
                    success: function() {
                        removeMessageFromPage(messageId);
                    },
                    error: function() {
                        console.log("Erreur lors de la suppression du message");
                    },
                });
            });

            // Suivre l'état du champ de texte
            $("#content")
                .on("focus", function() {
                    isTyping = true;
                    stopRefreshInterval();
                })
                .on("blur", function() {
                    isTyping = false;
                    startRefreshInterval();
                });

            // Démarrer l'intervalle de rafraîchissement initial
            startRefreshInterval();
        });
    </script>
    <script></script>





</body>

</html>
