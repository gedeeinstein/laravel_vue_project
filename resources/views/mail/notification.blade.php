{{-- This Mail For Reset Password --}}
{{-- Outro Lines --}}
@foreach ($outroLines as $line)
    {{ $line }}
@endforeach

{{-- Subcopy --}}
@isset($actionText)
    @component('mail::subcopy')
        If you’re having trouble clicking the "{{ $actionText }}" button, copy and paste the URL below
        into your web browser: [{{ $actionUrl }}]({{ $actionUrl }})
    @endcomponent
@endisset
