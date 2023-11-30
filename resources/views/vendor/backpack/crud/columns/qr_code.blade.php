@php
    $qrCode = QrCode::format('png')->size(100)->generate(data_get($entry, $column['name']));
@endphp

{!! $qrCode !!}
