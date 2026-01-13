<?php
include "koneksi.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Czennies</title>
    <link rel="icon" href="img/logo.jpg">
    <link 
        rel="stylesheet" 
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
    />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
     rel="stylesheet" 
     integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" 
     crossorigin="anonymous"
    />
</head>
<body class="bg-light text-dark">
    <nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">DREAM AREA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 text-dark">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#article">Article</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#gallery">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#schedule">Schedule</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#profile">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php" target="_blank">Login</a>
                    </li>
                </ul>
                <div class="d-flex ms-3 align-items-center">
                    <button id="darkBtn" class="btn btn-dark btn-sm me-2 p-1">
                        <i class="bi bi-moon-fill"></i>
                    </button>
                    <button id="lightBtn" class="btn btn-light btn-sm border p-1">
                        <i class="bi bi-sun-fill"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <section id="hero" class="text-center p-5 bg-success-subtle text-sm-start">
        <div class="container">
            <div class="d-sm-flex flex-sm-row-reverse align-items-center">
                <img src="img/dream.jpg" alt="Dream" class="img-fluid" width="300">
                <div>
                    <h1 class="fw-bold display-4">Dream Together, Grow Together, Forever</h1>
                    <h4 class="lead display-6">All about NCT Dream</h4>
                    <h6>
                        <span id="tanggal"></span>
                        <span id="jam"></span>
                    </h6>
                    <script type="text/javascript">
                        window.setTimeout("tampilWaktu()", 1000);
                        function tampilWaktu(){
                            var waktu = new Date();
                            var bulan = waktu.getMonth() + 1;
                            setTimeout("tampilWaktu()", 1000);
                            document.getElementById("tanggal").innerHTML = waktu.getDate() + "/" + bulan + "/" + waktu.getFullYear();
                            document.getElementById("jam").innerHTML = waktu.getHours() + ":" + waktu.getMinutes() + ":" + waktu.getSeconds();
                        }
                    </script>
                </div>
            </div>
        </div>
    </section>

    <!-- article begin -->
<section id="article" class="text-center p-5">
  <div class="container">
    <h1 class="fw-bold display-4 pb-3">article</h1>
    <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center">
      <?php
      $sql = "SELECT * FROM article ORDER BY tanggal DESC";
      $hasil = $conn->query($sql); 

      while($row = $hasil->fetch_assoc()){
        // Buat excerpt (ringkasan) dari isi artikel - maksimal 200 karakter
        $excerpt = substr($row["isi"], 0, 200);
        // Jika artikel lebih panjang dari 200 karakter, tambahkan "..."
        if (strlen($row["isi"]) > 200) {
            $excerpt .= "...";
        }
      ?>
        <div class="col">
          <div class="card h-100 shadow-sm hover-card">
            <img src="img/<?= $row["gambar"]?>" class="card-img-top" alt="<?= $row["judul"]?>" style="height: 200px; object-fit: cover;" />
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?= $row["judul"]?></h5>
              <p class="card-text text-muted small text-start flex-grow-1">
                <?= $excerpt?>
              </p>
              <a href="article_detail.php?id=<?= $row["id"]?>" class="btn btn-primary btn-sm mt-2">
                <i class="bi bi-book-half"></i> Read More
              </a>
            </div>
            <div class="card-footer ">
              <small class="text-body-secondary">
                <i class="bi bi-calendar3"></i> <?= date('d M Y', strtotime($row["tanggal"]))?>
              </small>
            </div>
          </div>
        </div>
        <?php
      }
      ?> 
    </div>
  </div>
