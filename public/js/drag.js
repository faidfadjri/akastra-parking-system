/**
 * Complete Drag & Drop System for Parking Seat Management
 * Supports both desktop (mouse) and mobile (touch) interactions
 * 
 * Author: Professional Development Team
 * Version: 2.0
 */

// ==========================================
// CONFIGURATION & GLOBAL VARIABLES
// ==========================================

const DRAGGABLE_SELECTOR = '.seat-vertical, .seat-horizontal, .seat-vertical-short, .seat-horizontal-wide, .seat-vertical-wide, .seat-horizontal-oven';

// Desktop drag state
let draggedElement = null;
let draggedData = null;
let isDragging = false;
let dragStartTime = 0;

// Touch handling state
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

// Touch interaction thresholds
const TOUCH_MOVE_THRESHOLD = 10;
const TOUCH_TIME_THRESHOLD = 300;
const LONG_PRESS_DURATION = 500;

// ==========================================
// INITIALIZATION FUNCTIONS
// ==========================================

/**
 * Initialize drag and drop functionality for all draggable elements
 * Called when DOM is ready
 */
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

        // Mouse events for desktop clicks
        element.addEventListener('mousedown', handleMouseDown);
        element.addEventListener('mouseup', handleMouseUp);
        element.addEventListener('click', handleClick);

        // Touch events for mobile devices
        element.addEventListener('touchstart', handleTouchStart, { passive: false });
        element.addEventListener('touchmove', handleTouchMove, { passive: false });
        element.addEventListener('touchend', handleTouchEnd, { passive: false });
        element.addEventListener('touchcancel', handleTouchCancel, { passive: false });
    });
}

// ==========================================
// TOUCH EVENT HANDLERS (Mobile)
// ==========================================

/**
 * Handle touch start event - initialize touch tracking and long press timer
 * @param {TouchEvent} e - Touch start event
 */
function handleTouchStart(e) {
    console.log('Touch start detected on element:', this);
    
    // Clear any existing long press timer
    if (longPressTimer) {
        clearTimeout(longPressTimer);
        longPressTimer = null;
    }

    // Initialize touch tracking variables
    const touch = e.touches[0];
    touchStartX = touch.clientX;
    touchStartY = touch.clientY;
    touchCurrentX = touch.clientX;
    touchCurrentY = touch.clientY;
    touchStartTime = Date.now();
    touchElement = this;
    initialTouchTarget = this;
    isTouchDragging = false;

    // Set up long press timer for drag initiation
    longPressTimer = setTimeout(() => {
        if (touchElement === this && !isTouchDragging) {
            const distance = Math.sqrt(
                Math.pow(touchCurrentX - touchStartX, 2) +
                Math.pow(touchCurrentY - touchStartY, 2)
            );

            // Only start drag if finger hasn't moved much
            if (distance < TOUCH_MOVE_THRESHOLD) {
                console.log('Long press detected, starting touch drag');
                e.preventDefault();
                startTouchDrag(this, touch);
            }
        }
    }, LONG_PRESS_DURATION);
}

/**
 * Handle touch move event - track finger movement and update drag position
 * @param {TouchEvent} e - Touch move event
 */
function handleTouchMove(e) {
    if (!touchElement) return;

    const touch = e.touches[0];
    touchCurrentX = touch.clientX;
    touchCurrentY = touch.clientY;

    const distance = Math.sqrt(
        Math.pow(touchCurrentX - touchStartX, 2) +
        Math.pow(touchCurrentY - touchStartY, 2)
    );

    // If already dragging, update drag position
    if (isTouchDragging) {
        e.preventDefault();
        updateTouchDragPosition(touch);
        updateDropTarget(touch);
        return;
    }

    // Cancel long press if user moves finger too quickly (likely scrolling)
    const timeDiff = Date.now() - touchStartTime;
    if (distance > TOUCH_MOVE_THRESHOLD && timeDiff < 200) {
        console.log('Quick movement detected, canceling long press timer');
        if (longPressTimer) {
            clearTimeout(longPressTimer);
            longPressTimer = null;
        }
        touchElement = null;
        return;
    }
}

