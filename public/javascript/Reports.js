//new records go into this table
const table = document.getElementById('task');

const namesMapDetailed = {
    'title' : 'Title',
    'projectName' : 'Project name',
    'clientName' : 'Client name',
    'wage' : 'Wage',
    'startTime' : 'Started',
    'stopTime' : 'Ended',
    'totalTime' : 'Duration',
    'totalPayout' : 'Payout'
};

const namesMapAggregated = {
    'projectName' : 'Project name',
    'clientName' : 'Client name',
    'wage' : 'Wage',
    'startTime' : 'Started',
    'stopTime' : 'Ended',
    'totalTime' : 'Total time',
    'totalPayout' : 'Total payout'
};

function getResults() {
    const form = new FormData(document.getElementById('form'));
    fetch('index.php?action=reports-filter', {
        method: 'POST',
        mode: "same-origin",
        credentials: "same-origin",
        body: form
    }).then(response => response.text())
        .then(text => {
            try {
                const data = JSON.parse(text);
                fillTable(data);
                check();
            } catch(err) {}
        });
}

		//jeszcze nad tym pracuje
function check()
{
    let x= document.getElementById("task").rows.length-1;
    console.log("found " + x + " elements");

    console.log(table);
}

function fillTable(data)
{
    table.innerHTML = '';
    let keys = Object.keys(data[0]);
    let map = keys.includes('title') ? namesMapDetailed : namesMapAggregated;
    let thead = document.createElement('thead');
    let tr = document.createElement('tr');

    for (let key of keys)
    {
        let th = document.createElement('th');
        th.innerText = map[key];
        tr.appendChild(th);
    }
    thead.appendChild(tr);

    let tbody = document.createElement('tbody');

    for (let row of data)
    {
    let tr = document.createElement('tr');

    for (let key in row)
        {
        let td = document.createElement('td');
        td.innerText = row[key];
        tr.appendChild(td);

        tr.appendChild(td);
        }

    tbody.appendChild(tr);
    }
table.appendChild(thead);

table.appendChild(tbody);
}
