function editOnFocusOut(ID) {
    let modifiedRow = document.getElementById(ID);
    let id = modifiedRow.id;
    let projectName = modifiedRow.getElementsByClassName('project_prj')[0].innerText;
    let client = modifiedRow.getElementsByClassName('client_prj')[0].value;
    let wage = modifiedRow.getElementsByClassName('wage_prj')[0].innerText;
    let message = new FormData();
    message.append('id', id);
    message.append('projectName', projectName);
    message.append('client', client),
    message.append('wage', wage);
    fetch('index.php?action=edit-project', {
        method: 'POST',
        mode: "same-origin",
        credentials: "same-origin",
        body: message
    }).then((r) => {
        window.location.reload();
    });
}


function deleteOnClick(id) {
    let confirmation = confirm("Do you really want to delete this project?");
    if (confirmation) {
        let message = new FormData();
        message.append('id', id);
        fetch('index.php?action=delete-project', {
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