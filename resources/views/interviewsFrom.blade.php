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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
</head>
<style>
.head1{
    font-size:20px;
    font-weight:bold;
}
.head2{
    font-size:16px;
    font-weight:bold
}
.text1{
    font-size: 16px;
}
.border-bottom{
    padding:0.5rem 0;
}

.card-data{
    margin-right:0.5rem
}
.card{
    box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;    
    border-radius: 0.5rem
}
@media screen and (max-width: 991px) {
  .second-div {
    margin-top: 3rem;
  }
}
/* .main{
   background: #e9eaed 
} */
</style>

<body >

    <!-- <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
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
                        <div class="card shadow">
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

    </div> -->
    <div class="main">
        <header class = "navbar navbar-dark sticky-top flex-md-nowrap p-0 shadow">
            <span class="p-3 head1">Interview Process</span>
        </header>
    <form method="post" action="/reviewsubmit">
                @method('post')
                @csrf
                <input type="hidden" name="candidateId" value="{{ $interviews->Candidate->id }}" />
                <input type="hidden" name="interviewId" value="{{ $interviews->id }}" />
                <input type="hidden" name="inteviewScheduleMappingsId" value="{{ request()->details }}" />

        <div class="main-container row  p-5">
            <div class="col-lg-6 ">
                <div class="d-flex justify-content-center">
                    <div class="card col-lg-12">
                            <div class="card-body">
                                <div class="form-inline my-3">
                                <label for="type" class="text1">Total Ratings: </label> 
                                <input type="number" 
                                    name="total_rating" 
                                    class="form-control col-lg-6 ml-3"
                                        placeholder="Total Ratings" 
                                        value="{{ old('total_rating') }}" 
                                        step="any"
                                        min="1" max="10" />
                                            </div>
                                    @error('total_rating')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <div>
                                    <table class="table table-responsive">
                                        <thead>
                                            <th style="width:11rem;">Skill</th>
                                            <th style="width:13rem;">Self Ratings</th>
                                            <th style="width: 15rem">Theory Ratings</th>
                                            <th style="width: 15rem">Practical Ratings</th>
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
                                    <div><button class="btn btn-primary" type="submit">Submit</div>
                            </div>
                    </div>
                </div>
        
            </div>
                <div class="col-lg-6 second-div">
                        <div class="d-flex justify-content-center">
                            <div class="card col-lg-12">
                                <div class="card-body">
                                    <h5 class="card-title head1 mb-4 d-flex justify-content-between border-bottom">
                                        <div class="d-flex align-items-center"><i class="fa fa-group"></i>&nbsp; Candidate Details</div>
                                        <div>  <a href={{ $interviews->Candidate->resume_id }} class='btn btn-primary'
                                                    target='blanck'> Resume </a></div>
                                    </h5>
                                    <h6 class="card-subtitle p-3 text1"><span class="card-data text1">Name: </span><span class="head2">{{ $interviews->Candidate->name }}</span></h6>
                                    <h6 class="card-subtitle p-3 text1"><span class="card-data text1">Email: </span><span class="head2">{{ $interviews->Candidate->email }}</span></h6>
                                    <h6 class="card-subtitle p-3 text1"><span class="card-data text1">Contact Number: </span><span class="head2">{{ $interviews->Candidate->contect_no }}</span></h6>
                                    <h6 class="card-subtitle p-3 text1"><span class="card-data text1">Remarks: </span><span class="head2">{{ $interviews->remarks}}</span></h6>
                                </div>
                                <div class="card-body">
                                <h5 class="card-title head1  border-bottom"><div><i class="fa fa-clock-o"></i>&nbsp; Interview Details</div></h5>
                                <h6 class="card-subtitle p-3 text1"><span class="card-data text1">Interviewer: </span><span class="head2">{{ $interviews->Interviewer_id->name }}</span></h6>
                                <h6 class="card-subtitle p-3 text1"><span class="card-data text1">Date & Time: </span><span class="head2">{{ $interviews->date }}</span></h6>
                                <h6 class="card-subtitle p-3 text1"><span class="card-data text1">Interview Type: </span><span class="head2">{{ $interviews->interview_type->interview_type }}</span></h6>
                                <h6 class="card-subtitle p-3 text1"><span class="card-data text1">Interview Mode: </span><span class="head2">{{ $interviews->Interview_mode->interview_mode }}&nbsp; (<span>{{$interviews->location_link }}</span>) </span></h6>
                                </div>
                            </div>
                        </div>
                </div>
        </div>
    </form>
</div>

</body>

</html>
