function initCloseAlertButtons() {
    var buttons = document.getElementsByClassName('close-button');

    for (let i = 0; i < buttons.length; i++) {
        buttons[i].addEventListener('click', () => {
            alert = document.getElementsByClassName('message-alert')[i];
            alert.classList.add('d-none');
        })
    }
}

function initToggleCategoryDeleteForm() {
    var wrapper = document.getElementById("category-delete-form-wrapper");
    var cancel = document.getElementById('cancel');

    cancel.addEventListener('click', () => {
        wrapper.classList.add('d-none')
    })

    var category_name = document.getElementById("category-name");
    var handles = document.getElementsByClassName('delete-form-handle');
    var form = document.getElementById('category-delete-form');

    for (let i = 0; i < handles.length; i++) {
        let handle = handles[i];
        handle.addEventListener('click', () => {
            wrapper.classList.remove('d-none');
            category_name.innerHTML = handle.dataset.name;
            form.setAttribute('action', handle.dataset.action);
        })
    }
}