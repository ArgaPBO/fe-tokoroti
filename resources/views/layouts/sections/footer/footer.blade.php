@php
  $containerFooter = ($configData['contentLayout'] === 'compact') ? 'container-xxl' : 'container-fluid';
@endphp

<!-- Footer -->
<footer class="content-footer footer bg-footer-theme">
  <div class="{{ $containerFooter }}">

    <!-- Bagian Atas Footer (Info Utama) -->
    {{-- Layout ini menggunakan flexbox untuk membagi 2 kolom seperti di gambar --}}
    <div class="d-flex flex-column flex-md-row justify-content-between py-4">

      <!-- Kolom 1: Judul Brand -->
      <div class="col-12 col-md-4 mb-4 mb-md-0">
        {{-- Menggunakan class fw-bold untuk teks tebal seperti di gambar --}}
        <h2 class="fw-bold mb-0" style="font-size: 2.5rem; line-height: 1.2;">Chiffon Krumpul Wonosari</h2>
      </div>

      <!-- Kolom 2: Deskripsi & Kontak -->
      <div class="col-12 col-md-7">
        <p>
          Chiffon Krumpul Wonosari adalah salah satu produk unggulan dari Roti Wonosari,
          sebuah toko roti legendaris yang berada di Wonosari, Gunungkidul.
          Produk ini berupa bolu chiffon dengan tekstur lembut dan ringan.
          Rasa bolu chiffon sangat khas, cita rasa yang sesuai dengan selera lokal.
        </p>

        {{-- Kontak info --}}
        <div class="d-flex flex-wrap mt-4">
          <div class="me-sm-5 me-3 mb-3">
            <div class="d-flex align-items-center">
              <i class='bx bxs-phone-call me-2 fs-4'></i>
              <div>
                <strong class="d-block">Hallo Chief - Layanan Pelanggan</strong>
                <span>+6281226332606</span>
              </div>
            </div>
          </div>
          <div>
            <div class="d-flex align-items-start">
              <i class='bx bxs-map me-2 fs-4'></i>
              <div>
                <strong class="d-block">Lokasi Kantor/Store</strong>
                <span class="d-block" style="max-width: 350px;">Jl. Tentara Pelajar No.96, Kepek I, Kepek, Kec.
                  Wonosari,
                  Kabupaten Gunungkidul, Daerah Istimewa Yogyakarta 55813</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Garis Pemisah (Seperti di Gambar) -->
    <div class="border-top"></div>

    <!-- Bagian Bawah Footer (Copyright & Links) -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center py-3">

      {{-- Copyright (kiri) --}}
      <span class="text-muted mb-2 mb-md-0">2025 Chiffon Krumpul Wonosari | v1.0.0</span>

      {{-- Links (kanan) --}}
      <div>
        <a href="#" class="text-dark me-3">Privacy Policy</a>
        <a href="#" class="text-dark">Terms of Service</a>
      </div>
    </div>

  </div>
</footer>
<!--/ Footer -->
