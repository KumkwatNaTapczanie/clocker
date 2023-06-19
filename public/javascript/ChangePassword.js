function changeOnClick(ID) {
    let passwordChanging = document.getElementById(ID);
    let id = passwordChanging.id;
    let newPassword = document.getElementById('password').value;
    let repeatedNewPassword = document.getElementById('repeat_password').value;
    if (newPassword === repeatedNewPassword) {
        alert([id, newPassword])
        let message = new FormData();
        message.append('id', id);
        message.append('password', newPassword);
        fetch('index.php?action=change-password', {
            method: 'POST',
            mode: "same-origin",
            credentials: "same-origin",
            body: message
        }).then((r) => {
            window.location.replace('index.php?action=show-profile');
        });
    }
}