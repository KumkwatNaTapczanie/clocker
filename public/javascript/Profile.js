function editOnFocusOut(ID) {
    let modifiedRow = document.getElementById(ID);
    let id = modifiedRow.id;
    let username = modifiedRow.getElementsByClassName('user_prfl')[0].innerText;
    let email = modifiedRow.getElementsByClassName('email_prfl')[0].innerText;
    let role = modifiedRow.getElementsByClassName('role_prfl')[0].innerText;
    let message = new FormData();
    message.append('id', id);
    message.append('username', username);
    message.append('email', email);
    message.append('role', role);
    fetch('index.php?action=edit-profile-except-password', {
        method: 'POST',
        mode: "same-origin",
        credentials: "same-origin",
        body: message
    }).then((r) => {
        window.location.reload();
    });
}