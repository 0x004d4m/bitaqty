@php
    $qrCode = QrCode::size(100)->generate(data_get($entry, $column['name']));
@endphp

{!! $qrCode !!}
