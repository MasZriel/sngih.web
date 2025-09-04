@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card p-5">
                <h1 class="fw-bold text-success">Pesanan Berhasil!</h1>
                <p class="lead">Terima kasih telah berbelanja di Snagih.</p>
                <p>Pesanan Anda sedang kami proses dan akan segera kami kirimkan ke alamat Anda.</p>
                <hr>
                <p>Ingin memesan lagi?</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary mt-2">Lanjut Belanja</a>
            </div>
        </div>
    </div>
</div>
@endsection