/**
 * Handle touch end event - complete drag operation or trigger tap action
 * @param {TouchEvent} e - Touch end event
 */
function handleTouchEnd(e) {
    console.log('Touch end detected, dragging state:', isTouchDragging);
    console.log('Initial touch target:', initialTouchTarget);
    
    // Clear long press timer
    if (longPressTimer) {
        clearTimeout(longPressTimer);
        longPressTimer = null;
    }

    // Calculate touch duration and distance
    const timeDiff = Date.now() - touchStartTime;
    const distance = Math.sqrt(
        Math.pow(touchCurrentX - touchStartX, 2) +
        Math.pow(touchCurrentY - touchStartY, 2)
    );

    if (isTouchDragging) {
        // Complete drag operation
        console.log('Completing touch drag operation');
        e.preventDefault();
        completeTouchDrag();
    } else if (distance < TOUCH_MOVE_THRESHOLD && timeDiff < TOUCH_TIME_THRESHOLD && initialTouchTarget) {
        // Handle quick tap - open modal
        console.log('Quick tap detected, opening modal');
        e.preventDefault();
        
        // Store element reference before resetting state
        const targetElement = initialTouchTarget;
        
        // Open modal immediately with validation
        if (isValidElement(targetElement)) {
            openModal(targetElement);
        } else {
            console.error('Invalid target element for modal opening');
        }
    }

    // Reset touch state after handling the event
    resetTouchState();
}

/**
 * Handle touch cancel event - cleanup touch state
 * @param {TouchEvent} e - Touch cancel event
 */
function handleTouchCancel(e) {
    console.log('Touch event cancelled');
    
    // Clear timers and cleanup drag state
    if (longPressTimer) {
        clearTimeout(longPressTimer);
        longPressTimer = null;
    }
    
    if (isTouchDragging) {
        cancelTouchDrag();
    }
    
    resetTouchState();
}

// ==========================================
// TOUCH DRAG OPERATIONS
// ==========================================

/**
 * Start touch drag operation with visual feedback
 * @param {HTMLElement} element - Element being dragged
 * @param {Touch} touch - Touch object with coordinates
 */
function startTouchDrag(element, touch) {
    console.log('=== STARTING TOUCH DRAG OPERATION ===');
    console.log('Dragging element:', element);
    
    // Set drag state
    isTouchDragging = true;
    draggedElement = element;
    touchElement = element;

    // Extract element data for drag operation
    draggedData = {
        grup: element.getAttribute('grup'),
        position: element.getAttribute('position'),
        parkingName: element.getAttribute('parking-name'),
        content: element.innerHTML,
        classList: Array.from(element.classList)
    };

    console.log('Drag data extracted:', draggedData);

    // Add visual feedback
    element.classList.add('dragging');
    
    // Create visual drag preview
    createDragPreview(element, touch);

    // Provide haptic feedback on supported devices
    if (navigator.vibrate) {
        navigator.vibrate(50);
    }

    // Prevent page scrolling and text selection during drag
    document.body.style.userSelect = 'none';
    document.body.style.webkitUserSelect = 'none';
    document.body.style.touchAction = 'none';
    document.body.style.overflow = 'hidden';
}

/**
 * Create visual preview element that follows finger during drag
 * @param {HTMLElement} element - Original element being dragged
 * @param {Touch} touch - Touch object with coordinates
 */
function createDragPreview(element, touch) {
    // Remove any existing preview
    if (dragPreview) {
        dragPreview.remove();
    }
    
    // Clone the original element for preview
    dragPreview = element.cloneNode(true);
    dragPreview.classList.add('drag-preview');
    
    // Style the preview with enhanced visual effects
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
    console.log('Drag preview created and positioned');
}

/**
 * Update drag preview position as finger moves
 * @param {Touch} touch - Touch object with current coordinates
 */
