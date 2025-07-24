<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Drag & Drop System</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .headline {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        .parkir-wrap {
            display: flex;
            gap: 20px;
            justify-content: space-between;
            align-items: flex-start;
        }

        .gedung-wrap {
            flex-shrink: 0;
        }

        .gedung {
            width: 150px;
            height: 200px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .parking-area {
            flex: 1;
            padding-left: 20px;
        }

        /* Seat Styles */
        .seat-vertical,
        .seat-horizontal,
        .seat-vertical-short,
        .seat-horizontal-wide,
        .seat-vertical-wide,
        .seat-horizontal-oven {
            display: inline-block;
            border: 2px solid #ddd;
            border-radius: 5px;
            text-align: center;
            font-size: 11px;
            line-height: 1.2;
            cursor: move;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            word-wrap: break-word;
        }

        .seat-vertical {
            width: 40px;
            height: 80px;
            padding: 5px 2px;
        }

        .seat-horizontal {
            width: 80px;
            height: 40px;
            padding: 8px 5px;
        }

        .seat-vertical-short {
            width: 40px;
            height: 60px;
            padding: 5px 2px;
        }

        .seat-horizontal-wide {
            width: 100px;
            height: 40px;
            padding: 8px 5px;
        }

        .seat-vertical-wide {
            width: 50px;
            height: 80px;
            padding: 5px 3px;
        }

        .seat-horizontal-oven {
            width: 90px;
            height: 45px;
            padding: 8px 5px;
        }

        /* Color Styles */
        .seat-blue {
            background-color: #007bff;
            border-color: #0056b3;
            color: white;
        }

        .seat {
            background-color: #28a745;
            border-color: #1e7e34;
            color: white;
        }

        .seat-default {
            background-color: #6c757d;
            border-color: #545b62;
            color: white;
        }

        .text-white {
            color: white !important;
        }

        .text-dark {
            color: #333 !important;
        }

        /* Drag States */
        .dragging {
            opacity: 0.5;
            transform: rotate(5deg) scale(1.05);
            z-index: 1000;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .drag-over {
            background-color: #fff3cd !important;
            border-color: #ffc107 !important;
            border-style: dashed !important;
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Empty seat highlight */
        .empty-seat {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            color: #6c757d;
        }

        .empty-seat:before {
            content: 'Kosong';
            font-size: 10px;
            color: #adb5bd;
        }

        /* Layout Helpers */
        .d-flex {
            display: flex;
        }

        .flex-column {
            flex-direction: column;
        }

        .gap-1 {
            gap: 5px;
        }

        .gap-2 {
            gap: 10px;
        }

        .w-100 {
            width: 100%;
        }

        .justify-content-end {
            justify-content: flex-end;
        }

        .justify-content-center {
            justify-content: center;
        }

        .align-items-center {
            align-items: center;
        }

        .mb-3 {
            margin-bottom: 15px;
        }

        .me-5 {
            margin-right: 25px;
        }

        .mt-1 {
            margin-top: 5px;
        }

        .mt-2 {
            margin-top: 10px;
        }

        .me-2 {
            margin-right: 10px;
        }

        .garis-vertical {
            width: 3px;
            height: 80px;
            background-color: #333;
            margin: 0 10px;
        }

        .security-office {
            background-color: #dc3545;
            color: white;
            padding: 10px;
            border-radius: 5px;
            font-size: 12px;
            text-align: center;
            width: 80px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .text-label-vertical-reverse {
            writing-mode: vertical-rl;
            text-orientation: mixed;
            font-size: 12px;
            color: #666;
            margin: 0;
        }

        /* Success/Error Messages */
        .message {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            z-index: 2000;
            animation: slideIn 0.3s ease;
        }

        .message.success {
            background-color: #28a745;
        }

        .message.error {
            background-color: #dc3545;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 3000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal {
            background: white;
            border-radius: 12px;
            padding: 0;
            width: 90%;
            max-width: 500px;
            max-height: 80vh;
            overflow: hidden;
            transform: scale(0.8) translateY(-50px);
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .modal-overlay.active .modal {
            transform: scale(1) translateY(0);
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-bottom: none;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .modal-header .close {
            position: absolute;
            top: 15px;
            right: 20px;
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s;
        }

        .modal-header .close:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .modal-body {
            padding: 25px;
        }

        .seat-info {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .seat-info h4 {
            margin: 0 0 10px 0;
            color: #495057;
            font-size: 1.1rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .info-value {
            font-size: 1rem;
            color: #212529;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #374151;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .modal-footer {
            padding: 20px 25px;
            border-top: 1px solid #e9ecef;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 14px;
        }

        .btn-primary {
            background-color: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background-color: #5a67d8;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        .status-occupied {
            background-color: #dc3545;
            color: white;
        }

        .status-empty {
            background-color: #28a745;
            color: white;
        }

        /* Info Panel */
        .info-panel {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .info-panel h3 {
            margin: 0 0 10px 0;
            color: #495057;
        }

        .legend {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 3px;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="headline">Area Parkir - Drag & Drop System</h1>

        <div class="info-panel">
            <h3>Cara Penggunaan:</h3>
            <p>Drag dan drop kendaraan antar slot parkir. Sistem akan otomatis memvalidasi perpindahan.</p>
            <div class="legend">
                <div class="legend-item">
                    <div class="legend-color seat-blue"></div>
                    <span>Parkiran GR</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color seat"></div>
                    <span>Parkiran Bayangan</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color seat-default"></div>
                    <span>Parkiran BP</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color empty-seat"></div>
                    <span>Slot Kosong</span>
                </div>
            </div>
        </div>

        <div class="parkir-wrap">
            <div class="gedung-wrap">
                <div class="gedung">Gedung Akastra</div>
            </div>

            <div class="parking-area">
                <!-- Parkiran GR - Grup A -->
                <div class="d-flex gap-1 w-100 justify-content-end mb-3">
                    <a class="seat-blue seat-vertical text-white" grup="A" position="1" parking-name="Parkiran GR">
                        B1234XY | Toyota<br>Sedan
                    </a>
                    <a class="seat-blue seat-vertical text-white empty-seat" grup="A" position="2" parking-name="Parkiran GR"></a>
                    <a class="seat-blue seat-vertical text-white" grup="A" position="3" parking-name="Parkiran GR">
                        D9876ZA | Honda<br>SUV
                    </a>
                    <a class="seat-blue seat-vertical text-white empty-seat" grup="A" position="4" parking-name="Parkiran GR"></a>
                    <a class="seat-blue seat-vertical text-white" grup="A" position="5" parking-name="Parkiran GR">
                        F5678BC | Suzuki<br>Hatchback
                    </a>
                    <a class="seat-blue seat-vertical text-white empty-seat" grup="A" position="6" parking-name="Parkiran GR"></a>
                    <a class="seat-blue seat-vertical text-white empty-seat" grup="A" position="7" parking-name="Parkiran GR"></a>
                    <a class="seat-blue seat-vertical text-white" grup="A" position="8" parking-name="Parkiran GR">
                        H1357DF | Mitsubishi<br>MPV
                    </a>
                    <a class="seat-blue seat-vertical text-white empty-seat" grup="A" position="9" parking-name="Parkiran GR"></a>
                    <div class="garis-vertical"></div>
                </div>

                <!-- Parkiran Bayangan GR - Grup B -->
                <div class="d-flex-column w-100 justify-content-center mb-3 me-5">
                    <div class="d-flex justify-content-end gap-2 my-2">
                        <a class="seat seat-horizontal text-white" grup="B" position="1" parking-name="Parkiran Bayangan GR">
                            J2468GH | Daihatsu<br>City Car
                        </a>
                        <a class="seat seat-horizontal text-white empty-seat" grup="B" position="2" parking-name="Parkiran Bayangan GR"></a>
                        <a class="seat seat-horizontal text-white" grup="B" position="3" parking-name="Parkiran Bayangan GR">
                            K3691IJ | Nissan<br>Sedan
                        </a>
                        <a class="seat seat-horizontal text-white empty-seat" grup="B" position="4" parking-name="Parkiran Bayangan GR"></a>
                    </div>
                    <div class="d-flex justify-content-end gap-2 my-2">
                        <a class="seat seat-horizontal text-white" grup="B" position="5" parking-name="Parkiran Bayangan GR">
                            L4702KL | Mazda<br>SUV
                        </a>
                        <a class="seat seat-horizontal text-white empty-seat" grup="B" position="6" parking-name="Parkiran Bayangan GR"></a>
                        <a class="seat seat-horizontal text-white empty-seat" grup="B" position="7" parking-name="Parkiran Bayangan GR"></a>
                        <a class="seat seat-horizontal text-white" grup="B" position="8" parking-name="Parkiran Bayangan GR">
                            M5813MN | Hyundai<br>Hatchback
                        </a>
                    </div>
                </div>

                <!-- Parkiran Dekat Satpam - Grup C -->
                <div class="d-flex flex-column gap-1 w-100 mb-3 me-5">
                    <div class="d-flex gap-2 justify-content-end">
                        <a class="seat text-dark seat-vertical" grup="C" position="1" parking-name="Parkiran Bayangan GR">
                            N6924OP | Isuzu<br>Pickup
                        </a>
                        <a class="seat-blue seat-vertical text-white empty-seat" grup="C" position="2" parking-name="Parkiran GR"></a>
                        <a class="seat-blue seat-vertical text-white" grup="C" position="3" parking-name="Parkiran GR">
                            P7035QR | BMW<br>Sedan
                        </a>
                        <a class="seat-blue seat-vertical text-white empty-seat" grup="C" position="4" parking-name="Parkiran GR"></a>
                        <a class="seat-blue seat-vertical text-white" grup="C" position="5" parking-name="Parkiran GR">
                            Q8146ST | Audi<br>SUV
                        </a>
                        <div class="security-office">Pos Satpam</div>
                    </div>
                    <div class="d-flex gap-2 justify-content-end">
                        <a class="seat text-dark seat-vertical" grup="C" position="6" parking-name="Parkiran Bayangan BP">
                            R9257UV | Volkswagen<br>Hatchback
                        </a>
                        <a class="seat-default seat-vertical text-white empty-seat" grup="C" position="7" parking-name="Parkiran Bayangan BP"></a>
                        <a class="seat-default seat-vertical text-white" grup="C" position="8" parking-name="Parkiran Bayangan BP">
                            S0368WX | Peugeot<br>MPV
                        </a>
                        <a class="seat-default seat-vertical text-white empty-seat" grup="C" position="9" parking-name="Parkiran Bayangan BP"></a>
                        <a class="seat-default seat-vertical text-white" grup="C" position="10" parking-name="Parkiran Bayangan BP">
                            T1479YZ | Chevrolet<br>SUV
                        </a>
                        <div class="security-office">Parkir Motor</div>
                    </div>
                </div>

                <!-- Parkiran BP -->
                <div class="d-flex mt-2 gap-2 justify-content-end w-100 mb-3 me-5">
                    <p class="text-label-vertical-reverse">- Area Parkir BP -</p>
                    <a class="seat text-dark seat-vertical" grup="E" position="1" parking-name="Parkiran Bayangan BP" style="margin-right: 65px;">
                        U2580AB | Ford<br>Sedan
                    </a>
                    <a class="seat-default seat-vertical text-white" grup="E" position="2" parking-name="Parkiran BP">
                        V3691CD | Kia<br>Hatchback
                    </a>
                    <a class="seat-default seat-vertical text-white empty-seat" grup="E" position="3" parking-name="Parkiran BP"></a>
                    <a class="seat-default seat-vertical text-white" grup="E" position="4" parking-name="Parkiran BP">
                        W4702EF | Lexus<br>SUV
                    </a>
                    <a class="seat-default seat-vertical text-white empty-seat" grup="E" position="5" parking-name="Parkiran BP"></a>
                    <a class="seat-default seat-vertical text-white" grup="E" position="6" parking-name="Parkiran BP">
                        X5813GH | Mercedes<br>Sedan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal-overlay" id="seatModal">
        <div class="modal">
            <div class="modal-header">
                <h3 id="modalTitle">Detail Parkir</h3>
                <button class="close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="seat-info">
                    <h4>Informasi Slot</h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Lokasi</span>
                            <span class="info-value" id="modalLocation">-</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Posisi</span>
                            <span class="info-value" id="modalPosition">-</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status</span>
                            <span class="status-badge" id="modalStatus">-</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Grup</span>
                            <span class="info-value" id="modalGrup">-</span>
                        </div>
                    </div>
                </div>

                <div id="vehicleForm">
                    <div class="form-group">
                        <label for="licensePlate">Nomor Plat</label>
                        <input type="text" id="licensePlate" placeholder="Contoh: B1234XY">
                    </div>
                    <div class="form-group">
                        <label for="modelCode">Merk/Model</label>
                        <input type="text" id="modelCode" placeholder="Contoh: Toyota Avanza">
                    </div>
                    <div class="form-group">
                        <label for="category">Kategori</label>
                        <select id="category">
                            <option value="">Pilih Kategori</option>
                            <option value="Sedan">Sedan</option>
                            <option value="SUV">SUV</option>
                            <option value="MPV">MPV</option>
                            <option value="Hatchback">Hatchback</option>
                            <option value="Pickup">Pickup</option>
                            <option value="City Car">City Car</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal()">Batal</button>
                <button class="btn btn-danger" id="clearBtn" onclick="clearSlot()">Kosongkan</button>
                <button class="btn btn-primary" onclick="saveSlot()">Simpan</button>
            </div>
        </div>
    </div>

    <script>
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

        // Touch thresholds
        const TOUCH_MOVE_THRESHOLD = 10; // pixels to start drag
        const TOUCH_TIME_THRESHOLD = 150; // ms to distinguish tap from drag
        const LONG_PRESS_DURATION = 500; // ms for long press to start drag

        // Initialize drag and drop + click handlers
        function initializeDragAndDrop() {
            const draggableElements = document.querySelectorAll(DRAGGABLE_SELECTOR);

            draggableElements.forEach(element => {
                element.draggable = true;

                // Drag events
                element.addEventListener('dragstart', handleDragStart);
                element.addEventListener('dragend', handleDragEnd);
                element.addEventListener('dragover', handleDragOver);
                element.addEventListener('drop', handleDrop);
                element.addEventListener('dragenter', handleDragEnter);
                element.addEventListener('dragleave', handleDragLeave);

                // Click events
                element.addEventListener('mousedown', handleMouseDown);
                element.addEventListener('mouseup', handleMouseUp);
                element.addEventListener('click', handleClick);
            });
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
                e.preventDefault();
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

            // Delay to allow drag to start properly
            setTimeout(() => {
                isDragging = true;
            }, 50);
        }

        // Handle drag end
        function handleDragEnd(e) {
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
            if (e.stopPropagation) {
                e.stopPropagation();
            }

            if (this !== draggedElement) {
                // Store target data
                const targetData = {
                    grup: this.getAttribute('grup'),
                    position: this.getAttribute('position'),
                    parkingName: this.getAttribute('parking-name'),
                    content: this.innerHTML,
                    classList: Array.from(this.classList)
                };

                // Validate move
                if (validateMove(draggedData, targetData)) {
                    // Perform the swap
                    swapElements(draggedElement, this, draggedData, targetData);
                    showMessage('Perpindahan berhasil!', 'success');
                } else {
                    showMessage('Perpindahan tidak diizinkan!', 'error');
                }
            }

            this.classList.remove('drag-over');
            return false;
        }

        // Modal Functions
        function openModal(seatElement) {
            currentSeat = seatElement;
            const modal = document.getElementById('seatModal');

            // Get seat data
            const grup = seatElement.getAttribute('grup');
            const position = seatElement.getAttribute('position');
            const parkingName = seatElement.getAttribute('parking-name');
            const content = seatElement.innerHTML.trim();

            // Update modal content
            document.getElementById('modalTitle').textContent = `${parkingName} - ${grup}${position}`;
            document.getElementById('modalLocation').textContent = parkingName;
            document.getElementById('modalPosition').textContent = `${grup}-${position}`;
            document.getElementById('modalGrup').textContent = grup;

            // Parse content if vehicle exists
            if (content && content.includes('|') && !seatElement.classList.contains('empty-seat')) {
                const lines = content.split('<br>');
                const firstLine = lines[0].split(' | ');
                const licensePlate = firstLine[0];
                const modelCode = firstLine[1];
                const category = lines[1] || '';

                document.getElementById('licensePlate').value = licensePlate;
                document.getElementById('modelCode').value = modelCode;
                document.getElementById('category').value = category;

                document.getElementById('modalStatus').textContent = 'Terisi';
                document.getElementById('modalStatus').className = 'status-badge status-occupied';
                document.getElementById('clearBtn').style.display = 'block';
            } else {
                // Empty slot
                document.getElementById('licensePlate').value = '';
                document.getElementById('modelCode').value = '';
                document.getElementById('category').value = '';

                document.getElementById('modalStatus').textContent = 'Kosong';
                document.getElementById('modalStatus').className = 'status-badge status-empty';
                document.getElementById('clearBtn').style.display = 'none';
            }

            // Show modal
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
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
                // Update seat with vehicle data
                currentSeat.innerHTML = `${licensePlate} | ${modelCode}<br>${category}`;
                currentSeat.classList.remove('empty-seat');
                showMessage('Data kendaraan berhasil disimpan!', 'success');
            } else if (!licensePlate && !modelCode && !category) {
                // Clear seat
                clearSlot();
                return;
            } else {
                showMessage('Mohon lengkapi semua data kendaraan!', 'error');
                return;
            }

            // Log the change
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
            // Swap content
            const tempContent = draggedEl.innerHTML;
            draggedEl.innerHTML = targetEl.innerHTML;
            targetEl.innerHTML = tempContent;

            // Update classes to reflect new content
            updateElementClasses(draggedEl, targetData.content);
            updateElementClasses(targetEl, draggedData.content);

            // Log the move for potential server sync
            logMove(draggedData, targetData);
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

        // Log move for server synchronization
        function logMove(from, to) {
            const moveData = {
                from: {
                    grup: from.grup,
                    position: from.position,
                    parkingName: from.parkingName
                },
                to: {
                    grup: to.grup,
                    position: to.position,
                    parkingName: to.parkingName
                },
                timestamp: new Date().toISOString()
            };

            console.log('Move logged:', moveData);

            // Here you would typically send this data to your server
            // Example: sendMoveToServer(moveData);
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

        // Example function to send data to server (implement as needed)
        function sendMoveToServer(moveData) {
            // Implement AJAX call to your PHP backend
            /*
            fetch('/api/update-parking-position', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(moveData)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Server response:', data);
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('Gagal menyimpan ke server!', 'error');
            });
            */
        }

        function sendSlotUpdateToServer(updateData) {
            // Implement AJAX call to your PHP backend
            /*
            fetch('/api/update-slot', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(updateData)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Slot update response:', data);
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('Gagal menyimpan ke server!', 'error');
            });
            */
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initializeDragAndDrop();
            console.log('Parking system initialized with click and drag functionality');
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
    </script>
</body>

</html>