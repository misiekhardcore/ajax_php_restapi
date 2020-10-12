<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <title>Document</title>
</head>

<body>
    <div class="container">
        <h3 class="text-center">CRUD using php and ajax</h3>
        <div class="text-right mb-3">
            <button class="btn btn-xs btn-success" name='add_button' type='button' id='add_button'>Add</button>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>First name</th>
                        <th>Last name</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <div class="apicrudModal modal fade" role='dialog' id="apicrudModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" id='api_crud_form'>
                    <div class="modal-header">
                        <h4 class="modal-title">Add Data</h4>
                        <button type="button" class='close' data-dismiss='modal'>&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Enter First Name</label>
                            <input type="text" name='first_name' id="first_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Enter Last Name</label>
                            <input type="text" name='last_name' id="last_name" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="hidden_id" id="hidden_id">
                        <input type="hidden" name="action" id="action" value="insert">
                        <input type="submit" name="button_action" id="button_action" class="btn btn-info" value="Insert">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <p><tt id="results"></tt></p>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <script>
        $(document).ready(_ => {

            fetch_data()

            function fetch_data() {
                $.ajax({
                    url: 'fetch.php',
                    success: function(data) {
                        $('tbody').html(data)
                    }
                })
            }

            $('#add_button').click(_ => {
                $('#action').val('insert')
                $('#button_action').val('Insert')
                $('.modal-title').text('Add Data')
                $('#apicrudModal').modal('show')
            })

            $('#api_crud_form').on('submit', function(event) {
                event.preventDefault()

                if ($('#first_name').val() == '') {
                    alert('Enter first name!')
                } else if ($('#last_name').val() == '') {
                    alert('Enter last name')
                } else {
                    let formData = $(this).serialize()
                    $.ajax({
                        url: 'action.php',
                        method: 'POST',
                        data: formData,
                        success: function(data) {
                            fetch_data()
                            $('#api_crud_form')[0].reset()
                            $('#apicrudModal').modal('hide')
                            if (data == 'insert') {
                                alert("Data inserted using PHP API")
                            }
                            if (data == 'update') {
                                alert("Data updated using PHP API")
                            }
                            if (data == 'error') {
                                alert("There was an error")
                            }
                        }
                    })
                }
            })

            $(document).on('click', '.edit', function() {
                let id = $(this).attr('id')
                let action = 'fetch_single'
                $.ajax({
                    url: 'action.php',
                    method: 'post',
                    data: {
                        id: id,
                        action: action
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#hidden_id').val(id)
                        $('#first_name').val(data.first_name)
                        $('#last_name').val(data.last_name)
                        $('#action').val('update')
                        $('#button_action').val('Update')
                        $('.modal-title').text('Edit Data')
                        $('#apicrudModal').modal('show')
                    }
                })
            })

            $(document).on('click', '.delete', function() {
                let id = $(this).attr('id')
                let action = 'delete'
                if (confirm('Are you sure you want to delete?')) {
                    $.ajax({
                        url: 'action.php',
                        method: 'post',
                        data: {
                            id: id,
                            action: action
                        },
                        dataType: 'json',
                        success: function(data) {
                            fetch_data()
                            alert("Deleted successfully")
                        }
                    })
                }
            })
        })
    </script>

</body>

</html>