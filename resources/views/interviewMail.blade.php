<p>Dear {{ $details['name'] }},</p>
<p>Kindly consider below details for the interview.</p>
<p>Name of Candidate: {{ $details['candidateName'] }}</p>
<p>Skills: {{ $details['skills'] }}</p>
<p>Date & Time of Interview: <a href="{{ $details['googleCalendarUrl'] }}" target="_blank">{{ $details['date'] }}</a></p>
<p>Type: {{ $details['type'] }}</p>
<p>Mode: {{ $details['mode'] }}</p>
<p>Details: {{ $details['details'] }}</p>
<p>Link to submit marks: {{ $details['linkToSubmitMarks'] }}</p><br />
<p>Regards,</p>
<p>WeyBee Solutions Pvt. Ltd.</p>

