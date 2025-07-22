let scale = 1;
const target = document.getElementById('main-area');

function applyZoom() {
    target.style.transform = `scale(${scale})`;
    target.style.transformOrigin = 'top left';

    const basePaddingTop = 100;
    const basePaddingLeft = 100;
    const adjustedPaddingTop = basePaddingTop * (scale - 1);
    const adjustedPaddingLeft = basePaddingLeft * (scale - 1);

    target.style.paddingTop = `${adjustedPaddingTop}px`;
    target.style.paddingLeft = `${adjustedPaddingLeft}px`;
}

function zoomIn() {
    scale += 0.1;
    applyZoom();
}

function zoomOut() {
    scale = Math.max(0.1, scale - 0.1);
    applyZoom();
}

function resetZoom() {
    scale = 1;
    applyZoom();
}