function updateTouchDragPosition(touch) {
    if (dragPreview) {
        dragPreview.style.left = (touch.clientX - 30) + 'px';
        dragPreview.style.top = (touch.clientY - 30) + 'px';
    }
}

/**
 * Update drop target highlighting based on finger position
 * @param {Touch} touch - Touch object with current coordinates
 */
function updateDropTarget(touch) {
    let elementBelow = null;
    
    // Temporarily hide preview to detect element below finger
    if (dragPreview) {
        dragPreview.style.visibility = 'hidden';
    }
    
    elementBelow = document.elementFromPoint(touch.clientX, touch.clientY);
    
    // Show preview again
    if (dragPreview) {
        dragPreview.style.visibility = 'visible';
    }

    // Remove previous drop target highlighting
    if (dropTarget && dropTarget !== draggedElement) {
        dropTarget.classList.remove('drag-over');
    }

    // Find and highlight valid drop target
    const validTarget = elementBelow?.closest(DRAGGABLE_SELECTOR);

    if (validTarget && validTarget !== draggedElement) {
        dropTarget = validTarget;
        dropTarget.classList.add('drag-over');
        console.log('Valid drop target found:', dropTarget.getAttribute('grup') + '-' + dropTarget.getAttribute('position'));
    } else {
        dropTarget = null;
    }
}

/**
 * Complete touch drag operation by swapping elements if valid
 */
function completeTouchDrag() {
    console.log('=== COMPLETING TOUCH DRAG OPERATION ===');
    console.log('Drop target:', dropTarget);
    console.log('Dragged element:', draggedElement);

    // Validate drag data exists
    if (!draggedElement || !draggedData) {
        console.error('Missing drag data, cannot complete operation');
        cleanupTouchDrag();
        return;
    }

    // Execute swap if valid drop target exists
    if (dropTarget && dropTarget !== draggedElement) {
        const targetData = {
            grup: dropTarget.getAttribute('grup'),
            position: dropTarget.getAttribute('position'),
            parkingName: dropTarget.getAttribute('parking-name'),
            content: dropTarget.innerHTML,
            classList: Array.from(dropTarget.classList)
        };

        console.log('Target data extracted:', targetData);

        // Validate and execute move
        if (validateMove(draggedData, targetData)) {
            console.log('✅ Valid move detected - executing element swap');
            swapElements(draggedElement, dropTarget, draggedData, targetData);
            
            // Provide success feedback
            if (navigator.vibrate) {
                navigator.vibrate([50, 50, 50]);
            }
            showMessage('Perpindahan berhasil!', 'success');
        } else {
            console.log('❌ Invalid move attempted');
            showMessage('Perpindahan tidak diizinkan!', 'error');
            if (navigator.vibrate) {
                navigator.vibrate([200]);
            }
        }
    } else {
        console.log('No valid drop target found');
    }

    cleanupTouchDrag();
}

/**
 * Cancel touch drag operation and provide feedback
 */
function cancelTouchDrag() {
    console.log('Touch drag operation cancelled');
    showMessage('Drag dibatalkan', 'error');
    cleanupTouchDrag();
}

/**
 * Clean up visual elements and restore normal page behavior
 */
function cleanupTouchDrag() {
    console.log('Cleaning up touch drag operation');
    
    // Remove visual drag feedback
    if (draggedElement) {
        draggedElement.classList.remove('dragging');
    }

    if (dropTarget) {
        dropTarget.classList.remove('drag-over');
    }

    // Remove drag preview from DOM
    if (dragPreview) {
        dragPreview.remove();
        dragPreview = null;
    }

    // Restore normal page behavior
    document.body.style.userSelect = '';
    document.body.style.webkitUserSelect = '';
    document.body.style.touchAction = '';
    document.body.style.overflow = '';
}

/**
 * Reset all touch-related state variables
 */
