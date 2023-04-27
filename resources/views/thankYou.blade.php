<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
</head>

<body>

    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="#">Interview Process</a>


    </header>
    <div class="container-fluid mt-3">

        <main class="card">





            <div class=" card-body row m-0">

                {{-- <div class=" col-6 ">
                    <div class="card shadow">
                        <div class="bg-primary card-header h6 text-white ">
                            Candidate Details
                        </div>
                        <div class="card-body">
                            <div class="d-flex">
                                <label class="card-title  h6 "> Name : </label>
                                <span class="h6">{{ $interviews->Candidate->name }}</span>
                            </div>
                            <hr class="my-2" />
                            <div class="row p-0 ">
                                <div class="co-lg-6 col-md-6 col-sm-12">
                                    <label for="email" class="card-title  h6 ">Email Id : </label>
                                    <span class="h6">{{ $interviews->Candidate->email }}</span>
                                </div>
                                <div class="co-lg-6 col-md-6 col-sm-12">
                                    <label for="mobile" class="card-title  h6 ">Contact Number :
                                    </label>
                                    <span class="h6">{{ $interviews->Candidate->contect_no }}</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div> --}}

                <br />

                {{-- <div class=" col-6 ">
                    <div class="card shadow">
                        <div class="bg-primary card-header h6 text-white ">
                            Interview Details
                        </div>
                        <div class="card-body">
                            <div class="row p-0 ">
                                <div class="co-lg-6 col-md-6 col-sm-12 mb-3">
                                    <label for="interviewer" class="card-title  h6 ">Interviewer : </label>
                                    <span class="h6">{{ $interviews->Interviewer_id->name }}</span>

                                </div>
                                <div class="co-lg-6 col-md-6 col-sm-12">
                                    <label for="date" class="card-title  h6 ">Date & time : </label>
                                    <span class="h6">{{ $interviews->date }}</span>

                                </div>
                                <div class="co-lg-6 col-md-6 col-sm-12">
                                    <label for="type" class="card-title  h6 ">Type : </label>
                                    <span class="h6">{{ $interviews->interview_type->interview_type }}</span>

                                </div>

                                <div class="co-lg-6 col-md-6 col-sm-12">
                                    <label for="mode" class="card-title  h6 ">Mode : </label>
                                    <span class="h6">{{ $interviews->Interview_mode->interview_mode }}</span>

                                    (<span>{{ $interviews->location_link }}</span>)
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}

                <hr />
                <br />
                <div class=" col-12  my-3 ">
                    <div class="card shadow">
                        <div class="bg-primary card-header h6 text-white ">
                            Interview Status
                        </div>
                        <div class="card-body">
                            <div class="row px-2 ">
                                <h5> Review Submitted. </h5>
                            </div>
                        </div>
                    </div>
                </div>


        </main>

    </div>
</body>

</html>
