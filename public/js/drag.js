// Drag & Drop Configuration
const DRAGGABLE_SELECTOR = '.seat-vertical, .seat-horizontal, .seat-vertical-short, .seat-horizontal-wide, .seat-vertical-wide, .seat-horizontal-oven';

let draggedElement = null;
let draggedData = null;
let isDragging = false;
let dragStartTime = 0;
let currentSeat = null;

// Touch handling variables
let touchStartX = 0;
let touchStartY = 0;
let touchCurrentX = 0;
let touchCurrentY = 0;
let isTouchDragging = false;
let touchStartTime = 0;
let touchElement = null;
let dragPreview = null;
let dropTarget = null;
let initialTouchTarget = null;
let longPressTimer = null; // Add timer reference

const TOUCH_MOVE_THRESHOLD = 15; // Increased threshold
const TOUCH_TIME_THRESHOLD = 200; // Increased time threshold
const LONG_PRESS_DURATION = 600; // Increased duration for better UX

function initializeDragAndDrop() {
    const draggableElements = document.querySelectorAll(DRAGGABLE_SELECTOR);

    draggableElements.forEach(element => {
        element.draggable = true;

        // Desktop drag events
        element.addEventListener('dragstart', handleDragStart);
        element.addEventListener('dragend', handleDragEnd);
        element.addEventListener('dragover', handleDragOver);
        element.addEventListener('drop', handleDrop);
        element.addEventListener('dragenter', handleDragEnter);
        element.addEventListener('dragleave', handleDragLeave);

        // Mouse events
        element.addEventListener('mousedown', handleMouseDown);
        element.addEventListener('mouseup', handleMouseUp);
        element.addEventListener('click', handleClick);

        // Touch events for mobile - FIXED: More specific event handling
        element.addEventListener('touchstart', handleTouchStart, {
            passive: false
        });
        element.addEventListener('touchmove', handleTouchMove, {
            passive: false
        });
        element.addEventListener('touchend', handleTouchEnd, {
            passive: false
        });
        element.addEventListener('touchcancel', handleTouchCancel, {
            passive: false
        });
    });
}

// FIXED: Improved Touch Event Handlers
function handleTouchStart(e) {
    // Clear any existing timer
    if (longPressTimer) {
        clearTimeout(longPressTimer);
        longPressTimer = null;
    }

    const touch = e.touches[0];
    touchStartX = touch.clientX;
    touchStartY = touch.clientY;
    touchCurrentX = touch.clientX;
    touchCurrentY = touch.clientY;
    touchStartTime = Date.now();
    touchElement = this;
    initialTouchTarget = this;
    isTouchDragging = false;

    // FIXED: Better long press detection
    longPressTimer = setTimeout(() => {
        if (touchElement === this && !isTouchDragging) {
            const distance = Math.sqrt(
                Math.pow(touchCurrentX - touchStartX, 2) +
                Math.pow(touchCurrentY - touchStartY, 2)
            );

            // Start drag if minimal movement during long press
            if (distance < TOUCH_MOVE_THRESHOLD) {
                startTouchDrag(this, touch);
            }
        }
    }, LONG_PRESS_DURATION);
}

function handleTouchMove(e) {
    if (!touchElement) return;

    const touch = e.touches[0];
    touchCurrentX = touch.clientX;
    touchCurrentY = touch.clientY;

    const distance = Math.sqrt(
        Math.pow(touchCurrentX - touchStartX, 2) +
        Math.pow(touchCurrentY - touchStartY, 2)
    );

    const timeDiff = Date.now() - touchStartTime;

    // If we're already touch dragging, update position
    if (isTouchDragging) {
        e.preventDefault(); // Prevent scrolling while dragging
        updateTouchDragPosition(touch);
        updateDropTarget(touch);
        return;
    }

    // FIXED: Cancel long press if user moves too much too quickly (likely scrolling)
    if (distance > TOUCH_MOVE_THRESHOLD && timeDiff < TOUCH_TIME_THRESHOLD) {
        if (longPressTimer) {
            clearTimeout(longPressTimer);
            longPressTimer = null;
        }
        touchElement = null;
        return;
    }
}

