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



            <form method="post" action="/reviewsubmit">
                @method('post')
                @csrf

                <div class="bg-light card-header h6">
                    <span class=" mb-0 mt-2">Interview Details</span>


                </div>

                <div class=" card-body row m-0">
                    <input type="hidden" name="candidateId" value="{{ $interviews->Candidate->id }}" />
                    <input type="hidden" name="interviewId" value="{{ $interviews->id }}" />
                    <br />

                    <div class=" col-6 ">
                        <div class="card shadow">
                            <div class="bg-primary card-header h6 text-white ">
                                Candidate Details
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                        <label class="card-title  h6 "> Name : </label>
                                        <span class="h6">{{ $interviews->Candidate->name }}</span>
                                    </div>

                                    <a href={{ $interviews->Candidate->resume_id }} class='btn btn-primary btn-sm'
                                        target='blanck'> Resume </a>
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
                    </div>

                    <br />

                    <div class=" col-6 ">
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
                    </div>

                    <hr />

                    <div class=" col-8 my-3">
                        <div class="card shadow ">
                            <div class="bg-primary card-header h6 text-white ">
                                Skills
                            </div>
                            <div class="card-body">

                                <label for="type" class="card-title  ">Total Interview Points : </label>
                                <input type="number" min="0" name="total_rating" class="form-control "
                                    placeholder="Points " value="{{ old('total_rating') }}" step="any"
                                    min="1" max="10" />
                                @error('total_rating')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <br />


                                <table class="table">
                                    <thead>
                                        <th>Skill</th>
                                        <th>Self Rating</th>
                                        <th>Theory Rating</th>
                                        <th>Practical Ratings</th>
                                    </thead>

                                    <tbody>
                                        @if (isset($skills))
                                            @foreach ($skills as $skill)
                                                <tr>
                                                    <td>{{ $skill->skill }}</td>
                                                    <td>
                                                        @if ($skill->self_rating)
                                                            {{-- {{ $skill->self_rating }} --}}
                                                            {{ 'Already filled' }}
                                                        @else
                                                            <input type="number" class="form-control "
                                                                name={{ 'self_rating[' . $skill->id . ']' }} />
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($skill->theory_rating)
                                                            {{-- {{ $skill->theory_rating }} --}}
                                                            {{ 'Already filled' }}
                                                        @else
                                                            <input type="number" class="form-control "
                                                                name={{ 'theory_rating[' . $skill->id . ']' }} />
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($skill->practical_rating)
                                                            {{-- {{ $skill->practical_rating }} --}}
                                                            {{ 'Already filled' }}
                                                        @else
                                                            <input type="number" class="form-control "
                                                                name={{ 'practical_rating[' . $skill->id . ']' }} />
                                                        @endif
                                                    </td>

                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>

                    <div class="col-4 d-flex flex-column justify-content-between my-3 ">
                        <div class="card shadow ">
                            <div class="bg-primary card-header h6 text-white ">
                                Remarks
                            </div>
                            <div class="card-body">

                                {{ $interviews->remarks }}

                            </div>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>


            </form>

        </main>

    </div>
</body>

</html>
