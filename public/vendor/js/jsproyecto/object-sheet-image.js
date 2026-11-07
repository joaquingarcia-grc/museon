const dropzone = document.querySelector('.object-sheet-image-dropzone');
const imageInput = document.querySelector('#imagenObjeto');

if (dropzone && imageInput) {
    const preview = document.querySelector('#vistaPreviaImagen');
    const placeholder = document.querySelector('#indicacionImagen');
    let previewUrl = null;

    const showPreview = (file) => {
        if (!file || !file.type.startsWith('image/')) {
            return;
        }

        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
        }
        previewUrl = URL.createObjectURL(file);
        preview.src = previewUrl;
        preview.hidden = false;
        placeholder.hidden = true;
    };

    imageInput.addEventListener('change', () => showPreview(imageInput.files[0]));
    dropzone.addEventListener('dragover', (event) => {
        event.preventDefault();
        dropzone.classList.add('is-dragging');
    });
    dropzone.addEventListener('dragleave', () => dropzone.classList.remove('is-dragging'));
    dropzone.addEventListener('drop', (event) => {
        event.preventDefault();
        dropzone.classList.remove('is-dragging');

        const [file] = event.dataTransfer.files;
        if (!file) {
            return;
        }

        const transfer = new DataTransfer();
        transfer.items.add(file);
        imageInput.files = transfer.files;
        showPreview(file);
    });
}