function handleTouchEnd(e) {
    // Clear long press timer
    if (longPressTimer) {
        clearTimeout(longPressTimer);
        longPressTimer = null;
    }

    const timeDiff = Date.now() - touchStartTime;
    const distance = Math.sqrt(
        Math.pow(touchCurrentX - touchStartX, 2) +
        Math.pow(touchCurrentY - touchStartY, 2)
    );

    if (isTouchDragging) {
        e.preventDefault();
        completeTouchDrag();
    } else if (distance < TOUCH_MOVE_THRESHOLD && timeDiff < TOUCH_TIME_THRESHOLD) {
        // This is a tap, open modal
        e.preventDefault();
        openModal(initialTouchTarget);
    }

    resetTouchState();
}

function handleTouchCancel(e) {
    // Clear long press timer
    if (longPressTimer) {
        clearTimeout(longPressTimer);
        longPressTimer = null;
    }
    
    if (isTouchDragging) {
        cancelTouchDrag();
    }
    resetTouchState();
}

function startTouchDrag(element, touch) {
    console.log('Starting touch drag'); // Debug log
    
    isTouchDragging = true;
    draggedElement = element;
    touchElement = element;

    draggedData = {
        grup: element.getAttribute('grup'),
        position: element.getAttribute('position'),
        parkingName: element.getAttribute('parking-name'),
        content: element.innerHTML,
        classList: Array.from(element.classList)
    };

    // Add visual feedback
    element.classList.add('dragging');

    // Create drag preview
    createDragPreview(element, touch);

    // Provide haptic feedback if available
    if (navigator.vibrate) {
        navigator.vibrate(50);
    }

    // Prevent default to avoid text selection and scrolling
    document.body.style.userSelect = 'none';
    document.body.style.webkitUserSelect = 'none';
    document.body.style.touchAction = 'none'; // FIXED: Prevent touch actions
}

function createDragPreview(element, touch) {
    dragPreview = element.cloneNode(true);
    dragPreview.classList.add('drag-preview');
    dragPreview.style.position = 'fixed';
    dragPreview.style.pointerEvents = 'none';
    dragPreview.style.zIndex = '9999';
    dragPreview.style.opacity = '0.8';
    dragPreview.style.transform = 'scale(1.1) rotate(5deg)';
    dragPreview.style.boxShadow = '0 10px 30px rgba(0,0,0,0.3)';
    dragPreview.style.left = (touch.clientX - 40) + 'px';
    dragPreview.style.top = (touch.clientY - 40) + 'px';
    
    // FIXED: Ensure preview is visible
    dragPreview.style.width = element.offsetWidth + 'px';
    dragPreview.style.height = element.offsetHeight + 'px';

    document.body.appendChild(dragPreview);
}

function updateTouchDragPosition(touch) {
    if (dragPreview) {
        dragPreview.style.left = (touch.clientX - 40) + 'px';
        dragPreview.style.top = (touch.clientY - 40) + 'px';
    }
}

function updateDropTarget(touch) {
    // FIXED: Temporarily hide drag preview to get element below
    let elementBelow = null;
    
    if (dragPreview) {
        const prevDisplay = dragPreview.style.display;
        dragPreview.style.display = 'none';
        elementBelow = document.elementFromPoint(touch.clientX, touch.clientY);
        dragPreview.style.display = prevDisplay;
    } else {
        elementBelow = document.elementFromPoint(touch.clientX, touch.clientY);
    }

    // Remove previous drop target highlight
    if (dropTarget && dropTarget !== draggedElement) {
        dropTarget.classList.remove('drag-over');
    }

    // Find valid drop target
    const validTarget = elementBelow?.closest(DRAGGABLE_SELECTOR);

    if (validTarget && validTarget !== draggedElement) {
        dropTarget = validTarget;
        dropTarget.classList.add('drag-over');
        console.log('Drop target found:', dropTarget); // Debug log
    } else {
        dropTarget = null;
        console.log('No valid drop target'); // Debug log
    }
}

