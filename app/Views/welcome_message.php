<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- JQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <!-- JQuery -->

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap -->

    <!-- SWAL -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-material-ui/material-ui.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <!-- SWAL -->

    <title>Laboratory Exam</title>
</head>
<body>
    <div class="container mt-5">
      <h1 class='text-center'>Museum</h1>
        <div class="row mt-5">
            <div class="col-md-12 d-flex justify-content-center">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        Select Object ID
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="dropdownMenu">
                        <li><a class="dropdown-item" href="#" data-id="0">Select Object ID</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row my-4">
            <div class="col-md-12 d-flex flex-column justify-content-center align-items-center">
                <h3 class='text-center mb-4'>Object Details</h3>
                <div id="objectDetails" class="card" style="width: 20rem;">
                  <img id="objectImage" class="img-fluid p-2">
                    <div class="card-body">
                      <h5 id="objectTitle" class="card-title"></h5>
                      <p id="objectArtist" class="card-text"></p>
                      <p id="objectDepartment" class="card-text"></p>
                      <p id="objectDate" class="card-text"></p>
                      <p id="objectMedium" class="card-text"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Populate the dropdown with IDs from the API
            const searchApiUrl = 'https://collectionapi.metmuseum.org/public/collection/v1/search?q=Ukhhotep';

            $.getJSON(searchApiUrl, function(data) {
                if (data && data.objectIDs) {
                    const objectIDs = data.objectIDs;
                    const $dropdownMenu = $('#dropdownMenu');

                    // Iterate over the IDs and append to the dropdown menu
                    objectIDs.forEach(id => {
                        $dropdownMenu.append(`<li><a class="dropdown-item" href="#" data-id="${id}">${id}</a></li>`);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No object IDs found.'
                    });
                }
            }).fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch data from the API.'
                });
            });

            // Handle click event on the dropdown items
            $('#dropdownMenu').on('click', '.dropdown-item', function() {
                const selectedID = $(this).data('id');
                $('#dropdownMenuButton').text(`Selected ID: ${selectedID}`);

                if (selectedID && selectedID !== 0) {
                    const objectApiUrl = `https://collectionapi.metmuseum.org/public/collection/v1/objects/${selectedID}`;

                    // Make an AJAX request to the API with the selected ID
                    $.getJSON(objectApiUrl, function(data) {
                        if (data) {
                            // Populate the HTML elements with the fetched data
                            $('#objectTitle').text(data.title || 'N/A');
                            $('#objectArtist').text('Artist: ' + (data.artistDisplayName || 'N/A'));
                            $('#objectDate').text('Date: ' + (data.objectDate || 'N/A'));
                            $('#objectMedium').text('Medium: ' + (data.medium || 'N/A'));
                            $('#objectDepartment').text('Department: ' + (data.department || 'N/A'));
                            $('#objectImage').attr('src', data.primaryImageSmall || 'https://via.placeholder.com/300x300?text=No+Image+Available');
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'No data found for the selected object.'
                            });
                        }
                    }).fail(function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to fetch data from the API.'
                        });
                    });
                }else{
                  $('#objectTitle').text('');
                    $('#objectArtist').text('');
                    $('#objectDate').text('');
                    $('#objectMedium').text('');
                    $('#objectDepartment').text('');
                    $('#objectImage').attr('src', '');
                }
            });
        });
    </script>
</body>
</html>
