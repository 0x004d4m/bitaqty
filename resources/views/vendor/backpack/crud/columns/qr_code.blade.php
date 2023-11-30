@php
    // Assuming you have a field or a method on your model to get the data for the QR code
    $qrCodeData = data_get($entry, $column['name']);
    $qrCodeImage = QrCode::format('png')->size(100)->generate($qrCodeData);
    $qrCodeSrc = 'data:image/png;base64,' . base64_encode($qrCodeImage);
@endphp

<img src="{{ $qrCodeSrc }}" alt="QR Code" />