function completeTouchDrag() {
    console.log('Completing touch drag. Drop target:', dropTarget); // Debug log
    
    if (dropTarget && dropTarget !== draggedElement) {
        // Perform the drop
        const targetData = {
            grup: dropTarget.getAttribute('grup'),
            position: dropTarget.getAttribute('position'),
            parkingName: dropTarget.getAttribute('parking-name'),
            content: dropTarget.innerHTML,
            classList: Array.from(dropTarget.classList)
        };

        if (validateMove(draggedData, targetData)) {
            swapElements(draggedElement, dropTarget, draggedData, targetData);

            // Haptic feedback for success
            if (navigator.vibrate) {
                navigator.vibrate([50, 50, 50]);
            }
        } else {
            showMessage('Perpindahan tidak diizinkan!', 'error');

            // Haptic feedback for error
            if (navigator.vibrate) {
                navigator.vibrate([200]);
            }
        }
    } else {
        console.log('No drop performed - invalid target or same element');
    }

    // Clean up
    cleanupTouchDrag();
}

function cancelTouchDrag() {
    console.log('Touch drag cancelled');
    cleanupTouchDrag();
}

function cleanupTouchDrag() {
    // Remove visual feedback
    if (draggedElement) {
        draggedElement.classList.remove('dragging');
    }

    if (dropTarget) {
        dropTarget.classList.remove('drag-over');
    }

    // Remove drag preview
    if (dragPreview) {
        dragPreview.remove();
        dragPreview = null;
    }

    // Reset body styles
    document.body.style.userSelect = '';
    document.body.style.webkitUserSelect = '';
    document.body.style.touchAction = ''; // FIXED: Reset touch action
}

function resetTouchState() {
    touchElement = null;
    draggedElement = null;
    draggedData = null;
    dropTarget = null;
    isTouchDragging = false;
    initialTouchTarget = null;
    
    // Clear timer if still running
    if (longPressTimer) {
        clearTimeout(longPressTimer);
        longPressTimer = null;
    }
}

// Rest of the functions remain the same...
function handleMouseDown(e) {
    dragStartTime = Date.now();
    isDragging = false;
}

function handleMouseUp(e) {
    const timeDiff = Date.now() - dragStartTime;
    if (timeDiff < 200 && !isDragging) {
        // Don't prevent default here, let click handler work
    }
}

function handleClick(e) {
    const timeDiff = Date.now() - dragStartTime;
    if (timeDiff < 200 && !isDragging) {
        e.preventDefault();
        openModal(this);
    }
}

function handleDragStart(e) {
    console.log('Drag started on:', this);
    isDragging = true;
    draggedElement = this;
    draggedData = {
        grup: this.getAttribute('grup'),
        position: this.getAttribute('position'),
        parkingName: this.getAttribute('parking-name'),
        content: this.innerHTML,
        classList: Array.from(this.classList)
    };

    this.classList.add('dragging');
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', this.outerHTML);
}

function handleDragEnd(e) {
    console.log('Drag ended');
    this.classList.remove('dragging');

    document.querySelectorAll('.drag-over').forEach(el => {
        el.classList.remove('drag-over');
    });

    setTimeout(() => {
        isDragging = false;
        draggedElement = null;
        draggedData = null;
    }, 100);
}

function handleDragOver(e) {
    if (e.preventDefault) {
        e.preventDefault();
    }
    e.dataTransfer.dropEffect = 'move';
    return false;
}

function handleDragEnter(e) {
    if (this !== draggedElement) {
        this.classList.add('drag-over');
    }
}

function handleDragLeave(e) {
    this.classList.remove('drag-over');
}

function handleDrop(e) {
    console.log('Drop event triggered');
    if (e.preventDefault) {
        e.preventDefault();
    }
    if (e.stopPropagation) {
        e.stopPropagation();
    }

    if (this !== draggedElement && draggedElement) {
        const targetData = {
            grup: this.getAttribute('grup'),
            position: this.getAttribute('position'),
            parkingName: this.getAttribute('parking-name'),
            content: this.innerHTML,
            classList: Array.from(this.classList)
        };

        if (validateMove(draggedData, targetData)) {
            swapElements(draggedElement, this, draggedData, targetData);
        } else {
            showMessage('Perpindahan tidak diizinkan!', 'error');
        }
    }

    this.classList.remove('drag-over');
    return false;
}

