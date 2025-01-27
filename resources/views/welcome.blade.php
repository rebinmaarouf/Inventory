<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Inventory - Login</title>

    <!-- Load Vite assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom fonts -->
    {{-- <link href="{{ asset('backend/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css"> --}}
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles -->
    <link href="{{ asset('backend/css/sb-admin-2.min.css') }}" rel="stylesheet">
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Vue App Mount Point -->
                    <div id="app">
                        <router-view></router-view>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('backend/vendor/jquery/jquery.min.js') }}"></script>

    <!-- Bootstrap JS -->
    <script src="{{ asset('backend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript -->
    <script src="{{ asset('backend/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Logout Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('logoutLink')?.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default link behavior

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will be logged out!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, log out!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        const token = localStorage.getItem(
                            'access_token'); // Get the token from local storage

                        // Validate token format
                        if (!token || token.split('.').length !== 3) {
                            Swal.fire('Error', 'Invalid token. Please log in again.', 'error').then(
                                () => {
                                    localStorage.removeItem(
                                        'access_token'); // Clear invalid token
                                    window.location.href = '/'; // Redirect to login page
                                });
                            return;
                        }

                        // Show loading indicator
                        Swal.fire({
                            title: 'Logging out...',
                            text: 'Please wait while we log you out.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                        });

                        // Send logout request
                        axios.post('http://localhost:8000/api/auth/logout', {}, {
                                headers: {
                                    Authorization: `Bearer ${token}`,
                                },
                            })
                            .then(() => {
                                // Clear user data from localStorage
                                localStorage.removeItem('access_token');
                                localStorage.removeItem(
                                    'user'); // Clear additional user data if stored

                                // Show success message and redirect after the dialog is closed
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Logged out successfully!',
                                    text: 'You will be redirected to the login page.',
                                }).then(() => {
                                    window.location.href =
                                        '/'; // Redirect to login page
                                });
                            })
                            .catch((error) => {
                                console.error('Logout failed:', error);
                                let errorMessage = 'Logout failed. Please try again.';
                                if (error.response && error.response.data && error.response.data
                                    .message) {
                                    errorMessage = error.response.data.message;
                                }

                                // Show error message and redirect after the dialog is closed
                                Swal.fire('Error', errorMessage, 'error').then(() => {
                                    window.location.href =
                                        '/'; // Redirect to login page
                                });
                            });
                    }
                });
            });
        });
    </script>
</body>

</html>