function resetTouchState() {
    console.log('Resetting touch state variables');
    
    // Reset drag-related variables immediately
    draggedElement = null;
    draggedData = null;
    dropTarget = null;
    isTouchDragging = false;
    
    // Reset touch tracking variables with slight delay to prevent race conditions
    setTimeout(() => {
        touchElement = null;
        initialTouchTarget = null;
    }, 100);
    
    // Clear any remaining timers
    if (longPressTimer) {
        clearTimeout(longPressTimer);
        longPressTimer = null;
    }
}

// ==========================================
// DESKTOP EVENT HANDLERS (Mouse)
// ==========================================

/**
 * Handle mouse down event for desktop drag preparation
 * @param {MouseEvent} e - Mouse down event
 */
function handleMouseDown(e) {
    // Skip if this is actually a touch event
    if (e.type === 'mousedown' && window.TouchEvent && e instanceof TouchEvent) {
        return;
    }
    
    dragStartTime = Date.now();
    isDragging = false;
}

/**
 * Handle mouse up event for desktop interaction
 * @param {MouseEvent} e - Mouse up event
 */
function handleMouseUp(e) {
    // Skip if this is actually a touch event
    if (e.type === 'mouseup' && window.TouchEvent && e instanceof TouchEvent) {
        return;
    }
    
    const timeDiff = Date.now() - dragStartTime;
    if (timeDiff < 200 && !isDragging) {
        // Quick click detected - let click handler process it
    }
}

/**
 * Handle click event for desktop modal opening
 * @param {MouseEvent} e - Click event
 */
function handleClick(e) {
    // Prevent conflicts with touch interactions
    if (isTouchDragging || touchElement || initialTouchTarget) {
        console.log('Click event blocked - touch interaction in progress');
        e.preventDefault();
        e.stopPropagation();
        return;
    }
    
    const timeDiff = Date.now() - dragStartTime;
    if (timeDiff < 200 && !isDragging) {
        e.preventDefault();
        openModal(this);
    }
}

/**
 * Handle desktop drag start event
 * @param {DragEvent} e - Drag start event
 */
function handleDragStart(e) {
    // Prevent desktop drag if touch interaction is active
    if (isTouchDragging || touchElement) {
        e.preventDefault();
        return false;
    }
    
    console.log('Desktop drag operation started');
    isDragging = true;
    draggedElement = this;
    
    // Extract element data for desktop drag
    draggedData = {
        grup: this.getAttribute('grup'),
        position: this.getAttribute('position'),
        parkingName: this.getAttribute('parking-name'),
        content: this.innerHTML,
        classList: Array.from(this.classList)
    };

    // Add visual feedback and configure drag transfer
    this.classList.add('dragging');
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', this.outerHTML);
}

/**
 * Handle desktop drag end event
 * @param {DragEvent} e - Drag end event
 */
function handleDragEnd(e) {
    console.log('Desktop drag operation ended');
    
    // Remove visual feedback
    this.classList.remove('dragging');
    document.querySelectorAll('.drag-over').forEach(el => {
        el.classList.remove('drag-over');
    });

    // Reset state with slight delay to prevent conflicts
    setTimeout(() => {
        isDragging = false;
        draggedElement = null;
        draggedData = null;
    }, 100);
}

/**
 * Handle desktop drag over event
 * @param {DragEvent} e - Drag over event
 */
function handleDragOver(e) {
    if (e.preventDefault) {
        e.preventDefault();
    }
    e.dataTransfer.dropEffect = 'move';
    return false;
}

/**
 * Handle desktop drag enter event
 * @param {DragEvent} e - Drag enter event
 */
function handleDragEnter(e) {
    if (this !== draggedElement) {
        this.classList.add('drag-over');
    }
}

/**
 * Handle desktop drag leave event
 * @param {DragEvent} e - Drag leave event
 */
function handleDragLeave(e) {
    this.classList.remove('drag-over');
}

/**
 * Handle desktop drop event
 * @param {DragEvent} e - Drop event
 */
