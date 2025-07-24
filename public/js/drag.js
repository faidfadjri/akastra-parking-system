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
let longPressTimer = null;

const TOUCH_MOVE_THRESHOLD = 10;
const TOUCH_TIME_THRESHOLD = 300;
const LONG_PRESS_DURATION = 500; // Reduced for better UX

function initializeDragAndDrop() {
    const draggableElements = document.querySelectorAll(DRAGGABLE_SELECTOR);
    console.log('Initializing drag and drop for', draggableElements.length, 'elements');

    draggableElements.forEach(element => {
        // Desktop drag events
        element.draggable = true;
        element.addEventListener('dragstart', handleDragStart);
        element.addEventListener('dragend', handleDragEnd);
        element.addEventListener('dragover', handleDragOver);
        element.addEventListener('drop', handleDrop);
        element.addEventListener('dragenter', handleDragEnter);
        element.addEventListener('dragleave', handleDragLeave);

        // Mouse events for desktop
        element.addEventListener('mousedown', handleMouseDown);
        element.addEventListener('mouseup', handleMouseUp);
        element.addEventListener('click', handleClick);

        // Touch events - always attach for mobile compatibility
        element.addEventListener('touchstart', handleTouchStart, { passive: false });
        element.addEventListener('touchmove', handleTouchMove, { passive: false });
        element.addEventListener('touchend', handleTouchEnd, { passive: false });
        element.addEventListener('touchcancel', handleTouchCancel, { passive: false });
    });
}

