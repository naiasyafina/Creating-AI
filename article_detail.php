<?php
include "koneksi.php";

// Ambil ID artikel dari URL
$id = isset($_GET['id']) ? $_GET['id'] : 0;

// Query untuk mengambil detail artikel
$sql = "SELECT * FROM article WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Jika artikel tidak ditemukan, redirect ke index
if ($result->num_rows == 0) {
    header("Location: index.php#article");
    exit;
}

$article = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article["judul"]) ?> - DREAM AREA</title>
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
    <style>
        .article-image {
            width: 100%;
            max-height: 500px;
            object-fit: cover;
            border-radius: 10px;
        }
        .article-content {
            line-height: 1.5;
            font-size: 1.1rem;
            text-align: justify;
            white-space: pre-line;
        }
        .article-content p {
            text-indent: 2.5em;
            margin-bottom: 0.3rem;
            margin-top: 0;
        }
        .article-content p:first-child {
            margin-top: 0;
        }
        .back-btn:hover {
            transform: translateX(-5px);
            transition: transform 0.3s ease;
        }
    </style>
</head>
<body class="bg-light text-dark">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">DREAM AREA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 text-dark">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php#article">Article</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#gallery">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#schedule">Schedule</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#profile">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php" target="_blank">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Article Detail -->
    <section class="py-5">
        <div class="container">
            <!-- Back Button -->
            <a href="index.php#article" class="btn btn-outline-primary mb-4 back-btn">
                <i class="bi bi-arrow-left"></i> Back to Articles
            </a>

            <!-- Article Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <h1 class="display-4 fw-bold mb-3"><?= htmlspecialchars($article["judul"]) ?></h1>
                    <div class="text-muted mb-4">
                        <i class="bi bi-calendar3"></i> 
                        <span><?= date('d F Y, H:i', strtotime($article["tanggal"])) ?></span>
                        <span class="mx-2">•</span>
                        <i class="bi bi-person-circle"></i> 
                        <span><?= htmlspecialchars($article["username"]) ?></span>
                    </div>
                </div>
            </div>

            <!-- Article Image -->
            <?php if (!empty($article["gambar"]) && file_exists("img/" . $article["gambar"])): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <img src="img/<?= $article["gambar"] ?>" 
                         alt="<?= htmlspecialchars($article["judul"]) ?>" 
                         class="article-image shadow-lg">
                </div>
            </div>
            <?php endif; ?>

            <!-- Article Content -->
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4 p-md-5">
                            <div class="article-content">
                                <?php
                                // Split artikel berdasarkan baris kosong (double newline)
                                $paragraphs = preg_split('/\n\s*\n/', $article["isi"]);
                                foreach($paragraphs as $paragraph) {
                                    $paragraph = trim($paragraph);
                                    if (!empty($paragraph)) {
                                        echo '<p>' . nl2br(htmlspecialchars($paragraph)) . '</p>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- Share & Back Button -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="index.php#article" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Articles
                        </a>
                    </div>
                </div>
            </div>

            <!-- Related Articles -->
            <div class="row mt-5">
                <div class="col-12">
                    <h3 class="fw-bold mb-4">More Articles</h3>
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        <?php
                        // Get 3 latest articles excluding current
                        $sql_related = "SELECT * FROM article WHERE id != ? ORDER BY tanggal DESC LIMIT 3";
                        $stmt_related = $conn->prepare($sql_related);
                        $stmt_related->bind_param("i", $id);
                        $stmt_related->execute();
                        $result_related = $stmt_related->get_result();

                        while($related = $result_related->fetch_assoc()):
                            $excerpt = substr($related["isi"], 0, 100);
                            if (strlen($related["isi"]) > 100) {
                                $excerpt .= "...";
                            }
                        ?>
                        <div class="col">
                            <div class="card h-100 shadow-sm">
                                <img src="img/<?= $related["gambar"]?>" 
                                     class="card-img-top" 
                                     alt="<?= htmlspecialchars($related["judul"])?>" 
                                     style="height: 150px; object-fit: cover;">
                                <div class="card-body">
                                    <h6 class="card-title"><?= htmlspecialchars($related["judul"])?></h6>
                                    <p class="card-text small text-muted mb-2">
                                        <i class="bi bi-calendar3"></i> 
                                        <?= date('d M Y', strtotime($related["tanggal"])) ?>
                                    </p>
                                    <p class="card-text small text-muted"><?= $excerpt?></p>
                                    <a href="article_detail.php?id=<?= $related["id"]?>" class="btn btn-sm btn-outline-primary">
                                        Read More <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center p-5 bg-light mt-5">
        <div>
            <a href="https://www.instagram.com/udinusofficial"><i class="bi bi-instagram h2 p-2 text-dark"></i></a>
            <a href="https://twitter.com/udinusofficial"><i class="bi bi-twitter-x h2 p-2 text-dark"></i></a>
            <a href="https://wa.me/+6282241549915"><i class="bi bi-whatsapp h2 p-2 text-dark"></i></a>
        </div>
        <div>
            UGduapunya &copy; 2026
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" 
        crossorigin="anonymous">
    </script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
