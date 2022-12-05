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

// Method is use in view category/list
function initToggleList() {
    var handles = document.getElementsByClassName("list-handle");
    var wrappers = document.getElementsByClassName("list-wrapper")

    for (let i = 0; i < handles.length; i++) {
        let handle = handles[i];

        handle.addEventListener('click', () => {
            for (let j = 0; j < wrappers.length; j++) {
                wrappers[j].classList.add('d-none');
            }

            wrappers[i].classList.remove('d-none');
        })
    }

    var address = window.location.href;
    var array = address.split('#');

    if (array.length > 1 && array[1] == "notCategorized") handles[1].click()
}