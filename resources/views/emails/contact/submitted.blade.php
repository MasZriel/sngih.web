@component('mail::message')
# Pesan Baru dari Formulir Kontak Snagih

Anda telah menerima pesan baru dari situs web Anda.

**Dari:** {{ $name }} ({{ $email }})

**Pesan:**

{{ $messageBody }}

Terima kasih,<br>
{{ config('app.name') }}
@endcomponent