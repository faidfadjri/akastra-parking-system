
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

        const TOUCH_MOVE_THRESHOLD = 10;
        const TOUCH_TIME_THRESHOLD = 150;
        const LONG_PRESS_DURATION = 500; 

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

                // Touch events for mobile
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

        // Touch Event Handlers
        function handleTouchStart(e) {
            const touch = e.touches[0];
            touchStartX = touch.clientX;
            touchStartY = touch.clientY;
            touchCurrentX = touch.clientX;
            touchCurrentY = touch.clientY;
            touchStartTime = Date.now();
            touchElement = this;
            initialTouchTarget = this;
            isTouchDragging = false;

            // Set up long press detection for drag initiation
            setTimeout(() => {
                if (touchElement === this && !isTouchDragging) {
                    const timeDiff = Date.now() - touchStartTime;
                    const distance = Math.sqrt(
                        Math.pow(touchCurrentX - touchStartX, 2) +
                        Math.pow(touchCurrentY - touchStartY, 2)
                    );

                    // Start drag if long press and minimal movement
                    if (timeDiff >= LONG_PRESS_DURATION && distance < TOUCH_MOVE_THRESHOLD) {
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

            // Check if we should start dragging (significant movement in short time)
            if (distance > TOUCH_MOVE_THRESHOLD && timeDiff < TOUCH_TIME_THRESHOLD) {
                // This looks like a scroll gesture, don't interfere
                touchElement = null;
                return;
            }
        }

        function handleTouchEnd(e) {
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
            if (isTouchDragging) {
                cancelTouchDrag();
            }
            resetTouchState();
        }

        function startTouchDrag(element, touch) {
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

            // Prevent default to avoid text selection
            document.body.style.userSelect = 'none';
            document.body.style.webkitUserSelect = 'none';
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

            document.body.appendChild(dragPreview);
        }

        function updateTouchDragPosition(touch) {
            if (dragPreview) {
                dragPreview.style.left = (touch.clientX - 40) + 'px';
                dragPreview.style.top = (touch.clientY - 40) + 'px';
            }
        }

        function updateDropTarget(touch) {
            // Get element under touch point (excluding the preview)
            if (dragPreview) {
                dragPreview.style.display = 'none';
            }

            const elementBelow = document.elementFromPoint(touch.clientX, touch.clientY);

            if (dragPreview) {
                dragPreview.style.display = 'block';
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
            } else {
                dropTarget = null;
            }
        }

        function completeTouchDrag() {
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
            }

            // Clean up
            cleanupTouchDrag();
        }

        function cancelTouchDrag() {
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
        }

        function resetTouchState() {
            touchElement = null;
            draggedElement = null;
            draggedData = null;
            dropTarget = null;
            isTouchDragging = false;
            initialTouchTarget = null;
        }

        // Handle mouse down (start of potential click or drag)
        function handleMouseDown(e) {
            dragStartTime = Date.now();
            isDragging = false;
        }

        // Handle mouse up
        function handleMouseUp(e) {
            const timeDiff = Date.now() - dragStartTime;
            // If it's a quick click (less than 200ms), treat as click
            if (timeDiff < 200 && !isDragging) {
                // Don't prevent default here, let click handler work
            }
        }

        // Handle click event
        function handleClick(e) {
            const timeDiff = Date.now() - dragStartTime;
            // Only trigger modal if it's a quick click and not dragging
            if (timeDiff < 200 && !isDragging) {
                e.preventDefault();
                openModal(this);
            }
        }

        // Handle drag start
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

        // Handle drag end
        function handleDragEnd(e) {
            console.log('Drag ended');
            this.classList.remove('dragging');

            // Remove drag-over class from all elements
            document.querySelectorAll('.drag-over').forEach(el => {
                el.classList.remove('drag-over');
            });

            // Reset dragging state after a short delay
            setTimeout(() => {
                isDragging = false;
                draggedElement = null;
                draggedData = null;
            }, 100);
        }

        // Handle drag over
        function handleDragOver(e) {
            if (e.preventDefault) {
                e.preventDefault();
            }

            e.dataTransfer.dropEffect = 'move';
            return false;
        }

        // Handle drag enter
        function handleDragEnter(e) {
            if (this !== draggedElement) {
                this.classList.add('drag-over');
            }
        }

        // Handle drag leave
        function handleDragLeave(e) {
            this.classList.remove('drag-over');
        }

        // Handle drop
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

        // Modal Functions
        function openModal(seatElement) {
            // currentSeat = seatElement;
            // const modal = document.getElementById('seatModal');

            // // Get seat data
            // const grup = seatElement.getAttribute('grup');
            // const position = seatElement.getAttribute('position');
            // const parkingName = seatElement.getAttribute('parking-name');
            // const content = seatElement.innerHTML.trim();

            // // Update modal content
            // document.getElementById('modalTitle').textContent = `${parkingName} - ${grup}${position}`;
            // document.getElementById('modalLocation').textContent = parkingName;
            // document.getElementById('modalPosition').textContent = `${grup}-${position}`;
            // document.getElementById('modalGrup').textContent = grup;

            // // Parse content if vehicle exists
            // if (content && content.includes('|') && !seatElement.classList.contains('empty-seat')) {
            //     const lines = content.split('<br>');
            //     const firstLine = lines[0].split(' | ');
            //     const licensePlate = firstLine[0];
            //     const modelCode = firstLine[1];
            //     const category = lines[1] || '';

            //     document.getElementById('licensePlate').value = licensePlate;
            //     document.getElementById('modelCode').value = modelCode;
            //     document.getElementById('category').value = category;

            //     document.getElementById('modalStatus').textContent = 'Terisi';
            //     document.getElementById('modalStatus').className = 'status-badge status-occupied';
            //     document.getElementById('clearBtn').style.display = 'block';
            // } else {
            //     // Empty slot
            //     document.getElementById('licensePlate').value = '';
            //     document.getElementById('modelCode').value = '';
            //     document.getElementById('category').value = '';

            //     document.getElementById('modalStatus').textContent = 'Kosong';
            //     document.getElementById('modalStatus').className = 'status-badge status-empty';
            //     document.getElementById('clearBtn').style.display = 'none';
            // }

            // // Show modal
            // modal.classList.add('active');
            // document.body.style.overflow = 'hidden';

            //----- Set hidden Key from attribute
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

        function closeModal() {
            const modal = document.getElementById('seatModal');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
            currentSeat = null;
        }

        function saveSlot() {
            if (!currentSeat) return;

            const licensePlate = document.getElementById('licensePlate').value.trim();
            const modelCode = document.getElementById('modelCode').value.trim();
            const category = document.getElementById('category').value.trim();

            if (licensePlate && modelCode && category) {
                
                currentSeat.innerHTML = `${licensePlate} | ${modelCode}<br>${category}`;
                currentSeat.classList.remove('empty-seat');
                showMessage('Data kendaraan berhasil disimpan!', 'success');
            } else if (!licensePlate && !modelCode && !category) {
                clearSlot();
                return;
            } else {
                showMessage('Mohon lengkapi semua data kendaraan!', 'error');
                return;
            }

            logSlotUpdate(currentSeat, {
                licensePlate,
                modelCode,
                category
            });

            closeModal();
        }

        function clearSlot() {
            if (!currentSeat) return;

            currentSeat.innerHTML = '';
            currentSeat.classList.add('empty-seat');

            // Log the change
            logSlotUpdate(currentSeat, null);

            showMessage('Slot parkir berhasil dikosongkan!', 'success');
            closeModal();
        }

        // Log slot updates for server sync
        function logSlotUpdate(seatElement, vehicleData) {
            const updateData = {
                grup: seatElement.getAttribute('grup'),
                position: seatElement.getAttribute('position'),
                parkingName: seatElement.getAttribute('parking-name'),
                vehicleData: vehicleData,
                action: vehicleData ? 'park' : 'clear',
                timestamp: new Date().toISOString()
            };

            console.log('Slot update logged:', updateData);

            // Here you would send this to your server
            // sendSlotUpdateToServer(updateData);
        }

        // Validate move (you can customize this logic)
        function validateMove(draggedData, targetData) {
            // Example validation rules:
            // 1. Can't move to same position
            if (draggedData.grup === targetData.grup &&
                draggedData.position === targetData.position) {
                return false;
            }

            // 2. Can move between any valid parking spots
            // Add more validation rules as needed
            return true;
        }

        // Swap elements content and attributes
        function swapElements(draggedEl, targetEl, draggedData, targetData) {
            const tempContent = draggedEl.innerHTML;
            draggedEl.innerHTML = targetEl.innerHTML;
            targetEl.innerHTML = tempContent;

            updateElementClasses(draggedEl, targetData.content);
            updateElementClasses(targetEl, draggedData.content);

            const recordId = draggedEl.id
            logMove(recordId, draggedData, targetData);
        }

        // Update element classes based on content
        function updateElementClasses(element, content) {
            // Remove empty-seat class if content is added
            if (content.trim() && content.includes('|')) {
                element.classList.remove('empty-seat');
            } else {
                element.classList.add('empty-seat');
            }
        }

        function logMove(recordId, from, to) {
            $.ajax({
                type: "POST",
                url: "/parkir/update_posisi",
                data: {
                    id : recordId,
                    grup: from.grup,
                    posisi: from.position,
                    newGrup: to.grup,
                    newPosisi: to.position,
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

        // Show success/error messages
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

            // Test drag functionality
            const seats = document.querySelectorAll(DRAGGABLE_SELECTOR);
            console.log('Found', seats.length, 'draggable seats');

            // Add debug info
            seats.forEach((seat, index) => {
                // console.log(`Seat ${index}:`, {
                //     grup: seat.getAttribute('grup'),
                //     position: seat.getAttribute('position'),
                //     draggable: seat.draggable,
                //     hasContent: seat.innerHTML.trim().length > 0
                // });
            });
        });

        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('seatModal');
            if (e.target === modal) {
                closeModal();
            }
        });

        // Add keyboard support
        document.addEventListener('keydown', function(e) {
            // ESC key to cancel current drag operation or close modal
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
                if (modal.classList.contains('active')) {
                    closeModal();
                }
            }
        });