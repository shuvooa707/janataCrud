<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Janata Crud</title>

    {{-- data table --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- charts.js integration --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- /charts.js integration --}}

    <script src="{{ asset('js/app.js') }}"></script>



    <!-- Latest compiled and minified CSS -->
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> --}}


</head>
<body>

    {{-- navbar --}}
    <nav class="navbar navbar-expand-lg navbar-light" style="background: indigo; color:white;">
        <a class="navbar-brand text-light" >Janata Crud</a>
    </nav>
    {{-- /navbar --}}

    {{-- stats --}}
    <div class="container-fluid my-5">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-6 my-5">
                <div class="form-group">
                    <label for="trade_code" style="">Select Trade Code : </label>
                    <select onchange="redrawLineChart(this.value)" id="trade_code" class="form-control" style="max-height: 480px; overflow-y:auto;" aria-labelledby="dropdownMenuLink">
                        @foreach ($clusters as $cluster)
                        <option value="{{ $cluster["trade_code"] }}" class="m" href="#">{{ $cluster["trade_code"] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-lg-8">
                <div>
                    <canvas id="myChart-line"></canvas>
                </div>
            </div>

            <div class="col-lg-8 mt-5">
                <div>
                    <canvas id="myChart-bar"></canvas>
                </div>
            </div>

        </div>
    </div>
    {{-- /stats --}}

    <div class="container-fluid  bg-light">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-10 mt-5 d-flex justify-content-center">
                {{-- <button class="btn btn-info bg-danger px-5 text-light" onclick="addNew()">
                    Create New
                </button> --}}
            </div>
            <div class="col-lg-10 mt-2">
                <table class=" text-center table table-bordered table-striped" id="table">
                    <thead class=" bg-danger text-light" style="background: #a92112; color:white;">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Trade Code</th>
                            <th>High</th>
                            <th>Low</th>
                            <th>Open</th>
                            <th>Close</th>
                            <th>Volume</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $row)
                        <tr data-id="">
                            <td>{{ $loop->index }}</td>
                            <td>{{ $row->date }}</td>
                            <td>{{ $row->trade_code }}</td>
                            <td>{{ $row->high }}</td>
                            <td>{{ $row->low }}</td>
                            <td>{{ $row->open }}</td>
                            <td>{{ $row->close }}</td>
                            <td>{{ $row->volume }}</td>
                            <td>
                                <button class="btn btn-success" onclick="edit(this.parentElement.parentElement)">
                                    Edit
                                </button>
                                <button class="btn btn-danger" onclick="deleteRow(this.parentElement.parentElement)">
                                    Delete
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="modal mt-3" tabindex="-1" role="dialog" id="addNewModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="min-width: 580px">
                @csrf
                <div class="modal-header bg-danger text-light">
                    <h5 class="modal-title" id="exampleModalLongTitle">Create new</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="trade_code">Trade Code</label>
                            <input type="text" id="trade_code" class="form-control">
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="date">Date</label>
                            <input type="date" id="date" class="form-control">
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="high">High</label>
                            <input type="text" id="high" class="form-control">
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="low">Low</label>
                            <input type="text" id="low" class="form-control">
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="open">Open</label>
                            <input type="text" id="open" class="form-control">
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="close">Close</label>
                            <input type="text" id="close" class="form-control">
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="volume">Volume</label>
                            <input type="text" id="volume" class="form-control">
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <div class="btn btn-danger col-lg-12" onclick="createNew(this.parentElement.parentElement)">Create</div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="modal mt-3" tabindex="-1" role="dialog" id="editModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="min-width: 580px">

                <div class="modal-header bg-success text-light">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Entry</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="edit_trade_code">Trade Code</label>
                            <input type="text" id="edit_trade_code" class="form-control">
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="edit_date">Date</label>
                            <input type="date" id="edit_date" class="form-control">
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="edit_high">High</label>
                            <input type="text" id="edit_high" class="form-control">
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="edit_low">Low</label>
                            <input type="text" id="edit_low" class="form-control">
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="edit_open">Open</label>
                            <input type="text" id="edit_open" class="form-control">
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="edit_close">Close</label>
                            <input type="text" id="edit_close" class="form-control">
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="edit_volume">Volume</label>
                            <input type="text" id="edit_volume" class="form-control">
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <div class="btn btn-info text-light col-lg-12" onclick="edit(this.parentElement.parentElement)">Update</div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>


    {{-- Graph Data --}}
    <script>

        function getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        let labels = [
            @php
            $dates = $clusters->first()["date"];
            sort($dates);
            @endphp
            @foreach ( $dates as $date)
            "{{ $date }}",
            @endforeach
        ];

        let allDatasets = [
            @foreach ($clusters as $cluster)
            {
                label: '{{ $cluster["trade_code"] }}',
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: `${getRandomColor()}`,
                data: [{{ implode(",",$cluster["close"]) }}],
            },
            @endforeach
        ];

        let datasets = allDatasets.slice(0,1);
    </script>
    {{-- /Graph Data --}}

    <script src="{{ asset('js/home.js') }}" defer></script>

</body>
</html>
