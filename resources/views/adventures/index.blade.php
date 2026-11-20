<!DOCTYPE html>
<html>
<head>
    <title>Adventures</title>
</head>
<body>

    <h1>All Adventures</h1>

    @if(session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    <a href="{{ route('adventures.create') }}">Add New Adventure</a>

    <hr>

    @forelse($adventures as $adventure)

        <h3>{{ $adventure->title }}</h3>

        <p>{{ $adventure->description }}</p>

        <p>Location: {{ $adventure->location }}</p>

        <p>Price: {{ $adventure->price }}</p>

        <a href="{{ route('adventures.show', $adventure->id) }}">
            Show
        </a>

        <a href="{{ route('adventures.edit', $adventure->id) }}">
            Edit
        </a>

        <form action="{{ route('adventures.destroy', $adventure->id) }}"
              method="POST">

            @csrf
            @method('DELETE')

            <button type="submit">Delete</button>

        </form>

        <hr>

    @empty

        <p>No adventures found.</p>

    @endforelse

    {{ $adventures->links() }}

</body>
</html>