// Modal and other functions remain the same...
function openModal(seatElement) {
    var grup     = $(seatElement).attr('grup');
    var position = $(seatElement).attr('position');
    var seatId   = $(seatElement).attr('id');
    var parking  = $(seatElement).attr('parking-name');

    const date   = $('#current-date').val();

    $("#parking-form").trigger("reset");
    $("#parking-grup").val(grup);
    $("#parking-position").val(position);
    $("#seat-id").val(seatId);
    $("#parking-name").val(parking);
    
    $("#addModal").modal('show');

    $.ajax({
        type: "POST",
        url: "/parkir/get_detail",
        data: {
            grup   : grup,
            posisi : position,
            date   : date
        },
        dataType: "json",
        success: function (response) {
            if(response.code === 200){
                const label = ['parking-id','parking-license-plate', 'parking-model', 'parking-other', 'parking-status', 'parking-job', 'parking-technician'];
                const field = ['id','license_plate', 'model_code', 'others', 'status', 'category', 'technician'];
                const detail = response.data;

                console.log(response.data);
                if(detail != null){
                    $("#parking-id").prop('disabled', false);
                    $(".btn-delete").removeClass('d-none');
                    label.forEach((element,index) => {
                        $(`#${element}`).val(detail[field[index]]);
                    });

                    if(detail.others){
                        $("#other-wrap").removeClass("d-none");
                    } else {
                        $("#other-wrap").addClass("d-none");
                    }

                    $("#modalGrup").html(grup)
                    $("#modalPosition").html(position)
                    $("#modalStatus")
                        .text("Terisi")
                        .removeClass("status-empty")
                        .addClass("status-badge status-occupied");
                }
            } else {
                $("#modalGrup").html("-")
                $("#modalPosition").html("-")
                $("#modalStatus")
                    .text("Kosong")
                    .removeClass("status-occupied")
                    .addClass("status-badge status-empty");

                $("#parking-id").prop('disabled', true);
                $("#other-wrap").addClass("d-none");
                $(".btn-delete").addClass('d-none');
            }
        },
        error : function (err) { 
            console.log(err);
        }
    });
}

function validateMove(draggedData, targetData) {
    if (draggedData.grup === targetData.grup &&
        draggedData.position === targetData.position) {
        return false;
    }
    return true;
}

function swapElements(draggedEl, targetEl, draggedData, targetData) {
    const tempContent = draggedEl.innerHTML;
    draggedEl.innerHTML = targetEl.innerHTML;
    targetEl.innerHTML = tempContent;

    updateElementClasses(draggedEl, targetData.content);
    updateElementClasses(targetEl, draggedData.content);

    updateToServer(draggedData, targetData);
}

function updateElementClasses(element, content) {
    if (content.trim() && content.includes('|')) {
        element.classList.remove('empty-seat');
    } else {
        element.classList.add('empty-seat');
    }
}

function extractDateFromUrl(url = window.location.pathname) {
    const match = url.match(/\d{4}-\d{2}-\d{2}/);
    return match ? match[0] : null;
}

function updateToServer(from, to) {
    const date = extractDateFromUrl();

    $.ajax({
        type: "POST",
        url: "/parkir/update_posisi",
        data: {
            grup: from.grup,
            posisi: from.position,
            newGrup: to.grup,
            newPosisi: to.position,
            date : date
        },
        dataType: "json",
        success: function (response) {
            console.log(response)
            showMessage('Perpindahan berhasil!', 'success');
        },
        error: function (err) {
            console.log(err)
            showMessage('Perpindahan tidak diizinkan!', 'error');
        }
    });
}

function showMessage(text, type) {
    const message = document.createElement('div');
    message.className = `message ${type}`;
    message.textContent = text;

    document.body.appendChild(message);

    setTimeout(() => {
        message.remove();
    }, 3000);
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeDragAndDrop();
    console.log('Parking system initialized with click and drag functionality');

    const seats = document.querySelectorAll(DRAGGABLE_SELECTOR);
    console.log('Found', seats.length, 'draggable seats');
});

// Close modal and keyboard support
document.addEventListener('click', function(e) {
    const modal = document.getElementById('seatModal');
    if (e.target === modal) {
        closeModal();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        if (draggedElement) {
            draggedElement.classList.remove('dragging');
            document.querySelectorAll('.drag-over').forEach(el => {
                el.classList.remove('drag-over');
            });
            draggedElement = null;
            draggedData = null;
            isDragging = false;
        }

        const modal = document.getElementById('seatModal');
        if (modal && modal.classList.contains('active')) {
            closeModal();
        }
    }
});