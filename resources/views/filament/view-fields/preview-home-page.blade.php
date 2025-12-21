@php
    $jsonData = json_encode($data ?? []);
    $encodedData = urlencode($jsonData);
@endphp

<iframe
    src="{{ route('gallery.preview', ['user' => $user ?? '', 'data' => $encodedData]) }}"
    style="width:100%; height:1325px;">
</iframe>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const iframe = document.getElementById('preview-iframe');

        Livewire.on('fieldUpdated', (field, value) => {
            const url = new URL(iframe.src);
            url.searchParams.set(field, value);
            iframe.src = url.toString();
        });
    });
</script>
