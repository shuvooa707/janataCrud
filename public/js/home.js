$(document).ready(function () {

    table = $('#table').DataTable();

    $('#example tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }
        else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });

    // defining row Delete function
    deleteRow = function(tr) {
        // removing row from database
        console.log(tr);
        let id = tr.dataset.id;
        let _token = document.querySelector("input[name='_token']").value;
        let payload = new FormData();
        payload.append("_token", _token);
        payload.append("id", id);

        (async () => {
            await fetch("/remove", {
                method: "POST",
                body: payload
            })
            .then(r => r.json())
            .then(r => {
                if (r.msg == "success") {
                    // removing row from table
                    table.row(tr).remove().draw(false);
                }
            });
        })();

    }
});




function edit(tr) {
    console.log(tr);
    let form = document.querySelector("#editModal");
    form.querySelector("#edit_date").value = tr.querySelector("td:nth-child(2)").innerText;
    form.querySelector("#edit_trade_code").value = tr.querySelector("td:nth-child(3)").innerText;
    form.querySelector("#edit_high").value = tr.querySelector("td:nth-child(4)").innerText;
    form.querySelector("#edit_low").value = tr.querySelector("td:nth-child(5)").innerText;
    form.querySelector("#edit_open").value = tr.querySelector("td:nth-child(6)").innerText;
    form.querySelector("#edit_close").value = tr.querySelector("td:nth-child(7)").innerText;
    form.querySelector("#edit_volume").value = tr.querySelector("td:nth-child(8)").innerText;
    form.querySelector("input[name='id']").value = tr.dataset.id;
    $('#editModal').modal('show');
    update = () => {
        let id = document.querySelector("input[name='id']").value;
        let trade = form.querySelector("#edit_trade_code").value;
        let date = form.querySelector("#edit_date").value;
        let high = form.querySelector("#edit_high").value;
        let low = form.querySelector("#edit_low").value;
        let open = form.querySelector("#edit_open").value;
        let close = form.querySelector("#edit_close").value;
        let volume = form.querySelector("#edit_volume").value;
        let _token = document.querySelector("input[name='_token']").value;

        let payload = new FormData();
        payload.append("id", id);
        payload.append("trade_code", trade);
        payload.append("date", date);
        payload.append("high", high);
        payload.append("low", low);
        payload.append("open", open);
        payload.append("close", close);
        payload.append("volume", volume);
        payload.append("_token", _token);

        (async () => {
            await fetch("/update",{
                method : "POST",
                body : payload
            })
            .then(r => r.json())
            .then(r => {
                if ( r.msg == "success" ) {

                    tr.querySelector("td:nth-child(2)").innerText = date;
                    tr.querySelector("td:nth-child(3)").innerText = trade;
                    tr.querySelector("td:nth-child(4)").innerText = high;
                    tr.querySelector("td:nth-child(5)").innerText = low;
                    tr.querySelector("td:nth-child(6)").innerText = open;
                    tr.querySelector("td:nth-child(7)").innerText = close;
                    tr.querySelector("td:nth-child(8)").innerText = volume;

                    $('#editModal').modal('hide');
                } else {

                }
            });
        })();
    }
}



function addNew() {

    $('#addNewModal').modal('show');

    createNew = form => {
        let trade = form.querySelector("#trade_code").value;
        let date = form.querySelector("#date").value;
        let high = form.querySelector("#high").value;
        let low = form.querySelector("#low").value;
        let open = form.querySelector("#open").value;
        let close = form.querySelector("#close").value;
        let volume = form.querySelector("#volume").value;
        let _token = document.querySelector("input[name='_token']").value;

        let payload = new FormData();
        payload.append("trade_code", trade);
        payload.append("date", date);
        payload.append("high", high);
        payload.append("low", low);
        payload.append("open", open);
        payload.append("close", close);
        payload.append("volume", volume);
        payload.append("_token", _token);
        overlay.classList.remove("hide");

        (async () => {
            await fetch("/create", {
                method: "POST",
                body: payload
            })
                .then(r => r.json())
                .then(r => {
                    if (r.msg == "success") {
                        var table = $('#table').DataTable();
                        table.row.add([
                            r.last,
                            date,
                            trade,
                            high,
                            low,
                            open,
                            close,
                            volume,
                            `<button class="btn btn-success" onclick="edit(this.parentElement.parentElement)">
                                    Edit
                                </button >
                            <button class="btn btn-danger" onclick="deleteRow(this.parentElement.parentElement)">
                                Delete
                            </button>`
                        ]).draw(false);
                        let overlay = document.querySelector("#addNewModal .overlay");
                        overlay.classList.add("hide");
                        $('#addNewModal').modal('hide');
                    } else {

                    }
                });
        })();
    }
}




let data = {
    labels: [...labels],
    datasets: [...datasets]
};

let dataBar = {
    labels: [...labels],
    datasets: [...datasets]
};

let configLine = {
    type: 'line',
    data,
    options: {
        plugins: {
            legend: {
                display: false
            }
        }
    }
};

let configBar = {
    type: 'bar',
    dataBar,
    options: {
        plugins: {
            legend: {
                display: false
            }
        },
        responsive: true
    }
};

console.log(data);
console.log(dataBar);

var myChartLine = new Chart(
        document.getElementById('myChart-line'),
        configLine
);


var myChartBar = new Chart(
        document.getElementById('myChart-bar'),
        configBar
);



function redrawLineChart ( trade_code ) {
    configLine.data.datasets = [allDatasets.find( e => {
        if( e.label == trade_code ) {
            return e;
        }
        return false;
    })];

    configBar.data.datasets = [allDatasets.find( e => {
        if( e.label == trade_code ) {
            return e;
        }
        return false;
    })];


    $('#myChart-line').replaceWith('<canvas id="myChart-line"></canvas>');
    new Chart(
        document.getElementById('myChart-line'),
        configLine
    );

    $('#myChart-bar').replaceWith('<canvas id="myChart-bar"></canvas>');
    new Chart(
        document.getElementById('myChart-bar'),
        configBar
    );
}



//
