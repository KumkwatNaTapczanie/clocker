function editOnFocusOut(ID) {
    let modifiedRow = document.getElementById(ID);
    let id = modifiedRow.id;
    let title = modifiedRow.getElementsByClassName('title_tsk')[0].innerText;
    let project = modifiedRow.getElementsByClassName('project_tsk')[0].value;
    let start = modifiedRow.getElementsByClassName('start_tsk')[0].value;
    let stop = modifiedRow.getElementsByClassName('stop_tsk')[0];
    if (stop) {
        stop = stop.value;
    } else {
        stop = '';
    }
    let message = new FormData();
    message.append('id', id);
    message.append('title', title);
    message.append('project', project);
    message.append('start', start);
    message.append('stop', stop);
    fetch('index.php?action=edit-task', {
        method: 'POST',
        mode: "same-origin",
        credentials: "same-origin",
        body: message
    }).then((r) => {
        window.location.reload();
    });
}


function deleteOnClick(id) {
    let confirmation = confirm("Do you really want to delete this task?");
    if (confirmation) {
        let message = new FormData();
        message.append('id', id);
        fetch('index.php?action=delete-task', {
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