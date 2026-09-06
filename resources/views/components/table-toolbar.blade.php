@props(['search' => '', 'placeholder' => 'Search...', 'action' => null])

<div class="table-toolbar">
    <form method="GET" action="{{ $action ?? url()->current() }}" class="table-search-form">
        <input
            type="text"
            name="search"
            class="form-control"
            placeholder="{{ $placeholder }}"
            value="{{ $search }}"
        >
        <button type="submit" class="btn btn-accent">Search</button>
        @if ($search)
            <a href="{{ url()->current() }}" class="btn btn-outline-secondary">Clear</a>
        @endif
    </form>
    @if (isset($slot) && ! $slot->isEmpty())
        <div>{{ $slot }}</div>
    @endif
</div>
