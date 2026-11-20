
<!DOCTYPE html>
<html>
<head>
    <title>Edit Adventure</title>
</head>
<body>

    <h1>Edit Adventure</h1>

    <a href="{{ route('adventures.index') }}">Back</a>

    @if ($errors->any())
        <ul style="color: red">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('adventures.update', $adventure->id) }}"
          method="POST">

        @csrf
        @method('PUT')

        <label>Title:</label>
        <input type="text" name="title"
               value="{{ $adventure->title }}">
        <br><br>

        <label>Description:</label>
        <textarea name="description">{{ $adventure->description }}</textarea>
        <br><br>

        <label>Organizer ID:</label>
        <input type="number" name="activity_organizer_id"
               value="{{ $adventure->activity_organizer_id }}">
        <br><br>

        <label>Difficulty:</label>
        <input type="text" name="difficulty_level"
               value="{{ $adventure->difficulty_level }}">
        <br><br>

        <label>Capacity:</label>
        <input type="number" name="capacity"
               value="{{ $adventure->capacity }}">
        <br><br>

        <label>Category:</label>
        <input type="text" name="category"
               value="{{ $adventure->category }}">
        <br><br>

        <label>Location:</label>
        <input type="text" name="location"
               value="{{ $adventure->location }}">
        <br><br>

        <label>Duration Hours:</label>
        <input type="number" name="duration_hours"
               value="{{ $adventure->duration_hours }}">
        <br><br>

        <label>Price:</label>
        <input type="number" step="0.01" name="price"
               value="{{ $adventure->price }}">
        <br><br>

        <label>Status:</label>
        <input type="text" name="status"
               value="{{ $adventure->status }}">
        <br><br>

        <button type="submit">Update Adventure</button>

    </form>

</body>
</html>
