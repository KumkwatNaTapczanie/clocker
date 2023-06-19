function editOnFocusOut(ID) {
    let modifiedRow = document.getElementById(ID);
    let id = modifiedRow.id;
    let username = modifiedRow.getElementsByClassName('user_usr')[0].innerText;
    let email = modifiedRow.getElementsByClassName('email_usr')[0].innerText;
    let select = modifiedRow.getElementsByClassName('role_select')[0];
    let role = select.options[select.selectedIndex].text;
    let message = new FormData();
    message.append('id', id);
    message.append('username', username);
    message.append('email', email);
    message.append('role', role);
    fetch('index.php?action=edit-user-except-password', {
        method: 'POST',
        mode: "same-origin",
        credentials: "same-origin",
        body: message
    }).then((r) => {
        window.location.reload();
    });
}

/*function changePassword(id) {

}*/

function deleteOnClick(id) {
    let confirmation = confirm("Do you really want to delete this user?");
    if (confirmation) {
        let message = new FormData();
        message.append('id', id);
        fetch('index.php?action=delete-user', {
            method: 'POST',
            mode: "same-origin",
            credentials: "same-origin",
            body: message
        }).then((r) => {
            window.location.reload();
        });
    } else {
        alert("Deleting cancelled");
    }
}