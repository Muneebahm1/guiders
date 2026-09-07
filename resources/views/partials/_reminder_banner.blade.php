@if (($reminders ?? collect())->isNotEmpty())
    <div class="alert alert-warning" id="reminderPopup">
        <strong>Reminders</strong>
        <ul class="mb-0 ps-3">
            @foreach ($reminders as $reminder)
                <li>{{ $reminder }}</li>
            @endforeach
        </ul>
    </div>
@endif
