function initCloseAlertButtons() {
    var buttons = document.getElementsByClassName('close-button');

    for (let i = 0; i < buttons.length; i++) {
        buttons[i].addEventListener('click', () => {
            alert = document.getElementsByClassName('message-alert')[i];
            alert.classList.add('d-none');
        })
    }
}

function initToggleForm(type) {
    var wrappers = document.getElementsByClassName(`${type}-form-wrapper`);
    var handles = document.getElementsByClassName(`${type}-form-handle`);
    var buttonsCancel = document.querySelectorAll(`.${type}-form-wrapper .cancel`);

    for (let i = 0; i < handles.length; i++) {
        let wrapper = wrappers[i];
        let handle = handles[i];
        let cancel = buttonsCancel[i];

        handle.addEventListener('click', () => {
            hideWrappers(wrappers);
            wrapper.classList.remove('d-none');
        })

        cancel.addEventListener('click', () => hideWrappers(wrappers));
    }
}

function hideWrappers(wrappers) {
    for (var i = 0; i < wrappers.length; i++) {
        wrappers[i].classList.add('d-none');
    }
}