<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Auth | Login</title>

    <!-- Bootstrap & Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Poppins:wght@500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="/css/main/auth.css">

    <!-- Favicon -->
    <link rel="shortcut icon" href="/assets/logo.webp" type="image/x-icon">

    <!-- JS Plugins -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <section class="auth-wrapper d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7 col-sm-10">
                    <div class="card auth-card p-4 p-md-5">
                        <div class="d-flex flex-column align-items-center mb-4">
                            <lottie-player src="https://assets2.lottiefiles.com/packages/lf20_a3emlnqk.json"
                                background="transparent" speed="1"
                                style="width: 200px; height: 200px;" loop autoplay>
                            </lottie-player>
                            <h5 class="mt-2 mb-1 fw-semibold">E-Parking Akastra Toyota</h5>
                            <p class="text-muted small">Silakan login untuk melanjutkan</p>
                        </div>

                        <form action="authentication" method="POST" id="login-form">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username or Email</label>
                                <input type="text" name="email" id="username" class="form-control" placeholder="Enter your email" required>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Enter password" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                                <span>Login</span>
                                <span class="material-icons">login</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Scripts -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#login-form').submit(function(e) {
                e.preventDefault();

                const form = $(this);
                const actionUrl = form.attr('action');

                $.ajax({
                    type: "POST",
                    url: actionUrl,
                    data: form.serialize(),
                    dataType: "json",
                    success: function(response) {
                        if (response.code == 200) {
                            Swal.fire('Success', 'Login successful!', 'success').then(() => location.href = "/");
                        } else {
                            Swal.fire('Error', response.message || 'Login failed.', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Unable to process your request.', 'error');
                    }
                });
            });
        });
    </script>
</body>

</html>