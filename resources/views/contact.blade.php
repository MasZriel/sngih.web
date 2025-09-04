@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5" data-aos="fade-down">
        <h1 class="fw-bold">Hubungi Kami</h1>
        <p class="lead">Punya pertanyaan atau masukan? Jangan ragu untuk menghubungi kami.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success col-md-8 mx-auto" data-aos="fade-up" data-aos-delay="100">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-8 mx-auto" data-aos="fade-up" data-aos-delay="200">
            <div class="card p-4">
                <div class="row">
                    <div class="col-md-6">
                        <form action="{{ route('contact.submit') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Pesan</label>
                                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Kirim Pesan</button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <div class="ps-md-4">
                            <h3 class="fw-bold">Informasi Kontak</h3>
                            <p><strong>Alamat:</strong><br>Banjarnegara</p>
                            <p><strong>Telepon:</strong><br>+62 896-9603-6257</p>
                            <p><strong>Email:</strong><br>{{ config('app.contact_email') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
