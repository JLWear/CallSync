<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Livre d'Or</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            padding: 20px;
            max-width: 700px;
            margin: auto;
        }

        h1, h2 {
            text-align: center;
            color: #2c3e50;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }

        form div {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #2980b9;
        }

        .message {
            background-color: #fff;
            padding: 15px;
            border-left: 4px solid #3498db;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 1px 5px rgba(0,0,0,0.05);
        }

        .message img {
            max-width: 100%;
            max-height: 200px;
            margin-top: 10px;
            border-radius: 6px;
        }

        .success {
            background-color: #dff0d8;
            color: #3c763d;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 5px solid #3c763d;
        }
    </style>
</head>
<body>
    <h1>Livre d'Or</h1>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('livreor.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="nom">Nom :</label>
            <input type="text" name="nom" id="nom" required>
        </div>

        <div>
            <label for="message">Message :</label>
            <textarea name="message" id="message" rows="4" required></textarea>
        </div>

        <div>
            <label for="image">Image (optionnelle) :</label>
            <input type="file" name="image" id="image" accept="image/*">
        </div>

        <button type="submit">Envoyer</button>
    </form>

    <h2>Messages :</h2>

    @forelse($messages as $msg)
        <div class="message">
            <strong>{{ $msg['nom'] }}</strong> <em>({{ $msg['date'] }})</em>
            <p>{{ $msg['message'] }}</p>

            @if(!empty($msg['image']))
                <img src="{{ asset('storage/' . $msg['image']) }}" alt="Image">
            @endif
        </div>
    @empty
        <p>Aucun message pour le moment.</p>
    @endforelse

</body>
</html>
