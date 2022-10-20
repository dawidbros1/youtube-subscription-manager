function initCloseAlertButtons() {
    var buttons = document.getElementsByClassName('close-button');

    for (let i = 0; i < buttons.length; i++) {
        buttons[i].addEventListener('click', () => {
            alert = document.getElementsByClassName('message-alert')[i];
            alert.classList.add('d-none');
        })
    }
}
