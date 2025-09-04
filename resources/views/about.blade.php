@extends('layouts.app')

@section('styles')
<style>
    .member-slide {
        display: none;
    }
    .member-slide.active {
        display: block;
        animation: fadeIn 0.5s;
    }
    .modal-body {
        min-height: 400px; /* Adjusted for more content */
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="text-center mb-5" data-aos="fade-down">
        <h1 class="fw-bold">Tentang Kami</h1>
        <p class="lead">Cerita di balik kelezatan Snagih.</p>
    </div>
    <div class="row align-items-center mb-5">
        <div class="col-md-6" data-aos="fade-right">
            <img src="{{ asset('images/ft1.JPG') }}" class="img-fluid rounded shadow" alt="Tentang Snagih">
        </div>
        <div class="col-md-6" data-aos="fade-left">
            <h3 class="fw-bold">Misi Kami</h3>
            <p>Kami percaya bahwa makanan ringan bukan hanya pengganjal perut, tapi juga teman di setiap momen. Misi kami adalah menghadirkan camilan berkualitas dengan rasa otentik yang selalu bikin nagih, kapan pun dan di mana pun.</p>
            <p>Dimulai dari dapur rumahan sederhana, Snagih lahir dari kecintaan pada resep-resep tradisional yang diolah secara modern untuk menciptakan rasa yang unik dan tak terlupakan.</p>
            <div class="mt-4">
                <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#membersModal">
                    Kenali Tim Kami
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Members Modal -->
<div class="modal fade" id="membersModal" tabindex="-1" aria-labelledby="membersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="membersModalLabel">Tim Hebat di Balik Snagih</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <!-- Member 1 -->
                <div class="member-slide active" id="member-1">
                    <div class="card p-4 h-100 bg-light shadow-sm">
                        <img src="{{ asset('images/azril.jpeg') }}" class="rounded-circle mb-3 mx-auto" alt="Azril" style="width: 220px; height: 220px; object-fit: cover;">
                        <h4 class="fw-bold">Azril</h4>
                        <p class="text-muted">CEO Snagih</p>
                        <p class="px-3">Visioner di balik Snagih, memastikan setiap produk memenuhi standar kualitas dan rasa tertinggi.</p>
                    </div>
                </div>
                <!-- Member 2 -->
                <div class="member-slide" id="member-2">
                    <div class="card p-4 h-100 bg-light shadow-sm">
                        <img src="{{ asset('images/dika.jpeg') }}" class="rounded-circle mb-3 mx-auto" alt="Dika" style="width: 220px; height: 220px; object-fit: cover;">
                        <h4 class="fw-bold">Dika</h4>
                        <p class="text-muted">Desain Logo</p>
                        <p class="px-3">Kreator identitas visual Snagih, mengubah ide menjadi logo yang ikonik dan menarik.</p>
                    </div>
                </div>
                <!-- Member 3 -->
                <div class="member-slide" id="member-3">
                    <div class="card p-4 h-100 bg-light shadow-sm">
                        <img src="{{ asset('images/adam.jpeg') }}" class="rounded-circle mb-3 mx-auto" alt="Adam" style="width: 220px; height: 220px; object-fit: cover;">
                        <h4 class="fw-bold">Adam</h4>
                        <p class="text-muted">Pengembang Website</p>
                        <p class="px-3">Membangun pengalaman digital yang mulus bagi pelanggan untuk menjelajahi dan membeli produk Snagih.</p>
                    </div>
                </div>
                <!-- Member 4 -->
                <div class="member-slide" id="member-4">
                    <div class="card p-4 h-100 bg-light shadow-sm">
                        <img src="{{ asset('images/ainin.jpeg') }}" class="rounded-circle mb-3 mx-auto" alt="Ainin" style="width: 220px; height: 220px; object-fit: cover;">
                        <h4 class="fw-bold">Ainin</h4>
                        <p class="text-muted">Manajemen Media Sosial</p>
                        <p class="px-3">Menghubungkan Snagih dengan komunitas online, menyebarkan cerita dan kelezatan produk kami.</p>
                    </div>
                </div>
                <!-- Member 5 -->
                <div class="member-slide" id="member-5">
                    <div class="card p-4 h-100 bg-light shadow-sm">
                        <img src="{{ asset('images/riska.jpeg') }}" class="rounded-circle mb-3 mx-auto" alt="Riska" style="width: 220px; height: 220px; object-fit: cover;">
                        <h4 class="fw-bold">Riska</h4>
                        <p class="text-muted">Pembuat Proposal</p>
                        <p class="px-3">Merancang strategi dan proposal bisnis untuk pertumbuhan dan kemitraan Snagih di masa depan.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-outline-primary" id="prevMember">Sebelumnya</button>
                <button type="button" class="btn btn-primary" id="nextMember">Selanjutnya</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const membersModal = document.getElementById('membersModal');
    if (membersModal) {
        const prevBtn = document.getElementById('prevMember');
        const nextBtn = document.getElementById('nextMember');
        const memberSlides = document.querySelectorAll('.member-slide');
        const totalMembers = memberSlides.length;
        let currentMemberIndex = 0;

        function updateButtonVisibility(index) {
            prevBtn.style.visibility = (index === 0) ? 'hidden' : 'visible';
            nextBtn.style.visibility = (index === totalMembers - 1) ? 'hidden' : 'visible';
        }

        function showMember(index) {
            memberSlides.forEach((slide, i) => {
                slide.classList.remove('active');
                if (i === index) {
                    slide.classList.add('active');
                }
            });
            updateButtonVisibility(index);
        }

        prevBtn.addEventListener('click', function () {
            if (currentMemberIndex > 0) {
                currentMemberIndex--;
                showMember(currentMemberIndex);
            }
        });

        nextBtn.addEventListener('click', function () {
            if (currentMemberIndex < totalMembers - 1) {
                currentMemberIndex++;
                showMember(currentMemberIndex);
            }
        });

        // Reset to first member when modal is opened
        membersModal.addEventListener('show.bs.modal', function () {
            currentMemberIndex = 0;
            showMember(currentMemberIndex);
        });
    }
});
</script>
@endpush
