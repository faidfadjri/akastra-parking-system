$(document).ready(function () {
    let dragging = null;
    let offset = { x: 0, y: 0 };
    let originalParent = null;

    const DRAGGABLE_SELECTOR = '.seat-vertical, .seat-horizontal, .seat-vertical-short, .seat-horizontal-wide, .seat-vertical-wide, .seat-horizontal-oven';

    // MOUSE DRAG
    $(document).on('mousedown', DRAGGABLE_SELECTOR, function (e) {
        e.preventDefault();
        dragging = $(this);
        originalParent = dragging.parent();

        const rect = dragging[0].getBoundingClientRect();
        offset.x = e.clientX - rect.left;
        offset.y = e.clientY - rect.top;

        dragging.css({
            position: 'fixed',
            zIndex: 1000,
            pointerEvents: 'none',
        });

        $('body').addClass('noselect');
    });

    $(document).on('mousemove', function (e) {
        if (dragging) {
            dragging.css({
                left: `${e.clientX - offset.x}px`,
                top: `${e.clientY - offset.y}px`,
            });
        }
    });

    $(document).on('mouseup', function (e) {
        if (dragging) {
            finishDrop(e.clientX, e.clientY);
        }
    });

    // TOUCH DRAG
    $(document).on('touchstart', DRAGGABLE_SELECTOR, function (e) {
        dragging = $(this);
        originalParent = dragging.parent();
        let touch = e.originalEvent.touches[0];

        const rect = dragging[0].getBoundingClientRect();
        offset.x = touch.clientX - rect.left;
        offset.y = touch.clientY - rect.top;

        dragging.css({
            position: 'fixed',
            zIndex: 1000,
            pointerEvents: 'none',
        });

        document.body.style.overflow = 'hidden';
    });

    $(document).on('touchmove', function (e) {
        if (dragging) {
            let touch = e.originalEvent.touches[0];
            dragging.css({
                left: `${touch.clientX - offset.x}px`,
                top: `${touch.clientY - offset.y}px`,
            });
            e.preventDefault();
        }
    });

    $(document).on('touchend touchcancel', function (e) {
        if (dragging) {
            let touch = e.originalEvent.changedTouches[0];
            finishDrop(touch.clientX, touch.clientY);
        }
        document.body.style.overflow = '';
    });

    // FINISH DROP LOGIC
    function finishDrop(x, y) {
        let dropTarget = document.elementFromPoint(x, y);
        if (dropTarget && $(dropTarget).is(DRAGGABLE_SELECTOR)) {
            let $target = $(dropTarget);
            let targetId = $target.attr('id');
            let prevId = dragging.attr('id');

            if ($target.html().trim() === '') {
                $.ajax({
                    type: "POST",
                    url: "/parkir/update_posisi",
                    data: {
                        grup: dragging.attr('grup'),
                        posisi: dragging.attr('position'),
                        newGrup: $target.attr('grup'),
                        newPosisi: $target.attr('position'),
                    },
                    dataType: "json",
                    success: function (response) {
                        $(`#${prevId}`).html('');
                        $(`#${targetId}`).html(`${response.model_code} | ${response.license_plate}<br>${response.category}`);
                    },
                    error: function () {
                        location.reload();
                    }
                });
            } else {
                alert('Data sudah terisi');
            }
        }

        dragging.css({
            position: 'relative',
            left: '',
            top: '',
            zIndex: '',
            pointerEvents: '',
        });
        dragging = null;
        $('body').removeClass('noselect');
    }

    // Prevent user text selection
    $('<style>.noselect { user-select: none; }</style>').appendTo('head');
});