function handleDrop(e) {
    // Skip if touch drag is active
    if (isTouchDragging) {
        console.log('Skipping desktop drop - touch drag operation active');
        return false;
    }
    
    console.log('Desktop drop event triggered');
    if (e.preventDefault) e.preventDefault();
    if (e.stopPropagation) e.stopPropagation();

    // Validate drag data exists
    if (!draggedElement || !draggedData) {
        console.warn('Desktop drop failed - missing drag data');
        return false;
    }

    // Execute drop operation if different element
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

// ==========================================
// UTILITY FUNCTIONS
// ==========================================

/**
 * Validate if element has required attributes for modal opening
 * @param {HTMLElement} element - Element to validate
 * @returns {boolean} - True if element is valid
 */
function isValidElement(element) {
    if (!element) {
        console.error('Element validation failed: element is null or undefined');
        return false;
    }
    
    if (typeof element.getAttribute !== 'function') {
        console.error('Element validation failed: no getAttribute method');
        return false;
    }
    
    const grup = element.getAttribute('grup');
    const position = element.getAttribute('position');
    
    if (!grup || !position) {
        console.error('Element validation failed: missing required attributes (grup/position)');
        return false;
    }
    
    return true;
}

/**
 * Validate if move between elements is allowed
 * @param {Object} draggedData - Data from dragged element
 * @param {Object} targetData - Data from target element
 * @returns {boolean} - True if move is valid
 */
function validateMove(draggedData, targetData) {
    // Prevent moving to same position
    if (draggedData.grup === targetData.grup &&
        draggedData.position === targetData.position) {
        return false;
    }
    return true;
}

/**
 * Swap content and classes between two elements
 * @param {HTMLElement} draggedEl - Source element
 * @param {HTMLElement} targetEl - Target element
 * @param {Object} draggedData - Source element data
 * @param {Object} targetData - Target element data
 */
function swapElements(draggedEl, targetEl, draggedData, targetData) {
    console.log('Executing element swap:', 
        draggedData.grup + '-' + draggedData.position, '↔', 
        targetData.grup + '-' + targetData.position);
    
    // Swap innerHTML content
    const tempContent = draggedEl.innerHTML;
    draggedEl.innerHTML = targetEl.innerHTML;
    targetEl.innerHTML = tempContent;

    // Update element classes based on content
    updateElementClasses(draggedEl, targetData.content);
    updateElementClasses(targetEl, draggedData.content);

    // Send update to server
    updateToServer(draggedData, targetData);
}

/**
 * Update element CSS classes based on content state
 * @param {HTMLElement} element - Element to update
 * @param {string} content - Content to analyze
 */
function updateElementClasses(element, content) {
    if (content.trim() && content.includes('|')) {
        element.classList.remove('empty-seat');
    } else {
        element.classList.add('empty-seat');
    }
}

/**
 * Extract date from current URL path
 * @param {string} url - URL to extract date from
 * @returns {string|null} - Extracted date or null
 */
function extractDateFromUrl(url = window.location.pathname) {
    const match = url.match(/\d{4}-\d{2}-\d{2}/);
    return match ? match[0] : null;
}

/**
 * Display temporary message to user
 * @param {string} text - Message text
 * @param {string} type - Message type ('success' or 'error')
 */
function showMessage(text, type) {
    // Remove any existing messages
    document.querySelectorAll('.message').forEach(msg => msg.remove());
    
    // Create new message element
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

    // Auto-remove message after 3 seconds
    setTimeout(() => {
        message.remove();
    }, 3000);
}

// ==========================================
// MODAL AND SERVER COMMUNICATION
// ==========================================

/**
 * Open modal dialog for seat management
 * @param {HTMLElement} seatElement - Seat element to manage
 */
function openModal(seatElement) {
    console.log('Opening modal for seat element:', seatElement);
    
    // Validate element before proceeding
    if (!isValidElement(seatElement)) {
        console.error('Cannot open modal - invalid seat element');
        return;
    }
    
    // Extract seat information
    const grup = seatElement.getAttribute('grup');
    const position = seatElement.getAttribute('position');
    const seatId = seatElement.getAttribute('id');
    const parking = seatElement.getAttribute('parking-name');
    const date = $('#current-date').val();

    console.log('Modal data - Grup:', grup, 'Position:', position, 'ID:', seatId);

    // Reset and populate form fields
    $("#parking-form").trigger("reset");
    $("#parking-grup").val(grup);
    $("#parking-position").val(position);
    $("#seat-id").val(seatId);
    $("#parking-name").val(parking);
    
    // Show modal dialog
    $("#addModal").modal('show');

    // Fetch seat details from server
    $.ajax({
        type: "POST",
        url: "/parkir/get_detail",
        data: {
            grup: grup,
            posisi: position,
            date: date
        },
        dataType: "json",
        success: function (response) {
            handleModalDataResponse(response, grup, position);
        },
        error: function (err) {
            console.error('Failed to fetch seat details:', err);
        }
    });
}

/**
 * Handle server response for modal data
 * @param {Object} response - Server response
 * @param {string} grup - Seat group
 * @param {string} position - Seat position
 */
function handleModalDataResponse(response, grup, position) {
    if (response.code === 200) {
        const formFields = ['parking-id', 'parking-license-plate', 'parking-model', 'parking-other', 'parking-status', 'parking-job', 'parking-technician'];
        const dataFields = ['id', 'license_plate', 'model_code', 'others', 'status', 'category', 'technician'];
        const detail = response.data;

        console.log('Seat detail data received:', detail);
        
        if (detail != null) {
            // Populate form with existing data
            $("#parking-id").prop('disabled', false);
            $(".btn-delete").removeClass('d-none');
            
            formFields.forEach((fieldId, index) => {
                $(`#${fieldId}`).val(detail[dataFields[index]]);
            });

            // Show/hide other field based on data
            if (detail.others) {
                $("#other-wrap").removeClass("d-none");
            } else {
                $("#other-wrap").addClass("d-none");
            }

            // Update modal status display
            $("#modalGrup").html(grup);
            $("#modalPosition").html(position);
            $("#modalStatus")
                .text("Terisi")
                .removeClass("status-empty")
                .addClass("status-badge status-occupied");
        }
    } else {
        // Handle empty seat
        $("#modalGrup").html("-");
        $("#modalPosition").html("-");
        $("#modalStatus")
            .text("Kosong")
            .removeClass("status-occupied")
            .addClass("status-badge status-empty");

        $("#parking-id").prop('disabled', true);
        $("#other-wrap").addClass("d-none");
        $(".btn-delete").addClass('d-none');
    }
}

/**
 * Send position update to server
 * @param {Object} from - Source position data
 * @param {Object} to - Target position data
 */
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
            date: date
        },
        dataType: "json",
        success: function (response) {
            console.log('Server update successful:', response);
            showMessage('Perpindahan berhasil!', 'success');
        },
        error: function (err) {
            console.error('Server update failed:', err);
            showMessage('Perpindahan gagal!', 'error');
        }
    });
}

// ==========================================
// EVENT LISTENERS AND INITIALIZATION
// ==========================================

/**
 * Initialize system when DOM is fully loaded
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Initializing parking seat management system...');
    
    // Initialize drag and drop functionality
    initializeDragAndDrop();

    // Log system capabilities
    const seats = document.querySelectorAll(DRAGGABLE_SELECTOR);
    console.log('✅ System initialized with', seats.length, 'draggable seats');
    console.log('📱 Touch support:', 'ontouchstart' in window);
    console.log('👆 Max touch points:', navigator.maxTouchPoints);
});

/**
 * Handle keyboard shortcuts
 */
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        // Cancel touch drag operation
        if (isTouchDragging) {
            console.log('ESC key pressed - cancelling touch drag');
            cancelTouchDrag();
        }
        
        // Cancel desktop drag operation
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