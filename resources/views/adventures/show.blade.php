
<!DOCTYPE html>
<html>
<head>
    <title>Adventure Details</title>
</head>
<body>

    <h1>{{ $adventure->title }}</h1>

    <p><strong>Description:</strong> {{ $adventure->description }}</p>

    <p><strong>Organizer ID:</strong>
        {{ $adventure->activity_organizer_id }}
    </p>

    <p><strong>Difficulty:</strong> {{ $adventure->difficulty_level }}</p>

    <p><strong>Capacity:</strong> {{ $adventure->capacity }}</p>

    <p><strong>Category:</strong> {{ $adventure->category }}</p>

    <p><strong>Location:</strong> {{ $adventure->location }}</p>

    <p><strong>Duration:</strong> {{ $adventure->duration_hours }} hours</p>

    <p><strong>Price:</strong> {{ $adventure->price }}</p>

    <p><strong>Status:</strong> {{ $adventure->status }}</p>

    <br>

    <a href="{{ route('adventures.index') }}">Back</a>

    <a href="{{ route('adventures.edit', $adventure->id) }}">Edit</a>

    
<h2>Add Schedule</h2>

<form action="{{ route('schedules.store') }}" method="POST">

    @csrf

    <input type="hidden" name="adventure_id"
           value="{{ $adventure->id }}">

    <label>Guide ID:</label>
    <input type="number" name="guide_id">
    <br><br>

    <label>Starts At:</label>
    <input type="datetime-local" name="starts_at">
    <br><br>

    <label>Ends At:</label>
    <input type="datetime-local" name="ends_at">
    <br><br>

    <label>Capacity:</label>
    <input type="number" name="capacity">
    <br><br>

    <label>Status:</label>
    <select name="status">
        <option value="available">Available</option>
        <option value="full">Full</option>
        <option value="cancelled">Cancelled</option>
    </select>
    <br><br>

    <button type="submit">Add Schedule</button>


<h2>Upload Adventure Image</h2>

<form action="{{ route('images.store') }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf

    <input type="hidden" name="adventure_id"
           value="{{ $adventure->id }}">

    <label>Select Image:</label>

    <input type="file" name="image_path" accept="image/*">

    <br><br>

    <button type="submit">Upload Image</button>

</form>


</form>


</body>
</html>
