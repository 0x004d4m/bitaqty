@php
    $qrCode = QrCode::size(100)->generate($column['value']);
@endphp

{!! $qrCode !!}