</section>
<!-- article end -->

    <section id="gallery" class="text-center p-5 bg-success-subtle">
        <div class="container">
            <h1 class="fw-bold display-4 pb-3">gallery</h1>
            <div id="carouselExample" class="carousel slide">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="img/mark.jpg" class="d-block w-100 " alt="Mark Lee">
                    </div>
                    <div class="carousel-item">
                        <img src="img/rj.jpg" class="d-block w-100" alt="Huang Renjun">
                    </div>
                    <div class="carousel-item">
                        <img src="img/jeno.jpg" class="d-block w-100" alt="Lee Jeno">
                    </div>
                    <div class="carousel-item">
                        <img src="img/echan.jpg" class="d-block w-100" alt="Lee Haechan">
                    </div>
                    <div class="carousel-item">
                        <img src="img/nana.jpg" class="d-block w-100" alt="Na Jaemin">
                    </div>
                    <div class="carousel-item">
                        <img src="img/cl.jpg" class="d-block w-100" alt="Zhong Chenle">
                    </div>
                    <div class="carousel-item">
                        <img src="img/jie.jpg" class="d-block w-100" alt="Park Jisung">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </section>

    <section id="schedule" class="text-center p-5">
        <div class="container">
            <h1 class="fw-bold display-4 pb-4">schedule</h1>
            <div class="row row-cols-1 row-cols-md-4 g-4 justify-content-center">
            <div class="col">
                <div class="card h-100 border-0">
                <div class="p-2 text-dark fw-bold bg-primary-subtle">Senin</div>
                    <div class="card-body bg-white text-dark">
                        <p class="mb-0"><b>09.30–12.00 </b><br>Logika Informatika<br>Ruang H.5.12</p>
                        <p class="mb-0"><b>14.10–15.50 </b><br>Basis Data<br>Ruang H.5.10</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 border-0">
                <div class="p-2 text-dark fw-bold bg-success-subtle">Selasa</div>
                <div class="card-body bg-white text-dark">
                        <p class="mb-0"><b>12.30–15.00 </b><br>Rekayasa Perangkat Lunak<br>Ruang H.5.10</p>
                        <p class="mb-0"><b>15.30–18.00 </b><br>Sistem Operasi<br>Ruang H.3.2</p>
                </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 border-0">
                <div class="p-2 text-dark fw-bold bg-primary-subtle">Rabu</div>
                <div class="card-body bg-white text-dark">
                        <p class="mb-0"><b>09.30–12.00 </b><br>Kriptografi<br>Ruang H.5.13</p>
                        <p class="mb-0"><b>12.30–14.10 </b><br>Pemrograman Berbasis Web<br>Ruang D.2.J</p>
                </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 border-0">
                <div class="p-2 text-dark fw-bold bg-success-subtle">Kamis</div>
                <div class="card-body bg-white text-dark">
                    <p class="mb-0"><b>14.10–15.50 </b><br>Basis Data<br>Ruang D.2.K</p>
                </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 border-0">
                <div class="p-2 text-dark fw-bold bg-primary-subtle">Jumat</div>
                <div class="card-body bg-white text-dark">
                    <p class="mb-0"><b>09.30–12.00 </b><br>Probabilitas Dan Statistik<br>Ruang H.3.2</p>
                    <p class="mb-0"><b>12.30–15.00 </b><br>Penambangan Data<br>Ruang H.4.3</p>
                </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 border-0">
                <div class="p-2 text-dark fw-bold bg-success-subtle">Sabtu</div>
                <div class="card-body bg-white text-dark">
                    <p class="mb-0"><b>Tidak ada jadwal</b></p>
                </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 border-0">
                <div class="p-2 text-dark fw-bold bg-primary-subtle">Minggu</div>
                <div class="card-body bg-white text-dark">
                    <p class="mb-0"><b>Tidak ada jadwal</b></p>
                </div>
                </div>
            </div>
    </section>
   
