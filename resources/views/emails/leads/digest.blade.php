@component('mail::message')
# Your Weekly High-Scoring Leads Digest

Hello there!

Here are the top high-scoring leads from the past week. Time to connect with them and close some deals!

@component('mail::table')
| Name          | Email         | Score         |
| :------------ |:--------------|:--------------|
@foreach($leads as $lead)
| {{ $lead->name }} | {{ $lead->email }} | {{ $lead->score }}     |
@endforeach
@endcomponent

@component('mail::button', ['url' => route('dashboard')])
View Full Dashboard
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
