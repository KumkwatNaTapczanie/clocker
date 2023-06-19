function editOnFocusOut(ID) {
    let modifiedRow = document.getElementById(ID);
    let id = modifiedRow.id;
    let clientName = modifiedRow.getElementsByClassName('client_cln')[0].innerText;
    let message = new FormData();
    message.append('id', id);
    message.append('clientName', clientName);
    fetch('index.php?action=edit-client', {
        method: 'POST',
        mode: "same-origin",
        credentials: "same-origin",
        body: message
    }).then((r) => {
        window.location.reload();
    });
}


function deleteOnClick(id) {
    let confirmation = confirm("Do you really want to delete this client?");
    if (confirmation) {
        let message = new FormData();
        message.append('id', id);
        fetch('index.php?action=delete-client', {
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