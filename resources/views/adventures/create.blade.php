<!DOCTYPE html>
<html>

<head>
    <title>Create Adventure</title>
</head>

<body>

    <h1>Create New Adventure</h1>

    <a href="{{ route('adventures.index') }}">Back</a>

    @if ($errors->any())
        <ul style="color: red">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('adventures.store') }}" method="POST">
        @csrf

        <label>Title:</label>
        <input type="text" name="title" value="{{ old('title') }}">
        <br><br>

        <label>Description:</label>
        <textarea name="description">{{ old('description') }}</textarea>
        <br><br>

        <label>Organizer ID:</label>
        <input type="number" name="activity_organizer_id" value="{{ old('activity_organizer_id') }}">
        <br><br>

        <label>Difficulty:</label>
        <select name="difficulty_level">
            <option value="Easy">Easy</option>
            <option value="Medium">Medium</option>
            <option value="Hard">Hard</option>
        </select>
        <br><br>

        <label>Capacity:</label>
        <input type="number" name="capacity">
        <br><br>

        <select name="category">
            <option value="hiking">Hiking</option>
            <option value="camping">Camping</option>
            <option value="adventure">Adventure</option>
            <option value="cycling">Cycling</option>
            <option value="water_sports">Water Sports</option>
        </select>

        <label>Location:</label>
        <input type="text" name="location">
        <br><br>

        <label>Duration Hours:</label>
        <input type="number" name="duration_hours">
        <br><br>

        <label>Price:</label>
        <input type="number" step="0.01" name="price">
        <br><br>

        <label>Requirements:</label>
        <textarea name="requirements"></textarea>
        <br><br>

        <label>Status:</label>
        <select name="status">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
        <br><br>



        <button type="submit">Create Adventure</button>

    </form>

</body>

</html>