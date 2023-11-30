@php
    $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(100)->generate(data_get($entry, $column['name']));
@endphp
<img src="data:image/png;base64, {!! base64_encode($qrCode) !!}">