<section id="profile" class="p-5 bg-success-subtle">
    <div class="container">
        <h1 class="fw-bold display-4 text-center pb-4">Profile Team</h1>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 justify-content-center">
            
            <!-- Card Member 1 -->
            <div class="col">
                <div class="card h-100 shadow-sm border-0 rounded-4">
                    <div class="card-body text-center">
                        <img 
                            src="img/naia.jpg" 
                            alt="Member 1" 
                            class="rounded-circle img-fluid mb-3 shadow-sm" 
                            width="150"
                            height="150"
                            style="object-fit: cover;"
                        >
                        <h5 class="fw-bold text-dark mb-3">Naia Syafina H</h5>
                        <div class="text-start">
                            <p class="mb-2 small">
                                <i class="bi bi-person-badge text-primary"></i> 
                                <strong>NIM:</strong> A11.2024.15554
                            </p>
                            <p class="mb-2 small">
                                <i class="bi bi-book text-success"></i> 
                                <strong>Prodi:</strong> Teknik Informatika
                            </p>
                            <p class="mb-2 small">
                                <i class="bi bi-envelope text-danger"></i> 
                                <strong>Email:</strong><br>
                                <a href="mailto:syafinanaia@gmail.com" class="text-decoration-none text-muted">
                                    syafinanaia@gmail.com
                                </a>
                            </p>
                            <p class="mb-0 small">
                                <i class="bi bi-telephone text-warning"></i> 
                                <strong>Phone:</strong><br>
                                <a href="https://wa.me/6282241549915" class="text-decoration-none text-muted" target="_blank">
                                    +62 822-4154-9915
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Member 2 -->
            <div class="col">
                <div class="card h-100 shadow-sm border-0 rounded-4">
                    <div class="card-body text-center">
                        <img 
                            src="img/nad.jpg" 
                            alt="Member 2" 
                            class="rounded-circle img-fluid mb-3 shadow-sm" 
                            width="150"
                            height="150"
                            style="object-fit: cover;"
                        >
                        <h5 class="fw-bold text-dark mb-3">Nadjwa Salsabila W</h5>
                        <div class="text-start">
                            <p class="mb-2 small">
                                <i class="bi bi-person-badge text-primary"></i> 
                                <strong>NIM:</strong> A11.2024.15670
                            </p>
                            <p class="mb-2 small">
                                <i class="bi bi-book text-success"></i> 
                                <strong>Prodi:</strong> Teknik Informatika
                            </p>
                            <p class="mb-2 small">
                                <i class="bi bi-envelope text-danger"></i> 
                                <strong>Email:</strong><br>
                                <a href="mailto:nadjwasalsabila16@gmail.com" class="text-decoration-none text-muted">
                                    nadjwasalsabila16@gmail.com
                                </a>
                            </p>
                            <p class="mb-0 small">
                                <i class="bi bi-telephone text-warning"></i> 
                                <strong>Phone:</strong><br>
                                <a href="https://wa.me/6281391467460" class="text-decoration-none text-muted" target="_blank">
                                    +62 813-9146-7460
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Member 3 -->
            <div class="col">
                <div class="card h-100 shadow-sm border-0 rounded-4">
                    <div class="card-body text-center">
                        <img 
                            src="img/moty.jpeg" 
                            alt="Member 3" 
                            class="rounded-circle img-fluid mb-3 shadow-sm" 
                            width="150"
                            height="150"
                            style="object-fit: cover;"
                        >
                        <h5 class="fw-bold text-dark mb-3">Timothy Giovanny</h5>
                        <div class="text-start">
                            <p class="mb-2 small">
                                <i class="bi bi-person-badge text-primary"></i> 
                                <strong>NIM:</strong> A11.2024.15646
                            </p>
                            <p class="mb-2 small">
                                <i class="bi bi-book text-success"></i> 
                                <strong>Prodi:</strong> Teknik Informatika
                            </p>
                            <p class="mb-2 small">
                                <i class="bi bi-envelope text-danger"></i> 
                                <strong>Email:</strong><br>
                                <a href="mailto:giotimothy33@gmail.com" class="text-decoration-none text-muted">
                                    giotimothy33@gmail.com
                                </a>
                            </p>
                            <p class="mb-0 small">
                                <i class="bi bi-telephone text-warning"></i> 
                                <strong>Phone:</strong><br>
                                <a href="https://wa.me/6285753365112" class="text-decoration-none text-muted" target="_blank">
                                    +62 857-5336-5112
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Member 4 -->
            <div class="col">
                <div class="card h-100 shadow-sm border-0 rounded-4">
                    <div class="card-body text-center">
                        <img 
                            src="img/el.jpeg" 
                            alt="Member 4" 
                            class="rounded-circle img-fluid mb-3 shadow-sm" 
                            width="150"
                            height="150"
                            style="object-fit: cover;"
                        >
                        <h5 class="fw-bold text-dark mb-3">Raffael Ezra N</h5>
                        <div class="text-start">
                            <p class="mb-2 small">
                                <i class="bi bi-person-badge text-primary"></i> 
                                <strong>NIM:</strong> A11.2024.15667
                            </p>
                            <p class="mb-2 small">
                                <i class="bi bi-book text-success"></i> 
                                <strong>Prodi:</strong> Teknik Informatika
                            </p>
                            <p class="mb-2 small">
                                <i class="bi bi-envelope text-danger"></i> 
                                <strong>Email:</strong><br>
                                <a href="mailto:nugrohoraffael@gmail.com" class="text-decoration-none text-muted">
                                    nugrohoraffael@gmail.com
                                </a>
                            </p>
                            <p class="mb-0 small">
                                <i class="bi bi-telephone text-warning"></i> 
                                <strong>Phone:</strong><br>
                                <a href="https://wa.me/6287832000076" class="text-decoration-none text-muted" target="_blank">
                                    +62 878-3200-0076
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

    <footer class="text-center p-5">
        <div>
            <a href="https://www.instagram.com/udinusofficial"><i class="bi bi-instagram h2 p-2 text-dark"></i></a>
            <a href="https://twitter.com/udinusofficial"><i class="bi bi-twitter-x h2 p-2 text-dark"></i></a>
            <a href="https://wa.me/+6282241549915"><i class="bi bi-whatsapp h2 p-2 text-dark"></i></a>
        </div>
        <div>
            UGduapunya &copy; 2026
        </div>
    </footer>

    <script>
        document.getElementById("darkBtn").onclick = function () {
            document.body.classList.remove("bg-light", "text-dark");
            document.body.classList.add("bg-dark", "text-white");

            const cards = document.getElementsByClassName("card");
            for (let i = 0; i < cards.length; i++) {
                cards[i].classList.add("bg-secondary", "text-white");
                cards[i].classList.remove("bg-light");
            }

            const icons = document.getElementsByTagName("i");
            for (let i = 0; i < icons.length; i++) {
                icons[i].classList.remove("text-dark");
                icons[i].classList.add("text-light");
            }

            const nav = document.getElementsByTagName("nav")[0];
            nav.classList.add("bg-dark", "navbar-dark");
            nav.classList.remove("bg-body-tertiary");

            const hero = document.getElementById("hero");
            hero.classList.remove("bg-success-subtle");
            hero.classList.add("bg-secondary");

            const gallery = document.getElementById("gallery");
            gallery.classList.remove("bg-success-subtle");
            gallery.classList.add("bg-secondary");

            const schedule = document.getElementById("schedule");
            if (schedule) {
                schedule.classList.remove("bg-light");
                schedule.classList.add("bg-dark");

                const scheduleCards = schedule.getElementsByClassName("card");
                for (let i = 0; i < scheduleCards.length; i++) {
                    scheduleCards[i].classList.remove("bg-light", "border", "border-dark");
                    scheduleCards[i].classList.add("bg-dark", "border", "border-light");
                }

                const jadwalCards = schedule.getElementsByClassName("card-body");
                for (let i = 0; i < jadwalCards.length; i++) {
                    jadwalCards[i].classList.remove("bg-white", "text-dark");
                    jadwalCards[i].classList.add("bg-secondary", "text-white");
                }
            }

            const profile = document.getElementById("profile");
            if (profile) {
                profile.classList.remove("bg-success-subtle");
                profile.classList.add("bg-secondary");

                const fotoCols = profile.getElementsByClassName("col-md-4");
                for (let i = 0; i < fotoCols.length; i++) {
                    fotoCols[i].classList.remove("bg-white", "shadow-sm");
                    fotoCols[i].classList.add("bg-secondary");
                    fotoCols[i].style.boxShadow = "none";
                }

                const profileCards = profile.getElementsByClassName("card");
                for (let i = 0; i < profileCards.length; i++) {
                profileCards[i].classList.remove("bg-white", "text-dark", "text-white");
                profileCards[i].classList.add("bg-secondary-subtle", "text-dark");
                }

                const links = profile.getElementsByTagName("a");
                for (let i = 0; i < links.length; i++) {
                    links[i].classList.remove("text-white");
                    links[i].classList.add("text-dark");
                }

                const headings = profile.getElementsByTagName("h2");
                for (let i = 0; i < headings.length; i++) {
                    headings[i].classList.remove("text-white");
                    headings[i].classList.add("text-dark");
                }
            }
        };

        document.getElementById("lightBtn").onclick = function () {
            document.body.classList.remove("bg-dark", "text-white");
            document.body.classList.add("bg-light", "text-dark");

            const cards = document.getElementsByClassName("card");
            for (let i = 0; i < cards.length; i++) {
                cards[i].classList.remove("bg-secondary", "text-white");
                cards[i].classList.add("bg-light", "text-dark");
            }

            const icons = document.getElementsByTagName("i");
            for (let i = 0; i < icons.length; i++) {
                icons[i].classList.remove("text-light");
                icons[i].classList.add("text-dark");
            }

            const nav = document.getElementsByTagName("nav")[0];
            nav.classList.remove("bg-dark", "navbar-dark");
            nav.classList.add("bg-body-tertiary");

            const hero = document.getElementById("hero");
            hero.classList.remove("bg-secondary");
            hero.classList.add("bg-success-subtle");

            const gallery = document.getElementById("gallery");
            gallery.classList.remove("bg-secondary");
            gallery.classList.add("bg-success-subtle");

            const schedule = document.getElementById("schedule");
            if (schedule) {
                schedule.classList.remove("bg-dark");
                schedule.classList.add("bg-light");

                const scheduleCards = schedule.getElementsByClassName("card");
                for (let i = 0; i < scheduleCards.length; i++) {
                    scheduleCards[i].classList.remove("bg-dark", "border", "border-light");
                    scheduleCards[i].classList.add("bg-light", "border", "border-dark");
                }

                const jadwalCards = schedule.getElementsByClassName("card-body");
                for (let i = 0; i < jadwalCards.length; i++) {
                    jadwalCards[i].classList.remove("bg-secondary", "text-white");
                    jadwalCards[i].classList.add("bg-white", "text-dark");
                }
            }

            const profile = document.getElementById("profile");
            if (profile) {
                profile.classList.remove("bg-secondary");
                profile.classList.add("bg-success-subtle");

                const fotoCols = profile.getElementsByClassName("col-md-4");
                for (let i = 0; i < fotoCols.length; i++) {
                    fotoCols[i].classList.remove("bg-secondary");
                    fotoCols[i].style.boxShadow = "none";
                }

                const profileCards = profile.getElementsByClassName("card");
                for (let i = 0; i < profileCards.length; i++) {
                    profileCards[i].classList.remove("bg-secondary-subtle", "text-dark");
                    profileCards[i].classList.add("bg-white", "text-dark");
                }

                const links = profile.getElementsByTagName("a");
                for (let i = 0; i < links.length; i++) {
                    links[i].classList.remove("text-dark");
                    links[i].classList.add("text-dark");
                }

                const headings = profile.getElementsByTagName("h2");
                for (let i = 0; i < headings.length; i++) {
                    headings[i].classList.remove("text-dark");
                    headings[i].classList.add("text-dark");
                }
            }
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" 
        crossorigin="anonymous">
    </script>
</body>
</html>