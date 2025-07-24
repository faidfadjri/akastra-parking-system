$(document).ready(function () {
    let dragging = false;
    let dragClone = null;
    let offset = { x: 0, y: 0 };
    let originalElement = null;
    let didMove = false; // ✅ tambahan

    const DRAGGABLE_SELECTOR = '.seat-vertical, .seat-horizontal, .seat-vertical-short, .seat-horizontal-wide, .seat-vertical-wide, .seat-horizontal-oven';

    // MOUSE START
    $(document).on('mousedown', DRAGGABLE_SELECTOR, function (e) {
        e.preventDefault();
        e.stopPropagation();

        originalElement = $(this);

        const rect = originalElement[0].getBoundingClientRect();
        offset.x = e.clientX - rect.left;
        offset.y = e.clientY - rect.top;

        dragClone = originalElement.clone();
        dragClone.css({
            position: 'fixed',
            left: `${e.clientX - offset.x}px`,
            top: `${e.clientY - offset.y}px`,
            width: rect.width,
            height: rect.height,
            zIndex: 1000,
            pointerEvents: 'none',
            opacity: 0.8
        }).appendTo('body');

        dragging = true;
        didMove = false; // ✅ reset per drag
        $('body').addClass('noselect');
    });

    $(document).on('mousemove', function (e) {
        if (dragging && dragClone) {
            didMove = true; // ✅ flag jika ada gerakan
            dragClone.css({
                left: `${e.clientX - offset.x}px`,
                top: `${e.clientY - offset.y}px`,
            });
        }
    });

    $(document).on('mouseup', function (e) {
        if (dragging && dragClone) {
            finishDrop(e.clientX, e.clientY);
        }
    });

    // CLICK CANCELLATION
    $(document).on('click', DRAGGABLE_SELECTOR, function (e) {
        if (didMove) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }
    });

    // TOUCH START
    document.querySelectorAll(DRAGGABLE_SELECTOR).forEach(el => {
        el.addEventListener('touchstart', function (e) {
            e.preventDefault();
            e.stopPropagation();

            originalElement = $(this);
            const touch = e.touches[0];
            const rect = originalElement[0].getBoundingClientRect();
            offset.x = touch.clientX - rect.left;
            offset.y = touch.clientY - rect.top;

            dragClone = originalElement.clone();
            dragClone.css({
                position: 'fixed',
                left: `${touch.clientX - offset.x}px`,
                top: `${touch.clientY - offset.y}px`,
                width: rect.width,
                height: rect.height,
                zIndex: 1000,
                pointerEvents: 'none',
                opacity: 0.8
            }).appendTo('body');

            dragging = true;
            didMove = false; // ✅ reset per drag
            document.body.style.overflow = 'hidden';
        }, { passive: false });
    });

    // TOUCH MOVE
    document.addEventListener('touchmove', function (e) {
        if (dragging && dragClone) {
            didMove = true; // ✅ flag jika ada gerakan
            const touch = e.touches[0];
            dragClone.css({
                left: `${touch.clientX - offset.x}px`,
                top: `${touch.clientY - offset.y}px`,
            });
            e.preventDefault();
        }
    }, { passive: false });

    // TOUCH END / CANCEL
    document.addEventListener('touchend', function (e) {
        if (dragging && dragClone) {
            const touch = e.changedTouches[0];
            finishDrop(touch.clientX, touch.clientY);
        }
        document.body.style.overflow = '';
    }, { passive: false });

    document.addEventListener('touchcancel', function (e) {
        if (dragging && dragClone) {
            const touch = e.changedTouches[0];
            finishDrop(touch.clientX, touch.clientY);
        }
        document.body.style.overflow = '';
    }, { passive: false });

    // FINISH DROP
    function finishDrop(x, y) {
        const dropTarget = document.elementFromPoint(x, y);
        if (dropTarget && $(dropTarget).is(DRAGGABLE_SELECTOR)) {
            const $target = $(dropTarget);
            const targetId = $target.attr('id');
            const prevId = originalElement.attr('id');

            if ($target.html().trim() === '') {
                $.ajax({
                    type: "POST",
                    url: "/parkir/update_posisi",
                    data: {
                        grup: originalElement.attr('grup'),
                        posisi: originalElement.attr('position'),
                        newGrup: $target.attr('grup'),
                        newPosisi: $target.attr('position'),
                    },
                    dataType: "json",
                    success: function (response) {
                        $(`#${prevId}`).html('');
                        $(`#${targetId}`).html(`${response.model_code} | ${response.license_plate}<br>${response.category}`);
                    },
                    error: function () {
                        // location.reload();
                    }
                });
            } else {
                // alert('Data sudah terisi');
            }
        }

        dragClone.remove();
        dragClone = null;
        dragging = false;
        didMove = false; // ✅ reset di akhir
        $('body').removeClass('noselect');
    }

    // Style noselect
    $('<style>.noselect { user-select: none; }</style>').appendTo('head');
});