// Touch Event Handlers
function handleTouchStart(e) {
    console.log('Touch start detected');
    
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

    // Set up long press timer
    longPressTimer = setTimeout(() => {
        if (touchElement === this && !isTouchDragging) {
            const distance = Math.sqrt(
                Math.pow(touchCurrentX - touchStartX, 2) +
                Math.pow(touchCurrentY - touchStartY, 2)
            );

            if (distance < TOUCH_MOVE_THRESHOLD) {
                console.log('Long press detected, starting drag');
                e.preventDefault(); // Prevent any default action
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

    // If we're already dragging, update position
    if (isTouchDragging) {
        e.preventDefault();
        console.log('Updating drag position');
        updateTouchDragPosition(touch);
        updateDropTarget(touch);
        return;
    }

    // If user moves too much too quickly, cancel long press (likely scrolling)
    const timeDiff = Date.now() - touchStartTime;
    if (distance > TOUCH_MOVE_THRESHOLD && timeDiff < 200) {
        console.log('Quick movement detected, canceling long press');
        if (longPressTimer) {
            clearTimeout(longPressTimer);
            longPressTimer = null;
        }
        touchElement = null;
        return;
    }
}

function handleTouchEnd(e) {
    console.log('Touch end, dragging state:', isTouchDragging);
    
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
        console.log('Completing touch drag');
        e.preventDefault();
        completeTouchDrag();
    } else if (distance < TOUCH_MOVE_THRESHOLD && timeDiff < TOUCH_TIME_THRESHOLD) {
        console.log('Quick tap detected, opening modal');
        // Small delay to prevent conflicts
        setTimeout(() => {
            openModal(initialTouchTarget);
        }, 50);
    }

    resetTouchState();
}

function handleTouchCancel(e) {
    console.log('Touch cancelled');
    
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
    console.log('=== STARTING TOUCH DRAG ===');
    console.log('Element:', element);
    
    isTouchDragging = true;
    draggedElement = element;
    touchElement = element;

    // Get element data
    draggedData = {
        grup: element.getAttribute('grup'),
        position: element.getAttribute('position'),
        parkingName: element.getAttribute('parking-name'),
        content: element.innerHTML,
        classList: Array.from(element.classList)
    };

    console.log('Drag data:', draggedData);

    // Visual feedback
    element.classList.add('dragging');
    
    // Create drag preview
    createDragPreview(element, touch);

    // Haptic feedback
    if (navigator.vibrate) {
        navigator.vibrate(50);
    }

    // Prevent scrolling and text selection
    document.body.style.userSelect = 'none';
    document.body.style.webkitUserSelect = 'none';
    document.body.style.touchAction = 'none';
    document.body.style.overflow = 'hidden'; // Prevent scroll completely
}

function createDragPreview(element, touch) {
    // Remove existing preview
    if (dragPreview) {
        dragPreview.remove();
    }
    
    dragPreview = element.cloneNode(true);
    dragPreview.classList.add('drag-preview');
    
    // Style the preview
    Object.assign(dragPreview.style, {
        position: 'fixed',
        pointerEvents: 'none',
        zIndex: '9999',
        opacity: '0.8',
        transform: 'scale(1.1) rotate(5deg)',
        boxShadow: '0 10px 30px rgba(0,0,0,0.5)',
        border: '2px solid #007bff',
        backgroundColor: 'rgba(255,255,255,0.9)',
        left: (touch.clientX - 30) + 'px',
        top: (touch.clientY - 30) + 'px',
        width: element.offsetWidth + 'px',
        height: element.offsetHeight + 'px'
    });

    document.body.appendChild(dragPreview);
    console.log('Drag preview created');
}

function updateTouchDragPosition(touch) {
    if (dragPreview) {
        dragPreview.style.left = (touch.clientX - 30) + 'px';
        dragPreview.style.top = (touch.clientY - 30) + 'px';
    }
}

function updateDropTarget(touch) {
    let elementBelow = null;
    
    // Hide preview to get element below
    if (dragPreview) {
        dragPreview.style.visibility = 'hidden';
    }
    
    elementBelow = document.elementFromPoint(touch.clientX, touch.clientY);
    
    // Show preview again
    if (dragPreview) {
        dragPreview.style.visibility = 'visible';
    }

    // Remove previous highlight
    if (dropTarget && dropTarget !== draggedElement) {
        dropTarget.classList.remove('drag-over');
    }

    // Find valid drop target
    const validTarget = elementBelow?.closest(DRAGGABLE_SELECTOR);

    if (validTarget && validTarget !== draggedElement) {
        dropTarget = validTarget;
        dropTarget.classList.add('drag-over');
        console.log('Valid drop target:', dropTarget.getAttribute('grup') + '-' + dropTarget.getAttribute('position'));
    } else {
        dropTarget = null;
    }
}

function completeTouchDrag() {
    console.log('=== COMPLETING TOUCH DRAG ===');
    console.log('Drop target:', dropTarget);
    console.log('Dragged element:', draggedElement);

    if (!draggedElement || !draggedData) {
        console.error('Missing drag data');
        cleanupTouchDrag();
        return;
    }

    if (dropTarget && dropTarget !== draggedElement) {
        const targetData = {
            grup: dropTarget.getAttribute('grup'),
            position: dropTarget.getAttribute('position'),
            parkingName: dropTarget.getAttribute('parking-name'),
            content: dropTarget.innerHTML,
            classList: Array.from(dropTarget.classList)
        };

        console.log('Target data:', targetData);

        if (validateMove(draggedData, targetData)) {
            console.log('✅ Valid move - swapping elements');
            swapElements(draggedElement, dropTarget, draggedData, targetData);
            
            // Success feedback
            if (navigator.vibrate) {
                navigator.vibrate([50, 50, 50]);
            }
            showMessage('Perpindahan berhasil!', 'success');
        } else {
            console.log('❌ Invalid move');
            showMessage('Perpindahan tidak diizinkan!', 'error');
            if (navigator.vibrate) {
                navigator.vibrate([200]);
            }
        }
    } else {
        console.log('No valid drop target');
    }

    cleanupTouchDrag();
}

function cancelTouchDrag() {
    console.log('Touch drag cancelled');
    showMessage('Drag dibatalkan', 'error');
    cleanupTouchDrag();
}

function cleanupTouchDrag() {
    console.log('Cleaning up touch drag');
    
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
    document.body.style.touchAction = '';
    document.body.style.overflow = '';
}

function resetTouchState() {
    console.log('Resetting touch state');
    touchElement = null;
    draggedElement = null;
    draggedData = null;
    dropTarget = null;
    isTouchDragging = false;
    initialTouchTarget = null;
    
    if (longPressTimer) {
        clearTimeout(longPressTimer);
        longPressTimer = null;
    }
}

// Desktop Event Handlers
function handleMouseDown(e) {
    // Only handle if not a touch device
    if (e.type === 'mousedown' && window.TouchEvent && e instanceof TouchEvent) {
        return;
    }
    dragStartTime = Date.now();
    isDragging = false;
}

function handleMouseUp(e) {
    // Only handle if not a touch device
    if (e.type === 'mouseup' && window.TouchEvent && e instanceof TouchEvent) {
        return;
    }
    const timeDiff = Date.now() - dragStartTime;
    if (timeDiff < 200 && !isDragging) {
        // Let click handler work
    }
}

function handleClick(e) {
    // Skip if this is from a touch event
    if (isTouchDragging || touchElement) {
        return;
    }
    
    const timeDiff = Date.now() - dragStartTime;
    if (timeDiff < 200 && !isDragging) {
        e.preventDefault();
        openModal(this);
    }
}

function handleDragStart(e) {
    // Prevent desktop drag if touch is active
    if (isTouchDragging || touchElement) {
        e.preventDefault();
        return false;
    }
    
    console.log('Desktop drag started');
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
    console.log('Desktop drag ended');
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
    // Skip if touch drag is active
    if (isTouchDragging) {
        console.log('Skipping desktop drop - touch drag active');
        return false;
    }
    
    console.log('Desktop drop event');
    if (e.preventDefault) e.preventDefault();
    if (e.stopPropagation) e.stopPropagation();

    if (!draggedElement || !draggedData) {
        console.warn('Desktop drop: missing drag data');
        return false;
    }

    if (this !== draggedElement) {
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

// Modal function
function openModal(seatElement) {
    console.log('Opening modal for element:', seatElement);
    
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
    console.log('Swapping elements:', draggedData.grup + '-' + draggedData.position, '↔', targetData.grup + '-' + targetData.position);
    
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
            console.log('Server response:', response);
            showMessage('Perpindahan berhasil!', 'success');
        },
        error: function (err) {
            console.log('Server error:', err);
            showMessage('Perpindahan gagal!', 'error');
        }
    });
}

function showMessage(text, type) {
    // Remove existing messages
    document.querySelectorAll('.message').forEach(msg => msg.remove());
    
    const message = document.createElement('div');
    message.className = `message ${type}`;
    message.textContent = text;
    message.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        border-radius: 4px;
        color: white;
        font-weight: bold;
        z-index: 10000;
        ${type === 'success' ? 'background-color: #28a745;' : 'background-color: #dc3545;'}
    `;

    document.body.appendChild(message);

    setTimeout(() => {
        message.remove();
    }, 3000);
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Initializing parking system...');
    initializeDragAndDrop();

    const seats = document.querySelectorAll(DRAGGABLE_SELECTOR);
    console.log('✅ Found', seats.length, 'draggable seats');
    
    // Test touch support
    console.log('Touch support:', 'ontouchstart' in window);
    console.log('Max touch points:', navigator.maxTouchPoints);
});

// Keyboard support
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        if (isTouchDragging) {
            console.log('ESC pressed - cancelling touch drag');
            cancelTouchDrag();
        }
        
        if (draggedElement) {
            draggedElement.classList.remove('dragging');
            document.querySelectorAll('.drag-over').forEach(el => {
                el.classList.remove('drag-over');
            });
            draggedElement = null;
            draggedData = null;
            isDragging = false;
        }
    }
});