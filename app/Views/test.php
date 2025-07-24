<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Drag Test dengan Dropzone</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden;
            font-family: Arial, sans-serif;
        }

        .seat-vertical {
            width: 100px;
            height: 100px;
            background: #4caf50;
            color: white;
            text-align: center;
            line-height: 100px;
            position: absolute;
            cursor: grab;
        }

        .dropzone {
            position: absolute;
            bottom: 50px;
            right: 50px;
            width: 200px;
            height: 200px;
            background: #ddd;
            border: 2px dashed #999;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 18px;
        }

        .dropzone.active {
            background-color: #90ee90;
            border-color: #4caf50;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>

    <div class="seat-vertical" style="top: 100px; left: 100px;">Drag Me</div>

    <div class="dropzone">Drop Here</div>

    <script>
        $(document).ready(function() {
            let dragging = null;
            let offset = {
                x: 0,
                y: 0
            };

            const DRAGGABLE_SELECTOR = '.seat-vertical';
            const $dropzone = $('.dropzone');

            $(document).on('mousedown', DRAGGABLE_SELECTOR, function(e) {
                e.preventDefault();
                dragging = $(this);
                const pos = dragging.offset();
                offset.x = e.pageX - pos.left;
                offset.y = e.pageY - pos.top;
            });

            $(document).on('mousemove', function(e) {
                if (dragging) {
                    e.preventDefault();
                    const left = e.pageX - offset.x;
                    const top = e.pageY - offset.y;
                    dragging.css({
                        left: left + 'px',
                        top: top + 'px'
                    });

                    const dropzoneOffset = $dropzone.offset();
                    const dropzoneWidth = $dropzone.outerWidth();
                    const dropzoneHeight = $dropzone.outerHeight();

                    const draggingOffset = dragging.offset();
                    const draggingWidth = dragging.outerWidth();
                    const draggingHeight = dragging.outerHeight();

                    const inDropzone =
                        draggingOffset.left + draggingWidth / 2 > dropzoneOffset.left &&
                        draggingOffset.left + draggingWidth / 2 < dropzoneOffset.left + dropzoneWidth &&
                        draggingOffset.top + draggingHeight / 2 > dropzoneOffset.top &&
                        draggingOffset.top + draggingHeight / 2 < dropzoneOffset.top + dropzoneHeight;

                    $dropzone.toggleClass('active', inDropzone);
                }
            });

            $(document).on('mouseup', function(e) {
                if (dragging) {
                    const draggingOffset = dragging.offset();
                    const draggingWidth = dragging.outerWidth();
                    const draggingHeight = dragging.outerHeight();

                    const dropzoneOffset = $dropzone.offset();
                    const dropzoneWidth = $dropzone.outerWidth();
                    const dropzoneHeight = $dropzone.outerHeight();

                    const inDropzone =
                        draggingOffset.left + draggingWidth / 2 > dropzoneOffset.left &&
                        draggingOffset.left + draggingWidth / 2 < dropzoneOffset.left + dropzoneWidth &&
                        draggingOffset.top + draggingHeight / 2 > dropzoneOffset.top &&
                        draggingOffset.top + draggingHeight / 2 < dropzoneOffset.top + dropzoneHeight;

                    if (inDropzone) {
                        alert('Berhasil dijatuhkan di dropzone!');
                    }

                    $dropzone.removeClass('active');
                    dragging = null;
                }
            });
        });
    </script>

</body>

</html>