<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Auth | Login</title>
    <link rel="stylesheet" href="/css/main/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>

<body>
    <section style="min-height: 100vh;" class="d-flex align-items-center basic-color">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8 col-sm-12">
                    <div class="card shadow p-5">
                        <form action="authentication" class="text-lato" method="authentication" id="login-form">
                            <div class="lottie-wrap d-flex align-items-center justify-content-center">
                                <lottie-player src="https://assets2.lottiefiles.com/packages/lf20_a3emlnqk.json" background="transparent" speed="1" style="width: 250px; height: 250px;" loop autoplay>
                                </lottie-player>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Username</label>
                                <input type="text" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" autocomplete="off" required>
                                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" id="exampleInputPassword1" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2 fst-bold fw-bold">
                                LOGIN
                                <span class="material-icons">
                                    login
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script>
        $(document).ready(function() {
            $('#login-form').submit(function(e) {
                e.preventDefault();

                var form = $(this);
                var actionUrl = form.attr('action');

                $.ajax({
                    type: "POST",
                    url: actionUrl,
                    data: form.serialize(),
                    dataType: "json",
                    success: function(response) {
                        if (response.code == 200) {
                            Swal.fire(
                                'Good job!',
                                'Login berhasil!',
                                'success'
                            ).then(function() {
                                location.href = "/";
                            })
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!',
                            })
                        }
                    }
                });
            });
        });
    </script>

    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body>

</html>