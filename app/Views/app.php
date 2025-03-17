<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PARKIR | <?= $lokasi; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

   <link rel="shortcut icon" 
href="https://akastra.id/assets/images/icon/logoapp.png" 
type="image/x-icon">

    <!-- Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!-- Interact.JS -->
    <script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>
    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!-- SweetAlert -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Parking Styling -->
    <?php if ($lokasi == 'DEPAN') : ?>
        <link rel="stylesheet" href="/css/parkir/depan/style.css">
    <?php elseif ($lokasi == 'STALL_GR') : ?>
        <link rel="stylesheet" href="/css/parkir/stall_gr/style.css">
    <?php elseif ($lokasi == 'STALL_BP') : ?>
        <link rel="stylesheet" href="/css/parkir/stall_bp/style.css">
    <?php else : ?>
        <link rel="stylesheet" href="/css/parkir/akm/style.css">
    <?php endif; ?>


</head>

<body>

    <!-- Render Section Here -->
    <?= $this->renderSection('content'); ?>

    <?php if (session()->get('user')['role'] == 'editor') : ?>
        <?= $this->include('items/modal'); ?>
    <?php endif; ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <script src="/js/index.js"></script>
    <script src="/js/drag.js"></script>
</body>

</html>
